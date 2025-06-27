<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class UploadController extends BaseController
{
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
        $validation = $this->validate([
            'thumbnail_path' => [
                'rules' => 'is_image[thumbnail_path]|mime_in[thumbnail_path,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                'errors' => [

                    'is_image' => 'The selected file is not a valid image',
                    'mime_in' => 'The file must be an image (JPG, JPEG, PNG, GIF, or WEBP)'
                ]
            ],

        ]);
        // Validate file type
        if (!$validation) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'File must be an image'
            ]);
        }

        // Check file size (max 5MB)
        if ($file->getSize() > 5242880) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'File size must be less than 5MB'
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
            $file->move(FCPATH . 'uploads/temp/', $file->getName());

            // Convert to WebP and save
            service('image')
                ->withFile(FCPATH . 'uploads/temp/' . $file->getName())
                ->convert(IMAGETYPE_WEBP)
                ->save($tempPath);

            // Delete original uploaded file
            unlink(FCPATH . 'uploads/temp/' . $file->getName());

            // Return the temporary file identifier
            return $this->response->setJSON($fileName);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Upload failed: ' . $e->getMessage()
            ]);
        }
    }

    public function revert()
    {



        $this->response->setContentType('application/json');
        // Get the file identifier from request body
        $fileId = file_get_contents('php://input');

        if (empty($fileId)) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => '400 Error'
            ]);
        }

        $filePath = FCPATH . 'uploads/temp/' . $fileId;

        // Delete the temporary file
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'success' => 'Successfully reverted',
        ]);
    }

    public function load()
    {
        // This is for loading existing files (optional)
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
}