<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class UploadController extends BaseController
{
    /**
     * Process file upload from FilePond
     */
    public function process()
    {
        // Set JSON response header
        $this->response->setContentType('application/json');

        // Check if file was uploaded
        $file = $this->request->getFile('thumbnail_path');

        if (!$file || !$file->isValid()) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'No valid file uploaded'
            ]);
        }

        // Validate file type and size
        $validation = $this->validate([
            'thumbnail_path' => [
                'rules' => 'is_image[thumbnail_path]|mime_in[thumbnail_path,image/jpg,image/jpeg,image/gif,image/png,image/webp]|max_size[thumbnail_path,5120]',
                'errors' => [
                    'is_image' => 'The selected file is not a valid image',
                    'mime_in' => 'The file must be an image (JPG, JPEG, PNG, GIF, or WEBP)',
                    'max_size' => 'File size must be less than 5MB'
                ]
            ],
        ]);

        if (!$validation) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => $this->validator->listErrors()
            ]);
        }

        try {
            // Generate unique filename
            $fileName = uniqid() . '_' . time() . '.webp';
            $tempPath = FCPATH . 'uploads/temp/' . $fileName;

            // Ensure temp directory exists
            if (!is_dir(FCPATH . 'uploads/temp/')) {
                mkdir(FCPATH . 'uploads/temp/', 0755, true);
            }

            // Move file to temp location first
            $originalTempName = uniqid() . '_original_' . $file->getName();
            $originalTempPath = FCPATH . 'uploads/temp/' . $originalTempName;
            $file->move(FCPATH . 'uploads/temp/', $originalTempName);

            // Process image with memory optimization
            $this->processImageWithCI($originalTempPath, $tempPath);

            // Delete original uploaded file
            if (file_exists($originalTempPath)) {
                unlink($originalTempPath);
            }

            // Return the temporary file identifier
            return $this->response->setBody($fileName);

        } catch (\Exception $e) {
            // Clean up any temporary files on error
            if (isset($originalTempPath) && file_exists($originalTempPath)) {
                unlink($originalTempPath);
            }
            if (isset($tempPath) && file_exists($tempPath)) {
                unlink($tempPath);
            }

            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Upload failed: ' . $e->getMessage()
            ]);
        }
    }


    /**
     * Process image using CodeIgniter's Image library
     *
     * @param string $sourcePath Path to the source image
     * @param string $destinationPath Path to save the processed image
     * @throws \Exception
     */
    private function processImageWithCI($sourcePath, $destinationPath)
    {
        $image = service('image');

        // Get image info first
        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) {
            throw new \Exception('Invalid image file');
        }

        $sourceWidth = $imageInfo[0];
        $sourceHeight = $imageInfo[1];

        // Calculate new dimensions
        $maxWidth = 1200;
        $maxHeight = 630;

        $scaleX = $maxWidth / $sourceWidth;
        $scaleY = $maxHeight / $sourceHeight;
        $scale = min($scaleX, $scaleY, 1);

        $newWidth = (int) ($sourceWidth * $scale);
        $newHeight = (int) ($sourceHeight * $scale);

        // Process with CI Image library
        $image->withFile($sourcePath)
            ->resize($newWidth, $newHeight, true, 'center')
            ->convert(IMAGETYPE_WEBP)
            ->save($destinationPath, 85);

        // Force garbage collection
        if (function_exists('gc_collect_cycles')) {
            gc_collect_cycles();
        }
    }

    /**
     * Revert/delete temporary file from FilePond
     */
    public function revert()
    {
        $this->response->setContentType('application/json');

        // Get the file identifier from request body
        $fileId = file_get_contents('php://input');

        if (empty($fileId)) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'No file identifier provided'
            ]);
        }

        $filePath = FCPATH . 'uploads/temp/' . $fileId;

        // Delete the temporary file
        if (file_exists($filePath)) {
            unlink($filePath);
            return $this->response->setStatusCode(200)->setJSON([
                'success' => 'File successfully removed'
            ]);
        }

        return $this->response->setStatusCode(404)->setJSON([
            'error' => 'File not found'
        ]);
    }

    /**
     * Load existing file for FilePond (for edit mode)
     */
    public function load()
    {
        $fileId = $this->request->getGet('id');

        if (empty($fileId)) {
            return $this->response->setStatusCode(400);
        }

        $filePath = FCPATH . 'uploads/thumbnails/' . $fileId;

        if (!file_exists($filePath)) {
            return $this->response->setStatusCode(404);
        }

        // Return the file
        return $this->response->download($filePath, null);
    }

    /**
     * Clean up old temporary files (can be called via cron job)
     */
    public function cleanupTempFiles()
    {
        $tempDir = FCPATH . 'uploads/temp/';

        if (!is_dir($tempDir)) {
            return $this->response->setJSON(['message' => 'Temp directory does not exist']);
        }

        $files = glob($tempDir . '*');
        $deletedCount = 0;
        $currentTime = time();

        // Delete files older than 1 hour
        foreach ($files as $file) {
            if (is_file($file) && ($currentTime - filemtime($file)) > 3600) {
                unlink($file);
                $deletedCount++;
            }
        }

        return $this->response->setJSON([
            'message' => "Cleaned up {$deletedCount} temporary files"
        ]);
    }
}