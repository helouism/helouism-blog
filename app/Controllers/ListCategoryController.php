<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\PostModel;
use CodeIgniter\HTTP\ResponseInterface;

class ListCategoryController extends BaseController
{
    protected $categoryModel;
    protected $postModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->postModel = new PostModel();
    }

    private function getCategoryDetails(): array
    {
        $categories = $this->categoryModel->findAll();
        $categoryDetails = [];

        foreach ($categories as $category) {
            $categoryDetails[$category['name']] = [
                'postCount' => $this->postModel->where('category_name', $category['name'])->countAllResults(),
                'latestPost' => $this->postModel->where('category_name', $category['name'])
                    ->orderBy('created_at', 'DESC')
                    ->first()
            ];
        }

        return $categoryDetails;
    }

    public function index()
    {
        $data = [
            'title' => 'All Categories',
            'categories' => $this->categoryModel->findAll(),
            'categoryDetails' => $this->getCategoryDetails()
        ];
        return view('category-list', $data);
    }
}
