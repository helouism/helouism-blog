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
    protected $cache;
    
    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->postModel = new PostModel();
        $this->cache = service('cache');
    }
    
   
    // Alternative: More optimized version using fewer queries
    private function getCategoryDetailsOptimized(): array
    {
        $cacheKey = 'category_details_optimized';
        $categoryDetails = $this->cache->get($cacheKey);
        
        if ($categoryDetails === null) {
            $categories = $this->categoryModel->findAll();
            $categoryDetails = [];
            
            // Get all post counts in a single query
            $postCounts = $this->postModel
                ->select('category_id, COUNT(*) as count')
                ->where('status', 'published')
                ->groupBy('category_id')
                ->findAll();
            
            // Convert to associative array for easy lookup
            $countMap = [];
            foreach ($postCounts as $count) {
                $countMap[$count['category_id']] = $count['count'];
            }
            
            // Get latest posts for each category
            $latestPosts = $this->postModel
                ->select('category_id, MAX(created_at) as latest_date')
                ->where('status', 'published')
                ->groupBy('category_id')
                ->findAll();
            
            // Get the actual latest post data
            $latestPostMap = [];
            foreach ($latestPosts as $latest) {
                $post = $this->postModel
                    ->where('status', 'published')
                    ->where('category_id', $latest['category_id'])
                    ->where('created_at', $latest['latest_date'])
                    ->first();
                if ($post) {
                    $latestPostMap[$latest['category_id']] = $post;
                }
            }
            
            // Build the final array
            foreach ($categories as $category) {
                $categoryDetails[$category['name']] = [
                    'postCount' => $countMap[$category['id']] ?? 0,
                    'latestPost' => $latestPostMap[$category['id']] ?? null
                ];
            }
            
            // Cache for 15 minutes
            $this->cache->save($cacheKey, $categoryDetails, 43200);
        }
        
        return $categoryDetails;
    }
    
    public function index()
    {
        // Cache the categories list as well
        $cacheKey = 'all_categories';
        $categories = $this->cache->get($cacheKey);
        
        if ($categories === null) {
            $categories = $this->categoryModel->findAll();
            $this->cache->save($cacheKey, $categories, 43200); // Cache for 30 minutes
        }
        
        $data = [
            'title' => 'All Categories',
            'categories' => $categories,
            'categoryDetails' => $this->getCategoryDetailsOptimized()
        ];
        
        return view('category-list', $data);
    }
}