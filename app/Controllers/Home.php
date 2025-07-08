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

    private function getCategoryPostCounts(): array
    {
        $categories = $this->categoryModel->findAll();
        $counts = [];
        foreach ($categories as $category) {
            $counts[$category['id']] = $this->postModel->where('status', 'published')
                ->where('category_id', $category['id'])
                ->countAllResults();
        }
        return $counts;
    }



    public function index(): string
    {
        $data = [
            'title' => 'helouism',
            'posts' => $this->postModel->getHomePosts(),
            'categories' => $this->categoryModel->findAll(),
            'categoryPostCounts' => $this->getCategoryPostCounts(),
            'archive' => $this->postModel->getPostArchive(),
            'pager' => $this->postModel->pager
        ];
        return view('home', $data);
    }
}
