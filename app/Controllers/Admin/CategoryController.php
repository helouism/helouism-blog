<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class CategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }



    public function index()
    {
        $pager = \Config\Services::pager();

        $data = [
            'title' => 'Manage Categories',
            'categories' => $this->categoryModel->paginate(10, 'category'),
            'pager' => $this->categoryModel->pager
        ];

        return view('admin/categories/index', $data);


    }

    /**
     * create function
     */
    public function create()
    {
        helper('form');
        $data = [
            'title' => 'Create Category'
        ];
        return view('admin/categories/create', $data);
    }


    /**
     * store function
     */
    public function store()
    {
        helper('form');

        $validation = $this->validate([
            'name' => [
                'rules' => 'required|max_length[150]|is_unique[categories.name]|alpha_space',
                'errors' => [
                    'required' => 'Category name required.',
                    'is_unique' => 'Category name already exists',
                    'max_length' => 'Category name too long',
                    'alpha_space' => 'Category name should be a text'
                ]
            ],
        ]);
        if (!$validation) {
            session()->setFlashdata('error', $this->validator->listErrors());

            return redirect()->to(base_url('admin/categories/create'));

        } else {
            //insert data into database
            $this->categoryModel->insert([
                'name' => $this->request->getPost('name'),
                'slug' => $this->categoryModel->setSlug($this->request->getPost('name')),

            ]);

            //flash message
            session()->setFlashdata('success', 'New category added');

            return redirect()->to(base_url('admin/categories'));
        }


    }

    /**
     * edit function
     */
    public function edit($id)
    {
        helper('form');

        $data = array(
            'title' => 'Edit Category',
            'category' => $this->categoryModel->find($id)
        );

        return view('admin/categories/edit', $data);
    }
    public function update($id)
    {
        //load helper form and URL
        helper('form');

        //define validation
        $validation = $this->validate([
            'name' => [
                'rules' => 'required|max_length[150]|string|is_unique[categories.name]',
                'errors' => [
                    'required' => 'Category name required.',
                    'is_unique' => 'Category name already exists',
                    'max_length' => 'Category name too long',
                    'string' => 'Category name should be a text'
                ]
            ],
        ]);

        if (!$validation) {

            //    Set error message
            session()->setFlashdata('error', $this->validator->listErrors());

            //render view with error validation message
            return redirect()->to(base_url('admin/categories/edit/') . $id);

        } else {
            //insert data into database
            $name = $this->request->getPost('name');
            $this->categoryModel->update($id, [
                'name' => $name,
                'slug' => $this->categoryModel->setSlug($this->request->getPost('name')),
            ]);

            //flash message
            session()->setFlashdata('success', 'Category updated');

            return redirect()->to(base_url('admin/categories'));
        }

    }

    public function delete($id)
    {
        //model initialize
        helper(['url']);

        $category = $this->categoryModel->find($id);

        if ($category) {
            $this->categoryModel->delete($id);

            //flash message
            session()->setFlashdata('success', 'Category Deleted');

            return redirect()->to(base_url('admin/categories'));
        }
    }
}
