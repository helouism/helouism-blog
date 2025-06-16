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
                'rules' => 'required|alpha_space|max_length[150]',
                'errors' => [
                    'max_length' => 'Post Title too long',
                    'required' => 'Post Title Required',
                    'alpha_space' => 'Post Title should be a text'

                ]
            ],
            'thumbnail_caption' => [
                'rules' => 'required|alpha_space',
                'errors' => [
                    'required' => 'Post thumbnail caption Required',
                    'alpha_space' => 'Post thumbnail caption should be a text'

                ]
            ],
            'thumbnail_path' => [
                'rules' => 'uploaded[thumbnail_path]|is_image[thumbnail_path]|mime_in[thumbnail_path,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                'errors' => [
                    'uploaded' => 'Please select an image',
                    'is_image' => 'The selected file is not a valid image',
                    'mime_in' => 'The file must be an image (JPG, JPEG, PNG, GIF, or WEBP)'
                ]
            ],

            'content' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Post content required'
                ]
            ],
            'category_name' => [
                'rules' => 'required|alpha_space|is_not_unique[categories.name]',
                'errors' => [
                    'required' => 'Post category required',
                    'alpha_space' => 'Post category should be a text',
                    'is_not_unique' => 'Selected category does not exist'
                ]
            ],


        ]);


        if (!$validation) {

            //render view with error validation message
            session()->setFlashdata('error', $this->validator->listErrors());

            return redirect()->to(base_url('admin/posts/create'));

        } else {
            $username = auth()->user()->username;
            $file_thumbnail = $this->request->getFile('thumbnail_path');
            $thumbnail = $file_thumbnail->getRandomName();
            $file_thumbnail->move(ROOTPATH . 'public/uploads/thumbnails', $thumbnail);

            //insert data into database
            $this->postModel->insert([
                'title' => $this->request->getPost('title'),
                'slug' => $this->postModel->setSlug($this->request->getPost('title')),
                'thumbnail_caption' => $this->request->getPost('thumbnail_caption'),
                'thumbnail_path' => $file_thumbnail->getName(),
                'username' => $username,
                'content' => $this->request->getPost('content'),
                'category_name' => $this->request->getPost('category_name')

            ]);

            //flash message
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

        // Validation rules
        $validation = $this->validate([
            'title' => [
                'rules' => 'required|alpha_space|max_length[150]',
                'errors' => [
                    'max_length' => 'Post Title too long',
                    'required' => 'Post Title Required',
                    'alpha_space' => 'Post Title should be a text'
                ]
            ],
            'content' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Post content required'
                ]
            ],
            'category_name' => [
                'rules' => 'required|alpha_space|is_not_unique[categories.name]',
                'errors' => [
                    'required' => 'Post category required',
                    'alpha_space' => 'Post category should be a text',
                    'is_not_unique' => 'Selected category does not exist'
                ]
            ],
            'thumbnail_caption' => [
                'rules' => 'max_length[200]',
                'errors' => [
                    'max_length' => 'Post thumbnail caption should be less than 200 characters',
                    'required' => 'Post thumbnail caption Required',
                    'alpha_space' => 'Post thumbnail caption should be a text'

                ]
            ],
            // Only validate thumbnail if one was uploaded
            'thumbnail_path' => [
                'rules' => 'permit_empty|is_image[thumbnail_path]|mime_in[thumbnail_path,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                'errors' => [
                    'is_image' => 'The selected file is not a valid image',
                    'mime_in' => 'The file must be an image (JPG, JPEG, PNG, GIF, or WEBP)'
                ]
            ]
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Get the new data
        $newData = [
            'title' => $this->request->getPost('title'),
            'slug' => $this->postModel->setSlug($this->request->getPost('title')),
            'content' => $this->request->getPost('content'),
            'thumbnail_caption' => $this->request->getPost('thumbnail_caption'),
            'category_name' => $this->request->getPost('category_name')
        ];

        // Check if any changes were made
        $changes = false;

        // Compare title, content and category
        foreach (['title', 'content', 'category_name'] as $field) {
            if ($oldData[$field] !== $newData[$field]) {
                $changes = true;
            }
        }

        // Handle thumbnail upload
        $thumbnail = $this->request->getFile('thumbnail_path');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            $changes = true;

            // Delete old thumbnail if exists
            if (!empty($oldData['thumbnail_path']) && file_exists(FCPATH . 'uploads/thumbnails/' . $oldData['thumbnail_path'])) {
                unlink(FCPATH . 'uploads/thumbnails/' . $oldData['thumbnail_path']);
            }

            // Save new thumbnail
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(FCPATH . 'uploads/thumbnails', $newName);
            $newData['thumbnail_path'] = $newName;
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

}
