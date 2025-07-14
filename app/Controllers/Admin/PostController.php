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

        $username = auth()->user()->username;

        $data = [
            'title' => 'Manage Posts',
            'posts' => $this->postModel->where('username', $username)->findAll(),

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

        if (
            !$this->validate([
                'title' => [
                    'rules' => 'required|regex_match[/^[\p{L}\p{N}\s\-\.,:!?"\']+$/u]|max_length[150]',
                    'errors' => [
                        'max_length' => 'Post Title too long',
                        'required' => 'Post Title Required',
                        'regex_match' => 'Post Title should be valid text with allowed punctuation.',
                    ]
                ],
                'slug' => [
                    'rules' => 'required|is_unique[posts.slug]|max_length[150]|regex_match[/^[a-z0-9]+(?:-[a-z0-9]+)*$/]',
                    'errors' => [
                        'max_length' => 'Post Slug too long',
                        'required' => 'Post Slug Required',
                        'regex_match' => 'Only lowercase letters, numbers, and hyphens allowed.',
                        'is_unique' => 'Post Slug already taken'
                    ]
                ],
                'meta_description' => [
                    'rules' => 'required|regex_match[/^[\p{L}\p{N}\s\-\.,:;!?"\'()]+$/u]|max_length[255]',
                    'errors' => [
                        'required' => 'Meta Description required',
                        'max_length' => 'Meta Description should be less than 255 characters',
                        'regex_match' => 'Meta Description should be valid text with allowed punctuation'
                    ]
                ],
                'thumbnail_caption' => [
                    'rules' => 'required|regex_match[/^[\p{L}\p{N}\s\-\.,:;!?"\'()]+$/u]|max_length[255]',
                    'errors' => [
                        'required' => 'Post thumbnail caption Required',
                        'regex_match' => 'Post thumbnail caption should be valid text with allowed punctuation'
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
            ])
        ) {
            $validation = service('validation');
            return redirect()->back()->withInput()->with('validation', $validation);
        }



        $username = auth()->user()->username;
        $thumbnail = null;

        // Handle thumbnail from FilePond
        $tempFileId = $this->request->getPost('temp_file_id');

        if ($tempFileId) {
            $thumbnail = $this->moveFromTempToPermanent($tempFileId);
        }

        if (!$thumbnail) {

            return redirect()->back()->withInput()->with('validation', 'Please upload an image');
        }

        // Insert data into database
        $this->postModel->insert([
            'title' => $this->request->getPost('title'),
            'slug' => $this->request->getPost('slug'),
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

        if (!$post) {
            return redirect()->to('admin/posts')->with('error', 'Post not found');
        }

        $post_category_id = $this->categoryModel->getNameFromId($post['category_id']);
        $data = [
            'title' => 'Edit Post',
            'post' => $post,
            'categories' => $this->categoryModel->findAll(),
            'category_name' => $post_category_id,
            'validation' => service('validation')

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
        if (
            !$this->validate([
                'title' => [
                    'rules' => 'required|regex_match[/^[\p{L}\p{N}\s\-\.,:!?"\']+$/u]|max_length[150]',
                    'errors' => [
                        'max_length' => 'Post Title should be less than 150 characters',
                        'required' => 'Post Title Required',
                        'regex_match' => 'Post Title should be valid text with allowed punctuation.',
                    ]
                ],
                'slug' => [
                    'rules' => 'required|is_unique[posts.slug]|max_length[150]|regex_match[/^[a-z0-9]+(?:-[a-z0-9]+)*$/]',
                    'errors' => [
                        'max_length' => 'Post Slug too long',
                        'required' => 'Post Slug Required',
                        'regex_match' => 'Only lowercase letters, numbers, and hyphens allowed.',
                        'is_unique' => 'Post Slug already taken'
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
                    'rules' => 'required|regex_match[/^[\p{L}\p{N}\s\-\.,:;!?"\'()]+$/u]|max_length[255]',
                    'errors' => [
                        'required' => 'Meta Description Required',
                        'regex_match' => 'Meta Description should be valid text with allowed punctuation',
                        'max_length' => 'Meta Description should be less than 255 characters'
                    ]
                ],
                'thumbnail_caption' => [
                    'rules' => 'max_length[200]|regex_match[/^[\p{L}\p{N}\s\-\.,:;!?"\'()]+$/u]',
                    'errors' => [
                        'max_length' => 'Post thumbnail caption should be less than 200 characters',
                        'required' => 'Post thumbnail caption Required',
                        'regex_match' => 'Post thumbnail caption should be valid text with allowed punctuation',
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
            ])
        ) {
            $validation = service('validation');
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        // Get the new data
        $newData = [
            'title' => $this->request->getPost('title'),
            'meta_description' => $this->request->getPost('meta_description'),
            'slug' => $this->request->getPost('slug'),
            'content' => $this->request->getPost('content'),
            'thumbnail_caption' => $this->request->getPost('thumbnail_caption'),
            'category_id' => $this->categoryModel->getIdFromName($this->request->getPost('category_name')),
            'status' => $this->request->getPost('status'),
        ];

        // Check if any changes were made
        $changes = false;

        // Compare title, content and category
        foreach (['title', 'slug', 'content', 'category_id', 'meta_description', 'thumbnail_caption', 'status'] as $field) {
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