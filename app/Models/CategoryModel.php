<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['name', 'slug'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
    ];
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
    protected $afterInsert = ['clearCache'];
    protected $beforeUpdate = [];
    protected $afterUpdate = ['clearCache'];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = ['clearCache'];

    public function setSlug($name)
    {
        $builder = $this->table($this->table);
        $url = strip_tags($name); #hilangkan tag html
        $url = preg_replace('/[^A-Za-z0-9]/', " ", $url);
        $url = trim($url);
        $url = preg_replace('/[^A-Za-z0-9]/', "-", $url);
        $url = strtolower($url);

        $builder->where('name', $name);
        $jumlah = $builder->countAllResults();
        if ($jumlah > 0) {
            $jumlah = $jumlah + 1;
            return $url . "-" . $jumlah;
        }
        return $url;
    }

    // Get Category id from name
    public function getIdFromName($category_name): string
    {
        $category = $this->where('name', $category_name)
            ->first();
        return $category['id'];
    }

    public function getNameFromId($category_id): string
    {
        $category = $this->where('id', $category_id)
            ->first();
        return $category['name'];
    }

    public function getCategoryPostCounts(): array
    {
        $builder = $this->db->table($this->table . ' c');
        $builder->select('c.id, COUNT(p.id) as post_count');
        $builder->join('posts p', 'p.category_id = c.id AND p.status = "published"', 'left');
        $builder->groupBy('c.id');

        $results = $builder->get()->getResultArray();

        $counts = [];
        foreach ($results as $result) {
            $counts[$result['id']] = (int) $result['post_count'];
        }

        return $counts;
    }

    // New methods for sitemap functionality
    
    /**
     * Get categories data for sitemap generation
     * Only selects necessary fields to reduce memory usage
     */
    public function getSitemapCategories(): array
    {
        $cacheKey = 'sitemap_categories';
        $cache = service('cache');
        
        $categories = $cache->get($cacheKey);
        if ($categories === null) {
            $categories = $this->select('slug, created_at')
                ->orderBy('created_at', 'DESC')
                ->findAll();
            
            // Cache for 30 minutes
            $cache->save($cacheKey, $categories, 1800);
        }
        
        return $categories;
    }
    
    /**
     * Get the latest category creation date for sitemap lastmod
     * This helps with sitemap cache invalidation
     */
    public function getLatestCategoryDate(): string
    {
        $result = $this->select('MAX(created_at) as latest_date')
            ->first();
            
        return $result['latest_date'] ?? date('Y-m-d H:i:s');
    }

    protected function clearCache(array $data)
    {
        $cache = service('cache');
        
        // Clear all cache including sitemap cache when categories are modified
        $cache->delete('sitemap_xml');
        $cache->delete('sitemap_categories');
        
        // Clear general cache
        $cache->clean();
    }
}