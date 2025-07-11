<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\CategoryModel;

class Home extends BaseController
{
    protected $postModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->categoryModel = new CategoryModel();
    }





    public function index(): string
    {
        $data = [
            'title' => 'helouism',
            'posts' => $this->postModel->getHomePosts(),
            'categories' => $this->categoryModel->findAll(),
            'categoryPostCounts' => $this->categoryModel->getCategoryPostCounts(),
            'archive' => $this->postModel->getPostArchive(),
            'pager' => $this->postModel->pager
        ];
        return view('home', $data);
    }
}
