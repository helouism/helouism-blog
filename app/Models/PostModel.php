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

    public function getHomePosts()
    {
        $posts = $this->where('status', 'published')
            ->orderBy('created_at', 'DESC')->paginate(5, 'home_posts');

        return $posts;
    }

    public function getPostItem($slug)
    {
        $postItem = $this->where('status', 'published')
            ->select('posts.*, categories.slug as category_slug, categories.name as category_name')
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
        $builder = $this->where('status', 'published')
            ->select([
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
        $posts = $this->where('status', 'published')
            ->select('posts.*, categories.name as category_name, categories.slug as category_slug')
            ->join('categories', 'categories.id = posts.category_id', 'left') // LEFT JOIN to handle missing categories
            ->where('YEAR(posts.created_at)', $year)
            ->where('MONTH(posts.created_at)', $month)
            ->orderBy('posts.created_at', 'DESC')
            ->paginate(10, 'archive_posts');
        return $posts;
    }

    public function getPostByCategory($category_id)
    {
        $posts = $this->where('status', 'published')
            ->select('posts.*, categories.name as category_name, categories.slug as category_slug')
            ->join('categories', 'categories.id = posts.category_id', 'left')
            ->where('posts.category_id', $category_id)
            ->orderBy('posts.created_at', 'DESC')
            ->paginate(6, 'category_posts');
        return $posts;
    }

    public function getTotalSearchResults(string $sanitized_query)
    {
        $totalSearchResults = $this->where('status', 'published')
            ->like('title', $sanitized_query)
            ->orLike('content', $sanitized_query)
            ->countAllResults(false);

        return $totalSearchResults;
    }

    public function getSearchResults(string $sanitized_query)
    {
        $searchResults = $this->where('status', 'published')
            ->select('posts.*, categories.name as category_name, categories.slug as category_slug')
            ->join('categories', 'categories.id = posts.category_id', 'left')
            ->like('posts.title', $sanitized_query)
            ->orLike('posts.content', $sanitized_query)
            ->findAll();

        return $searchResults;
    }

    // New methods for sitemap functionality

    /**
     * Get posts data for sitemap generation
     * Only selects necessary fields to reduce memory usage
     */
    public function getSitemapPosts(): array
    {
        $cacheKey = 'sitemap_posts';
        $cache = service('cache');

        $posts = $cache->get($cacheKey);
        if ($posts === null) {
            $posts = $this->select('slug, created_at')
                ->where('status', 'published')
                ->orderBy('created_at', 'DESC')
                ->findAll();

            // Cache for 30 minutes
            $cache->save($cacheKey, $posts, 1800);
        }

        return $posts;
    }

    /**
     * Get unique year-month combinations from posts for archive URLs
     * This replaces the direct database query in the controller
     */
    public function getArchiveUrls(): array
    {
        $cacheKey = 'sitemap_archives';
        $cache = service('cache');

        $archives = $cache->get($cacheKey);
        if ($archives === null) {
            $builder = $this->builder();
            $archives = $builder->select('YEAR(created_at) as year, MONTH(created_at) as month')
                ->where('status', 'published')
                ->groupBy('YEAR(created_at), MONTH(created_at)')
                ->orderBy('year', 'DESC')
                ->orderBy('month', 'DESC')
                ->get()
                ->getResultArray();

            // Cache for 30 minutes
            $cache->save($cacheKey, $archives, 1800);
        }

        return $archives;
    }

    /**
     * Get the latest post creation date for sitemap lastmod
     * This helps with sitemap cache invalidation
     */
    public function getLatestPostDate(): string
    {
        $result = $this->select('MAX(created_at) as latest_date')
            ->where('status', 'published')
            ->first();

        return $result['latest_date'] ?? date('Y-m-d H:i:s');
    }

    protected function clearSearchCache(array $data)
    {
        $cache = service('cache');

        // Clear all cache including sitemap cache when posts are modified
        $cache->delete('sitemap_xml');
        $cache->delete('sitemap_posts');
        $cache->delete('sitemap_archives');
        $cache->delete('sitemap_categories');

        // Clear general cache
        $cache->clean();
    }
}
