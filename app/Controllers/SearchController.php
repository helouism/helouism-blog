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

        $query = $this->request->getGet('search_query');


        $results = [];
        if ($query) {
            $results = $this->postModel
                ->like('title', $query)
                ->orLike('content', $query)
                ->findAll();
        }

        return view('search_results', [
            'query' => $query,
            'title' => 'Search Results',
            'results' => $results,
        ]);
    }
}
