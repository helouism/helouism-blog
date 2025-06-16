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
            $counts[$category['name']] = $this->postModel->where('category_name', $category['name'])->countAllResults();
        }
        return $counts;
    }

    public function index(): string
    {
        $data = [
            'title' => 'helouism',
            'posts' => $this->postModel->orderBy('created_at', 'DESC')->paginate(5, 'home_posts'),
            'categories' => $this->categoryModel->findAll(),
            'categoryPostCounts' => $this->getCategoryPostCounts(),
            'pager' => $this->postModel->pager
        ];
        return view('home', $data);
    }
}
