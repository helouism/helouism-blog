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
                'rules' => 'required|alpha_numeric_punct|max_length[150]',
                'errors' => [
                    'max_length' => 'Post Title too long',
                    'required' => 'Post Title Required',
                    'alpha_numeric_punct' => 'Post Title should be a text'
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
            'status' => [
                'rules' => 'required|alpha_dash|in_list[published,draft]',
                'errors' => [
                    'required' => 'Post status Required',
                    'alpha_dash' => 'Post status is not a valid string',
                    'in_list' => 'Post status is not in list'
                ]
            ],
        ]);

        if (!$validation) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->to(base_url('admin/posts/create'));
        }

        $username = auth()->user()->username;
        $thumbnail = null;

        // Handle thumbnail from FilePond
        $tempFileId = $this->request->getPost('temp_file_id');

        if ($tempFileId) {
            $thumbnail = $this->moveFromTempToPermanent($tempFileId);
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
            'category_id' => $this->categoryModel->getIdFromName($this->request->getPost('category_name')),
            'status' => $this->request->getPost('status')
        ]);

        session()->setFlashdata('success', 'New post added');
        return redirect()->to(base_url('admin/posts'));
    }

    /**
     * edit function
     */
    public function edit($id)
    {
        helper('form');
        $post = $this->postModel->find($id);
        $post_category_id = $this->categoryModel->getNameFromId($post['category_id']);

        $data = [
            'title' => 'Edit Post',
            'post' => $post,
            'categories' => $this->categoryModel->findAll(),
            'category_name' => $post_category_id
        ];


        return view('admin/posts/edit', $data);
    }

    public function update($id)
    {
        helper('form');

        $oldData = $this->postModel->find($id);

        if (!$oldData) {
            return redirect()->to('admin/posts')->with('error', 'Post not found');
        }

        // Validation rules
        $validation = $this->validate([
            'title' => [
                'rules' => 'required|alpha_numeric_punct|max_length[150]',
                'errors' => [
                    'max_length' => 'Post Title too long',
                    'required' => 'Post Title Required',
                    'alpha_numeric_punct' => 'Post Title should be a text'
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
            ],
            'status' => [
                'rules' => 'required|alpha_dash|in_list[published,draft]',
                'errors' => [
                    'required' => 'Post status Required',
                    'alpha_dash' => 'Post status is not a valid string',
                    'in_list' => 'Post status is not in list'
                ]
            ],
        ]);

        if (!$validation) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->to(base_url('admin/posts/edit/' . $id));
        }

        // Get the new data
        $newData = [
            'title' => $this->request->getPost('title'),
            'meta_description' => $this->request->getPost('meta_description'),
            'slug' => $this->postModel->setSlug($this->request->getPost('title')),
            'content' => $this->request->getPost('content'),
            'thumbnail_caption' => $this->request->getPost('thumbnail_caption'),
            'category_id' => $this->categoryModel->getIdFromName($this->request->getPost('category_name')),
            'status' => $this->request->getPost('status'),
        ];

        // Check if any changes were made
        $changes = false;

        // Compare title, content and category
        foreach (['title', 'content', 'category_id', 'meta_description', 'thumbnail_caption'] as $field) {
            if ($oldData[$field] !== $newData[$field]) {
                $changes = true;
            }
        }

        // Handle thumbnail upload from FilePond
        $tempFileId = $this->request->getPost('temp_file_id');

        if ($tempFileId) {
            $newThumbnail = $this->moveFromTempToPermanent($tempFileId);

            if ($newThumbnail) {
                $changes = true;

                // Delete old thumbnail if exists
                $this->deleteOldThumbnail($oldData['thumbnail_path']);

                $newData['thumbnail_path'] = $newThumbnail;
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
        helper(['url']);

        $post = $this->postModel->find($id);

        if ($post) {
            // Delete associated thumbnail
            $this->deleteOldThumbnail($post['thumbnail_path']);

            $this->postModel->delete($id);

            //flash message
            session()->setFlashdata('success', 'Post Deleted');

            return redirect()->to(base_url('admin/posts'));
        }
    }

    /**
     * Move file from temp to permanent location
     */
    private function moveFromTempToPermanent($tempFileId)
    {
        $tempPath = FCPATH . 'uploads/temp/' . $tempFileId;

        if (!file_exists($tempPath)) {
            return null;
        }

        $finalPath = FCPATH . 'uploads/thumbnails/' . $tempFileId;

        // Ensure thumbnails directory exists
        if (!is_dir(FCPATH . 'uploads/thumbnails/')) {
            mkdir(FCPATH . 'uploads/thumbnails/', 0755, true);
        }

        if (rename($tempPath, $finalPath)) {
            return $tempFileId;
        }

        return null;
    }

    /**
     * Delete old thumbnail file
     */
    private function deleteOldThumbnail($thumbnailPath)
    {
        if (!empty($thumbnailPath) && file_exists(FCPATH . 'uploads/thumbnails/' . $thumbnailPath)) {
            unlink(FCPATH . 'uploads/thumbnails/' . $thumbnailPath);
        }
    }
}