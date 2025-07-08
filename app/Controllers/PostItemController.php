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


        $post = $this->postModel->getPostItem($slug);
        $category_name = $this->categoryModel->getNameFromId($post['category_id']);

        if (!$post) {
            return redirect()->to('/')->with('error', 'Post not found');
        }



        $data = [
            'title' => $post['title'],
            'post' => $post,
            'category_name' => $category_name

        ];

        return view('post/show', $data);
    }


}
