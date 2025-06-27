<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PostModel;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class PostController extends BaseController
{
    protected $categoryModel;

    protected $postModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->postModel = new PostModel();
    }



    public function index()
    {
        //pager initialize
        $pager = \Config\Services::pager();
        $username = auth()->user()->username;

        $data = [
            'title' => 'Manage Posts',
            'posts' => $this->postModel->where('username', $username)->paginate(10, 'post'),
            'pager' => $this->postModel->pager
        ];

        return view('admin/posts/index', $data);


    }

    /**
     * create function
     */

    public function create()
    {
        helper('form');
        $data = [
            'title' => 'Create Post',
            'categories' => $this->categoryModel->findAll() // Get all categories
        ];
        return view('admin/posts/create', $data);
    }


    /**
     * store function
     */


    public function store()
    {
        helper('form');

        $validation = $this->validate([
            'title' => [
                'rules' => 'required|alpha_numeric_space|max_length[150]',
                'errors' => [
                    'max_length' => 'Post Title too long',
                    'required' => 'Post Title Required',
                    'alpha_numeric_space' => 'Post Title should be a text'
                ]
            ],
            'meta_description' => [
                'rules' => 'required|alpha_numeric_space|max_length[150]',
                'errors' => [
                    'required' => 'Meta Description Required',
                    'alpha_numeric_space' => 'Meta Description should be a text'
                ]
            ],
            'thumbnail_caption' => [
                'rules' => 'required|alpha_numeric_space',
                'errors' => [
                    'required' => 'Post thumbnail caption Required',
                    'alpha_numeric_space' => 'Post thumbnail caption should be a text'
                ]
            ],
            'content' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Post content required'
                ]
            ],
            'category_name' => [
                'rules' => 'required|alpha_numeric_space|is_not_unique[categories.name]',
                'errors' => [
                    'required' => 'Post category required',
                    'alpha_numeric_space' => 'Post category should be a text',
                    'is_not_unique' => 'Selected category does not exist'
                ]
            ],
        ]);

        if (!$validation) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->to(base_url('admin/posts/create'));
        } else {
            $username = auth()->user()->username;
            $thumbnail = null;

            // Check if there's a temporary file from FilePond
            $tempFileId = $this->request->getPost('temp_file_id');

            if ($tempFileId) {
                $tempPath = FCPATH . 'uploads/temp/' . $tempFileId;

                if (file_exists($tempPath)) {
                    // Generate final filename
                    $thumbnail = $tempFileId;

                    // Move from temp to permanent location
                    $finalPath = FCPATH . 'uploads/thumbnails/' . $thumbnail;

                    // Ensure thumbnails directory exists
                    if (!is_dir(FCPATH . 'uploads/thumbnails/')) {
                        mkdir(FCPATH . 'uploads/thumbnails/', 0755, true);
                    }

                    rename($tempPath, $finalPath);
                }
            } else {
                // Fallback to traditional file upload (if FilePond fails)
                $file_thumbnail = $this->request->getFile('thumbnail_path');

                if ($file_thumbnail && $file_thumbnail->isValid() && !$file_thumbnail->hasMoved()) {
                    // Generate a random name with .webp extension
                    $thumbnail = pathinfo($file_thumbnail->getRandomName(), PATHINFO_FILENAME) . '.webp';

                    // First save the original upload
                    $file_thumbnail->move(FCPATH . 'uploads/thumbnails/temp', $file_thumbnail->getName());

                    // Convert to WebP
                    service('image')
                        ->withFile(FCPATH . 'uploads/thumbnails/temp/' . $file_thumbnail->getName())
                        ->convert(IMAGETYPE_WEBP)
                        ->save(FCPATH . 'uploads/thumbnails/' . $thumbnail);

                    // Delete temporary file
                    unlink(FCPATH . 'uploads/thumbnails/temp/' . $file_thumbnail->getName());
                }
            }

            if (!$thumbnail) {
                session()->setFlashdata('error', 'Please select a thumbnail image');
                return redirect()->to(base_url('admin/posts/create'));
            }

            // Insert data into database
            $this->postModel->insert([
                'title' => $this->request->getPost('title'),
                'slug' => $this->postModel->setSlug($this->request->getPost('title')),
                'meta_description' => $this->request->getPost('meta_description'),
                'thumbnail_caption' => $this->request->getPost('thumbnail_caption'),
                'thumbnail_path' => $thumbnail,
                'username' => $username,
                'content' => $this->request->getPost('content'),
                'category_name' => $this->request->getPost('category_name')
            ]);

            session()->setFlashdata('success', 'New post added');
            return redirect()->to(base_url('admin/posts'));
        }
    }

    /**
     * edit function
     */
    public function edit($id)
    {
        helper('form');

        $data = array(
            'title' => 'Edit Post',
            'post' => $this->postModel->find($id),
            'categories' => $this->categoryModel->findAll() // Get all categories
        );

        return view('admin/posts/edit', $data);
    }


    public function update($id)
    {
        helper('form');

        $oldData = $this->postModel->find($id);

        if (!$oldData) {
            return redirect()->to('admin/posts')->with('error', 'Post not found');
        }

        // Validation rules (thumbnail is optional for updates)
        $validation = $this->validate([
            'title' => [
                'rules' => 'required|alpha_numeric_space|max_length[150]',
                'errors' => [
                    'max_length' => 'Post Title too long',
                    'required' => 'Post Title Required',
                    'alpha_numeric_space' => 'Post Title should be a text'
                ]
            ],
            'content' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Post content required'
                ]
            ],
            'category_name' => [
                'rules' => 'required|alpha_numeric_space|is_not_unique[categories.name]',
                'errors' => [
                    'required' => 'Post category required',
                    'alpha_numeric_space' => 'Post category should be a text',
                    'is_not_unique' => 'Selected category does not exist'
                ]
            ],
            'meta_description' => [
                'rules' => 'required|alpha_numeric_space|max_length[150]',
                'errors' => [
                    'required' => 'Meta Description Required',
                    'alpha_numeric_space' => 'Meta Description should be a text'
                ]
            ],
            'thumbnail_caption' => [
                'rules' => 'max_length[200]',
                'errors' => [
                    'max_length' => 'Post thumbnail caption should be less than 200 characters',
                    'required' => 'Post thumbnail caption Required',
                    'alpha_numeric_space' => 'Post thumbnail caption should be a text'
                ]
            ]
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Get the new data
        $newData = [
            'title' => $this->request->getPost('title'),
            'meta_description' => $this->request->getPost('meta_description'),
            'slug' => $this->postModel->setSlug($this->request->getPost('title')),
            'content' => $this->request->getPost('content'),
            'thumbnail_caption' => $this->request->getPost('thumbnail_caption'),
            'category_name' => $this->request->getPost('category_name')
        ];

        // Check if any changes were made
        $changes = false;

        // Compare title, content and category
        foreach (['title', 'content', 'category_name', 'meta_description', 'thumbnail_caption'] as $field) {
            if ($oldData[$field] !== $newData[$field]) {
                $changes = true;
            }
        }

        // Handle thumbnail upload from FilePond
        $tempFileId = $this->request->getPost('temp_file_id');

        if ($tempFileId) {
            $tempPath = FCPATH . 'uploads/temp/' . $tempFileId;

            if (file_exists($tempPath)) {
                $changes = true;

                // Delete old thumbnail if exists
                if (!empty($oldData['thumbnail_path']) && file_exists(FCPATH . 'uploads/thumbnails/' . $oldData['thumbnail_path'])) {
                    unlink(FCPATH . 'uploads/thumbnails/' . $oldData['thumbnail_path']);
                }

                // Move from temp to permanent location
                $newThumbnail = $tempFileId;
                $finalPath = FCPATH . 'uploads/thumbnails/' . $newThumbnail;

                // Ensure thumbnails directory exists
                if (!is_dir(FCPATH . 'uploads/thumbnails/')) {
                    mkdir(FCPATH . 'uploads/thumbnails/', 0755, true);
                }

                rename($tempPath, $finalPath);
                $newData['thumbnail_path'] = $newThumbnail;
            }
        } else {
            // Fallback to traditional file upload (if FilePond fails)
            $thumbnail = $this->request->getFile('thumbnail_path');
            if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
                $changes = true;

                // Delete old thumbnail if exists
                if (!empty($oldData['thumbnail_path']) && file_exists(FCPATH . 'uploads/thumbnails/' . $oldData['thumbnail_path'])) {
                    unlink(FCPATH . 'uploads/thumbnails/' . $oldData['thumbnail_path']);
                }

                // Generate a random name with .webp extension
                $newName = pathinfo($thumbnail->getRandomName(), PATHINFO_FILENAME) . '.webp';

                // First save the original upload
                $thumbnail->move(FCPATH . 'uploads/thumbnails/temp', $thumbnail->getName());

                // Convert to WebP
                service('image')
                    ->withFile(FCPATH . 'uploads/thumbnails/temp/' . $thumbnail->getName())
                    ->convert(IMAGETYPE_WEBP)
                    ->save(FCPATH . 'uploads/thumbnails/' . $newName);

                // Delete temporary file
                unlink(FCPATH . 'uploads/thumbnails/temp/' . $thumbnail->getName());

                $newData['thumbnail_path'] = $newName;
            }
        }

        // If no changes detected
        if (!$changes) {
            return redirect()->to('admin/posts')->with('info', 'No changes');
        }

        // Update the post slug if title changed
        if ($oldData['title'] !== $newData['title']) {
            $newData['slug'] = $this->postModel->setSlug($newData['title']);
        }

        // Update the post
        try {
            $this->postModel->update($id, $newData);
            return redirect()->to('admin/posts')->with('success', 'Post updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update post: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        //model initialize
        helper(['url']);

        $post = $this->postModel->find($id);

        if ($post) {
            $this->postModel->delete($id);

            //flash message
            session()->setFlashdata('success', 'Post Deleted');

            return redirect()->to(base_url('admin/posts'));
        }
    }

    public function uploadThumbnail()
    {
        $upload_status = '';
        $file = $this->request->getFile('file');

        if (!$file->isValid()) {
            $upload_status = 'failed';
            return $this->response->setJSON(['error' => 'Invalid file upload']);
        }
        $upload_status = 'success';

        // Generate a random name with .webp extension
        $thumbnail = pathinfo($file->getRandomName(), PATHINFO_FILENAME) . '.webp';

        // First save the original upload
        $file->move(FCPATH . 'uploads/thumbnails/temp', $file->getName());

        // Convert to WebP
        service('image')
            ->withFile(FCPATH . 'uploads/thumbnails/temp/' . $file->getName())
            ->convert(IMAGETYPE_WEBP)
            ->save(FCPATH . 'uploads/thumbnails/' . $thumbnail);

        // Delete temporary file
        unlink(FCPATH . 'uploads/thumbnails/temp/' . $file->getName());
        echo $upload_status;
        return $this->response->setJSON(['path' => base_url('uploads/thumbnails/' . $thumbnail)]);
    }

}