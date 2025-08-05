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
    protected $image;
    protected $validation;
    protected $ckEditorLicenseKey;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->postModel = new PostModel();
        $this->image = service('image');
        $this->validation = service('validation');
        $this->ckEditorLicenseKey = ENVIRONMENT !== 'production' ? env('CKEDITOR_LICENSE_DEV_KEY') : env('CKEDITOR_LICENSE_PROD_KEY');

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
            'categories' => $this->categoryModel->asObject()->findAll(),
            'validation' => service('validation'),
            'ckEditorLicenseKey' => $this->ckEditorLicenseKey,
        ];
        return view('admin/posts/create', $data);
    }

    /**
     * store function
     */
    public function store()
    {
        helper('form');
        if ($this->request->isAjax()) {

            $this->validate([
                'title' => [
                    'rules' => 'required|max_length[255]',
                    'errors' => [
                        'max_length' => 'Post Should be 255 chars or less',
                        'required' => 'Post Title Required',

                    ]
                ],
                'slug' => [
                    'rules' => 'required|is_unique[posts.slug]|max_length[255]|regex_match[/^[a-z0-9]+(?:-[a-z0-9]+)*$/]',
                    'errors' => [
                        'max_length' => 'Post Slug should be 255 chars or less',
                        'required' => 'Post Slug Required',
                        'regex_match' => 'Only lowercase letters, numbers, and hyphens allowed.',
                        'is_unique' => '{value} already taken'
                    ]
                ],
                'meta_description' => [
                    'rules' => 'required|max_length[255]',
                    'errors' => [
                        'required' => 'Meta Description required',
                        'max_length' => 'Meta Description should be less than 255 characters',

                    ]
                ],
                'thumbnail_path' => [
                    'rules' => 'uploaded[thumbnail_path]|is_image[thumbnail_path]|max_size[thumbnail_path,2048]|ext_in[thumbnail_path,jpg,jpeg,webp,png,gif]',
                    'errors' => [
                        'uploaded' => 'Post thumbnail is required',
                        'is_image' => 'Post thumbnail must be an image',
                        'max_size' => 'Post thumbnail should not be larger than 2MB',
                        'ext_in' => 'Post thumbnail must be a valid image format (jpg, jpeg, png, gif, webp)'
                    ]
                ],
                'thumbnail_caption' => [
                    'rules' => 'required|regex_match[/^[a-zA-Z0-9\s\-_.,!?()&:;"\']+$/]|max_length[255]',
                    'errors' => [
                        'required' => 'Post thumbnail caption Required',
                        'regex_match' => 'Post thumbnail caption should be valid text with allowed punctuation'
                    ]
                ],
                'content' => [
                    'rules' => 'required|min_length[20]',
                    'errors' => [
                        'required' => 'Post content required',
                        'min_length' => 'Post content should be at least 20 characters long'
                    ]
                ],
                'category_id' => [
                    'rules' => 'required|is_natural_no_zero|is_not_unique[categories.id]',
                    'errors' => [
                        'required' => 'Post category required',
                        'is_natural_no_zero' => 'Post category id should be a number',
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


            if ($this->validation->run() === FALSE) {
                $errors = $this->validation->getErrors();
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'error' => $errors
                ]);
            } else {
                $username = auth()->user()->username;
                $path = 'uploads/thumbnails/';
                $file = $this->request->getFile('thumbnail_path');
                $filename = $file->getRandomName();

                // Create thumbnails directory inside of uploads directory inside public folder if not exits
                if (!is_dir($path)) {
                    mkdir($path, 0755, true);
                }

                // Move the uploaded file to the thumbnails directory
                if ($file->move($path, $filename)) {
                    $this->image->withFile($path . $filename)
                        ->convert(IMAGETYPE_WEBP)
                        ->save($path . $filename);


                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'msg' => 'Failed to upload thumbnail',
                        'token' => csrf_hash()
                    ]);
                }

                // Insert data into database
                $post_save = $this->postModel->insert([
                    'title' => $this->request->getPost('title', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                    'slug' => $this->request->getPost('slug'),
                    'meta_description' => $this->request->getPost('meta_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                    'thumbnail_caption' => $this->request->getPost('thumbnail_caption'),
                    'thumbnail_path' => $filename,
                    'username' => $username,
                    'content' => $this->request->getPost('content'),
                    'category_id' => $this->request->getPost('category_id'),
                    'status' => $this->request->getPost('status')
                ]);

                if ($post_save) {

                    return $this->response->setJSON([
                        'status' => 1,
                        'msg' => 'Post created successfully',
                        'token' => csrf_hash()
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'msg' => 'Failed to create post',
                        'token' => csrf_hash()
                    ]);
                }
            }

        } else {
            return $this->response->setJSON([
                'status' => 0,
                'msg' => 'Invalid request',
                'token' => csrf_hash()
            ]);
        }
    }

    /**
     * edit function
     */
    public function edit($id)
    {
        helper('form');
        $post = $this->postModel->asObject()->find($id);

        if (!$post) {
            return redirect()->to('admin/posts')->with('error', 'Post not found');
        }


        $data = [
            'title' => 'Edit Post',
            'post' => $post,
            'categories' => $this->categoryModel->asObject()->findAll(),
            'ckEditorLicenseKey' => $this->ckEditorLicenseKey,



        ];


        return view('admin/posts/edit', $data);
    }

    public function update()
    {

        helper('form');

        if ($this->request->isAjax()) {
            $post_id = $this->request->getVar('post_id');

            // IF Form request has thumbnail
            if (isset($_FILES['thumbnail_path']) && !empty($_FILES['thumbnail_path']['name'])) {
                $this->validate([
                    'title' => [
                        'rules' => 'required|max_length[255]',
                        'errors' => [
                            'max_length' => 'Post Should be 255 chars or less',
                            'required' => 'Post Title Required',
                        ]
                    ],
                    'slug' => [
                        'rules' => 'required|max_length[255]|regex_match[/^[a-z0-9]+(?:-[a-z0-9]+)*$/]|is_unique[posts.slug,id,' . $post_id . ']',
                        'errors' => [
                            'max_length' => 'Post Slug should be 255 chars or less',
                            'required' => 'Post Slug Required',
                            'regex_match' => 'Only lowercase letters, numbers, and hyphens allowed.',
                            'is_unique' => '{value} already taken'
                        ]
                    ],
                    'meta_description' => [
                        'rules' => 'required|max_length[255]',
                        'errors' => [
                            'required' => 'Meta Description required',
                            'max_length' => 'Meta Description should be less than 255 characters',
                        ]
                    ],
                    'thumbnail_path' => [
                        'rules' => 'uploaded[thumbnail_path]|is_image[thumbnail_path]|max_size[thumbnail_path,2048]|ext_in[thumbnail_path,jpg,jpeg,webp,png,gif]',
                        'errors' => [
                            'uploaded' => 'Post thumbnail is required',
                            'is_image' => 'Post thumbnail must be an image',
                            'max_size' => 'Post thumbnail should not be larger than 2MB',
                            'ext_in' => 'Post thumbnail must be a valid image format (jpg, jpeg, png, gif, webp)'
                        ]
                    ],
                    'thumbnail_caption' => [
                        'rules' => 'required|regex_match[/^[a-zA-Z0-9\s\-_.,!?()&:;"\']+$/]|max_length[255]',
                        'errors' => [
                            'required' => 'Post thumbnail caption Required',
                            'regex_match' => 'Post thumbnail caption should be valid text with allowed punctuation'
                        ]
                    ],
                    'content' => [
                        'rules' => 'required|min_length[20]',
                        'errors' => [
                            'required' => 'Post content required',
                            'min_length' => 'Post content should be at least 20 characters long'
                        ]
                    ],
                    'category_id' => [
                        'rules' => 'required|is_natural_no_zero|is_not_unique[categories.id]',
                        'errors' => [
                            'required' => 'Post category required',
                            'is_natural_no_zero' => 'Post category id should be a number',
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
            }
            // IF Form request do not has thumbnail
            else {
                $this->validate([
                    'title' => [
                        'rules' => 'required|max_length[255]',
                        'errors' => [
                            'max_length' => 'Post Should be 255 chars or less',
                            'required' => 'Post Title Required',
                        ]
                    ],
                    'slug' => [
                        'rules' => 'required|max_length[255]|regex_match[/^[a-z0-9]+(?:-[a-z0-9]+)*$/]|is_unique[posts.slug,id,' . $post_id . ']',
                        'errors' => [
                            'max_length' => 'Post Slug should be 255 chars or less',
                            'required' => 'Post Slug Required',
                            'regex_match' => 'Only lowercase letters, numbers, and hyphens allowed.',
                            'is_unique' => '{value} already taken'
                        ]
                    ],
                    'meta_description' => [
                        'rules' => 'required|max_length[255]',
                        'errors' => [
                            'required' => 'Meta Description required',
                            'max_length' => 'Meta Description should be less than 255 characters',
                        ]
                    ],
                    'thumbnail_caption' => [
                        'rules' => 'required|regex_match[/^[a-zA-Z0-9\s\-_.,!?()&:;"\']+$/]|max_length[255]',
                        'errors' => [
                            'required' => 'Post thumbnail caption Required',
                            'regex_match' => 'Post thumbnail caption should be valid text with allowed punctuation'
                        ]
                    ],
                    'content' => [
                        'rules' => 'required|min_length[20]',
                        'errors' => [
                            'required' => 'Post content required',
                            'min_length' => 'Post content should be at least 20 characters long'
                        ]
                    ],
                    'category_id' => [
                        'rules' => 'required|is_natural_no_zero|is_not_unique[categories.id]',
                        'errors' => [
                            'required' => 'Post category required',
                            'is_natural_no_zero' => 'Post category id should be a number',
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
            }


            if ($this->validation->run() === FALSE) {
                $errors = $this->validation->getErrors();
                log_message('error', 'Validation errors: ' . json_encode($errors));
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'error' => $errors
                ]);
            } else {
                if (isset($_FILES['thumbnail_path']) && !empty($_FILES['thumbnail_path']['name'])) {
                    $path = 'uploads/thumbnails/';
                    $file = $this->request->getFile('thumbnail_path');
                    $filename = $file->getRandomName();

                    $old_thumbnail_path = $this->postModel->asObject()->find($post_id)->thumbnail_path;
                    if ($file->move($path, $filename)) {
                        // Convert image to webp
                        $this->image->withFile($path . $filename)
                            ->convert(IMAGETYPE_WEBP)
                            ->save($path . $filename);

                        // Delete old thumbnail
                        if ($old_thumbnail_path != null && file_exists($path . $old_thumbnail_path)) {
                            unlink($path . $old_thumbnail_path);
                        }
                    }
                    $post_update = $this->postModel->update($post_id, [
                        'title' => $this->request->getPost('title', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                        'slug' => $this->request->getPost('slug'),
                        'meta_description' => $this->request->getPost('meta_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                        'thumbnail_caption' => $this->request->getPost('thumbnail_caption'),
                        'thumbnail_path' => $filename,
                        'content' => $this->request->getPost('content'),
                        'category_id' => $this->request->getPost('category_id'),
                        'status' => $this->request->getPost('status')
                    ]);

                    if ($post_update) {
                        log_message('info', 'Post updated successfully: ' . $post_id);
                        return $this->response->setJson([
                            'status' => 1,
                            'msg' => 'Post updated successfully',
                            'token' => csrf_hash()
                        ]);
                    } else {
                        log_message('error', 'Failed to update post: ' . $post_id);
                        return $this->response->setJson([
                            'status' => 0,
                            'msg' => 'Failed to update post',
                            'token' => csrf_hash()
                        ]);
                    }


                } else {
                    $post_update = $this->postModel->update($post_id, [
                        'title' => $this->request->getPost('title', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                        'slug' => $this->request->getPost('slug'),
                        'meta_description' => $this->request->getPost('meta_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                        'thumbnail_caption' => $this->request->getPost('thumbnail_caption'),

                        'content' => $this->request->getPost('content'),
                        'category_id' => $this->request->getPost('category_id'),
                        'status' => $this->request->getPost('status')
                    ]);

                    if ($post_update) {
                        log_message('info', 'Post updated successfully: ' . $post_id);
                        return $this->response->setJson([
                            'status' => 1,
                            'msg' => 'Post updated successfully',
                            'token' => csrf_hash()
                        ]);
                    } else {
                        log_message('error', 'Failed to update post: ' . $post_id);
                        return $this->response->setJson([
                            'status' => 0,
                            'msg' => 'Failed to update post',
                            'token' => csrf_hash()
                        ]);
                    }

                }
            }


        } else {
            return $this->response->setJSON([
                'status' => 0,
                'msg' => 'Invalid request',
                'token' => csrf_hash()
            ]);
        }

    }

    public function delete($id)
    {
        helper(['url']);

        $post = $this->postModel->find($id);

        if ($post) {
            // Delete associated thumbnail
            $thumbnail_path = 'uploads/thumbnails/' . $post['thumbnail_path'];
            if (file_exists($thumbnail_path)) {
                unlink($thumbnail_path);
            }
            $this->postModel->delete($id);

            //flash message
            session()->setFlashdata('success', 'Post Deleted');

            return redirect()->to(base_url('admin/posts'));
        }
    }



}