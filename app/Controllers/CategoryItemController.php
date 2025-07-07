<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PostModel;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class CategoryItemController extends BaseController
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
        $category = $this->categoryModel->where('slug', $slug)->first();

        if (!$category) {
            return redirect()->to('/')->with('error', 'Category not found');
        }

        // Get paginated posts for this category
        $posts = $this->postModel->getPostByCategory($category['id']);


        $data = [
            'title' => "Posts in {$category['name']}",
            'category' => $category,
            'page' => 'Category Item',
            'posts' => $posts,
            'pager' => $this->postModel->pager
        ];

        return view('category/show', $data);
    }
}
