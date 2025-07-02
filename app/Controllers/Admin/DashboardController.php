<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CategoryModel;
use App\Models\PostModel;

class DashboardController extends BaseController
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
        $post_count = $this->postModel->countAllResults();
        $category_count = $this->categoryModel->countAllResults();

        $data = [
            'title' => 'Admin Dashboard',
            'total_categories' => $category_count,
            'total_posts' => $post_count,
        ];
        return view('admin/index', $data);
    }
}