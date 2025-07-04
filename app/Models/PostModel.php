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
    protected $allowedFields = ['title', 'slug', 'meta_description', 'thumbnail_caption', 'thumbnail_path', 'content', 'username', 'category_name'];

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
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];


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


}
