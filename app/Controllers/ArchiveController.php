<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PostModel;
use App\Models\CategoryModel;

class ArchiveController extends BaseController
{
    protected $postModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->categoryModel = new CategoryModel();
    }

    /**
     * Display posts for a specific year and month
     */
    public function show($year, $month)
    {
        // Validate year and month
        if (!is_numeric($year) || !is_numeric($month) || $month < 1 || $month > 12) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid archive date');
        }

        // Get posts for the specific year and month
        $posts = $this->postModel->getPostByYearAndMonth($year, $month);


        // Get month name for display
        $monthName = date('F', mktime(0, 0, 0, $month, 10));

        // Prepare data for view
        $data = [
            'title' => "Archive - $monthName $year",
            'posts' => $posts,
            'year' => $year,
            'month' => $month,
            'monthName' => $monthName,
            'pager' => $this->postModel->pager,
            'categories' => $this->categoryModel->findAll(),

            'archive' => $this->postModel->getPostArchive()
        ];

        return view('archive/posts', $data);
    }

    /**
     * Display archive overview (all years and months)
     */
    public function index()
    {
        $data = [
            'title' => 'Archive',
            'archive' => $this->postModel->getPostArchive(),
            'pager' => $this->postModel->pager
        ];

        return view('archive/index', $data);
    }
}