<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\CategoryModel;
use App\Models\PostModel;

class PostItemController extends BaseController
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

    public function show($slug)
    {
        // Generate cache keys
        $postCacheKey = 'post_item_' . $slug;
        $categoryCacheKey = 'category_name_';
        
        // Try to get post from cache first
        $post = $this->cache->get($postCacheKey);
        
        if ($post === null) {
            // Post not in cache, get from database
            $post = $this->postModel->getPostItem($slug);
            
            if (!$post) {
                return redirect()->to('/')->with('error', 'Post not found');
            }
            
            // Cache the post for 1 hour (3600 seconds)
            $this->cache->save($postCacheKey, $post, 432000);
        }
        
        // If post wasn't found in cache and doesn't exist in database
        if (!$post) {
            return redirect()->to('/')->with('error', 'Post not found');
        }
        
        // Generate category cache key with the actual category_id
        $categoryCacheKey .= $post['category_id'];
        
        // Try to get category name from cache
        $category_name = $this->cache->get($categoryCacheKey);
        
        if ($category_name === null) {
            // Category name not in cache, get from database
            $category_name = $this->categoryModel->getNameFromId($post['category_id']);
            
            // Cache the category name for 2 hours (7200 seconds)
            $this->cache->save($categoryCacheKey, $category_name, 432000);
        }

        $data = [
            'title' => $post['title'],
            'post' => $post,
            'category_name' => $category_name
        ];
        
        return view('post/show', $data);
    }
}