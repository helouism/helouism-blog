<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['title', 'slug', 'meta_description', 'thumbnail_path', 'thumbnail_caption', 'content', 'username', 'category_id', 'status'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = ['clearSearchCache'];
    protected $beforeUpdate = [];
    protected $afterUpdate = ['clearSearchCache'];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = ['clearSearchCache'];

    public function getPostItem($slug)
    {
        $postItem = $this->select('posts.*, categories.slug as category_slug, categories.name as category_name')
            ->join('categories', 'categories.id = posts.category_id', 'left') // Changed to LEFT JOIN
            ->where('posts.slug', $slug)
            ->first();
        return $postItem;
    }

    public function setSlug($title)
    {
        $builder = $this->table($this->table);
        $url = strip_tags($title); #hilangkan tag html
        $url = preg_replace('/[^A-Za-z0-9]/', " ", $url);
        $url = trim($url);
        $url = preg_replace('/[^A-Za-z0-9]/', "-", $url);
        $url = strtolower($url);

        $builder->where('title', $title);
        $jumlah = $builder->countAllResults();
        if ($jumlah > 0) {
            $jumlah = $jumlah + 1;
            return $url . "-" . $jumlah;
        }
        return $url;
    }

    /**
     * Get the post archive grouped by year and month.
     *
     * @return array
     */
    public function getPostArchive(): array
    {
        $builder = $this->select([
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

    public function getPostByYearAndMonth($year, $month)
    {
        $posts = $this->select('posts.*, categories.name as category_name, categories.slug as category_slug')
            ->join('categories', 'categories.id = posts.category_id', 'left') // LEFT JOIN to handle missing categories
            ->where('YEAR(posts.created_at)', $year)
            ->where('MONTH(posts.created_at)', $month)
            ->orderBy('posts.created_at', 'DESC')
            ->paginate(10, 'archive_posts');
        return $posts;
    }

    public function getPostByCategory($category_id)
    {
        $posts = $this->select('posts.*, categories.name as category_name, categories.slug as category_slug')
            ->join('categories', 'categories.id = posts.category_id', 'left')
            ->where('posts.category_id', $category_id)
            ->orderBy('posts.created_at', 'DESC')
            ->paginate(6, 'category_posts');
        return $posts;
    }

    public function getTotalSearchResults(string $sanitized_query)
    {
        $totalSearchResults = $this->like('title', $sanitized_query)
            ->orLike('content', $sanitized_query)
            ->countAllResults(false);

        return $totalSearchResults;
    }

    public function getSearchResults(string $sanitized_query)
    {
        $searchResults = $this->select('posts.*, categories.name as category_name, categories.slug as category_slug')
            ->join('categories', 'categories.id = posts.category_id', 'left')
            ->like('posts.title', $sanitized_query)
            ->orLike('posts.content', $sanitized_query)
            ->findAll();

        return $searchResults;
    }

    protected function clearSearchCache(array $data)
    {
        $cache = \Config\Services::cache();
        $cache->clean();
    }
}