<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\PostModel;
use CodeIgniter\HTTP\ResponseInterface;

class PostItemController extends BaseController
{
    protected $postModel;
    protected $categoryModel;
    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->categoryModel = new CategoryModel();
    }

    public function show($slug)
    {


        $post = $this->postModel->select('posts.*, categories.slug as category_slug')
            ->join('categories', 'categories.name = posts.category_name')
            ->where('posts.slug', $slug)
            ->first();

        if (!$post) {
            return redirect()->to('/')->with('error', 'Post not found');
        }

        $data = [
            'title' => $post['title'],
            'post' => $post,

        ];

        return view('post/show', $data);
    }


}
