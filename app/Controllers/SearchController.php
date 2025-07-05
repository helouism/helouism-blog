<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PostModel;

class SearchController extends BaseController
{
    protected $postModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
    }

    public function index()
    {
        helper('search_helper');
        $validation = $this->validate([
            'search_query' => [
                'label' => 'Search Query',
                'rules' => 'permit_empty|max_length[150]|min_length[1]',
                'errors' => [
                    'max_length' => '{field} cannot exceed {param} characters.',
                    'min_length' => '{field} must be at least {param} character long.'
                ]
            ]
        ]);

        if (!$validation) {
            // Set flashdata for validation errors
            session()->setFlashdata('errors', $this->validator->getErrors());
            // Redirect back to the previous page
            return redirect()->back()->withInput();
        }

        $query = $this->request->getGet('search_query');

        // Sanitize the search query
        $sanitizedQuery = $this->sanitizeSearchQuery(strtolower($query));

        $results = [];
        $totalResults = 0;

        if (!empty($sanitizedQuery)) {
            // Get results with pagination
            $perPage = 10;
            $currentPage = (int) ($this->request->getGet('page') ?? 1);

            // Get total count for pagination
            $totalResults = $this->postModel
                ->like('title', $sanitizedQuery)
                ->orLike('content', $sanitizedQuery)
                ->countAllResults(false); // false keeps the query for reuse

            // Get paginated results
            $results = $this->postModel
                ->like('title', $sanitizedQuery)
                ->orLike('content', $sanitizedQuery)
                ->paginate($perPage, 'default', $currentPage);


        }

        return view('search_results', [
            'query' => $sanitizedQuery,
            'original_query' => $query,
            'title' => 'Search Results',
            'results' => $results,
            'total_results' => $totalResults,
            'pager' => $this->postModel->pager,
        ]);
    }

    /**
     * Sanitize search query to prevent XSS and other attacks
     */
    private function sanitizeSearchQuery($query)
    {
        if (empty($query)) {
            return '';
        }

        // Remove potentially dangerous characters
        $sanitized = preg_replace('/[<>"\'\x00-\x1F\x7F]/', '', $query);

        // Remove excessive whitespace
        $sanitized = preg_replace('/\s+/', ' ', $sanitized);

        // Trim whitespace
        $sanitized = trim($sanitized);
        return $sanitized;
    }
}