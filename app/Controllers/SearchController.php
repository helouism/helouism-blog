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
        $validation = $this->validate([
            'search_query' => [
                'label' => 'Search Query',
                'rules' => 'required|max_length[150]|alpha_numeric_space',
                'errors' => [
                    'required' => 'You must provide a {field}.',
                    'max_length' => '{field} cannot exceed {param} characters.',
                    'alpha_numeric_space' => '{field} can only contain alphanumeric characters and spaces.'
                ]
            ]
        ]);
        if (!$validation) {
            // set flashdata for validation errors
            session()->setFlashdata('errors', $this->validator->getErrors());
            // redirect back to the search page
            return redirect()->back()->withInput();
        }

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
