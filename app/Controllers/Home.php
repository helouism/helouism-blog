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

    private function getPostArchive(): array
    {
        $builder = $this->postModel->select([
            'YEAR(created_at) as year',
            'MONTH(created_at) as month',
            'COUNT(*) as post_count'
        ])->groupBy('year, month')
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->findAll();

        $archive = [];
        foreach ($builder as $row) {
            $year = $row['year'];
            $month = $row['month'];
            if (!isset($archive[$year])) {
                $archive[$year] = [];
            }
            $archive[$year][$month] = $row['post_count'];
        }
        return $archive;
    }

    public function index(): string
    {
        $data = [
            'title' => 'helouism',
            'posts' => $this->postModel->orderBy('created_at', 'DESC')->paginate(5, 'home_posts'),
            'categories' => $this->categoryModel->findAll(),
            'categoryPostCounts' => $this->getCategoryPostCounts(),
            'archive' => $this->getPostArchive(),
            'pager' => $this->postModel->pager
        ];
        return view('home', $data);
    }
}
