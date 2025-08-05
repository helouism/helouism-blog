<?php
namespace App\Controllers;
use App\Models\PostModel;
use App\Models\CategoryModel;

class Home extends BaseController
{
    protected $postModel;
    protected $categoryModel;
    protected $cache;

    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->categoryModel = new CategoryModel();
        $this->cache = service('cache');

    }

    public function index(): string
    {
        // Cache keys
        $cacheKeys = [
            'categories' => 'home_categories',
            'categoryPostCounts' => 'category_post_counts',
            'archive' => 'post_archive'
        ];

        // Cache duration in seconds (5 Days)
        $cacheDuration = 432000;

        // Posts cannot be cached with pager, so always fetch fresh
        $posts = $this->postModel->getHomePosts();

        // Try to get cached data for other components
        $cachedData = [];
        foreach ($cacheKeys as $key => $cacheKey) {
            $cachedData[$key] = $this->cache->get($cacheKey);
        }

        // Get fresh data for any missing cache entries
        $data = [
            'title' => 'Helouism | Open Source, Programming, Tutorials & Networking Insights',
            'posts' => $posts,
            'categories' => $cachedData['categories'] ?? $this->getCachedCategories($cacheKeys['categories'], $cacheDuration),
            'categoryPostCounts' => $cachedData['categoryPostCounts'] ?? $this->getCachedCategoryPostCounts($cacheKeys['categoryPostCounts'], $cacheDuration),
            'archive' => $cachedData['archive'] ?? $this->getCachedArchive($cacheKeys['archive'], $cacheDuration),
            'pager' => $this->postModel->pager
        ];

        return view('home', $data);
    }

    // Note: Posts are not cached because they need the pager object

    protected function getCachedCategories(string $cacheKey, int $duration)
    {
        $categories = $this->cache->get($cacheKey);
        if ($categories === null) {
            $categories = $this->categoryModel->findAll();
            $this->cache->save($cacheKey, $categories, $duration);
        }
        return $categories;
    }

    protected function getCachedCategoryPostCounts(string $cacheKey, int $duration)
    {
        $counts = $this->cache->get($cacheKey);
        if ($counts === null) {
            $counts = $this->categoryModel->getCategoryPostCounts();
            $this->cache->save($cacheKey, $counts, $duration);
        }
        return $counts;
    }

    protected function getCachedArchive(string $cacheKey, int $duration)
    {
        $archive = $this->cache->get($cacheKey);
        if ($archive === null) {
            $archive = $this->postModel->getPostArchive();
            $this->cache->save($cacheKey, $archive, $duration);
        }
        return $archive;
    }

    public function privacy(): string
    {
        $data = [
            'title' => 'Privacy Policy'
        ];
        return view('privacy-policy', $data);
    }
    
     public function terms(): string
    {
        $data = [
            'title' => 'Terms and Conditions'
        ];
        return view('terms-and-conditions', $data);
    }


}