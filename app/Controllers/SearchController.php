<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PostModel;

class SearchController extends BaseController
{
    protected $postModel;
    protected $cache;

    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->cache = \Config\Services::cache();
    }

    public function index()
    {

        helper('search_helper');
        $validation = $this->validate([
            'q' => [
                'label' => 'Search Query',
                'rules' => 'string|permit_empty|max_length[150]|min_length[1]',
                'errors' => [
                    'string' => '{field} must be a valid string.',
                    'max_length' => '{field} cannot exceed {param} characters.',
                    'min_length' => '{field} must be at least {param} character long.'
                ]
            ]
        ]);

        if (!$validation) {
            session()->setFlashdata('search_errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }

        $query = $this->request->getGet('q');
        $sanitized_query = sanitizeSearchQuery(strtolower($query));

        $search_results = [];
        $total_results = 0;

        if (!empty($sanitized_query)) {

            // cache results
            $cacheKey = $this->generateCacheKey($sanitized_query);
            $cachedData = $this->cache->get($cacheKey);

            if ($cachedData !== null) {
                // Use cached data
                $total_results = $cachedData['total_results'];
                $search_results = $cachedData['search_results'];

            } else {
                // Get fresh data from database
                $total_results = $this->postModel->getTotalSearchResults($sanitized_query);
                $search_results = $this->postModel->getSearchResults($sanitized_query);

                // Cache the results for 15 minutes
                $this->cache->save($cacheKey, [
                    'total_results' => $total_results,
                    'search_results' => $search_results,
                ], 900); // 15 minutes
            }
        }

        $data = [
            'query' => $sanitized_query,
            'original_query' => $query,
            'title' => 'Search Results',
            'search_results' => $search_results,
            'total_results' => $total_results
        ];
        return view('search_results', $data);
    }


    /**
     * Generate a unique cache key for the search
     */
    private function generateCacheKey($query)
    {
        return 'search_' . md5($query);
    }
}