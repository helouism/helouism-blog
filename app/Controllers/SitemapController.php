<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PostModel;
use App\Models\CategoryModel;

class SitemapController extends BaseController
{
    protected $cache;
    protected $postModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->categoryModel = new CategoryModel();
        $this->cache = service('cache');
    }

    public function index()
    {
        // Set content type to XML
        $this->response->setContentType('application/xml');

        // Try to get cached sitemap first
        $cacheKey = 'sitemap_xml';
        $cachedSitemap = $this->cache->get($cacheKey);

        if ($cachedSitemap !== null) {
            return $this->response->setBody($cachedSitemap);
        }

        // Get all required data using model methods
        $posts = $this->postModel->getSitemapPosts();
        $categories = $this->categoryModel->getSitemapCategories();
        $archives = $this->postModel->getArchiveUrls();

        // Base URL
        $baseUrl = base_url();

        // Build XML sitemap
        $xml = $this->buildSitemapXml($baseUrl, $posts, $categories, $archives);

        // Cache the sitemap for 1 hour (3600 seconds)
        $this->cache->save($cacheKey, $xml, 3600);

        return $this->response->setBody($xml);
    }

    /**
     * Build the complete sitemap XML
     */
    private function buildSitemapXml(string $baseUrl, array $posts, array $categories, array $archives): string
    {
        // Start building XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";

        // Add homepage
        $xml .= $this->addUrl($baseUrl, date('c'), '1.00');

        // Add static pages
        $staticPages = [
            'terms-and-conditions' => '0.80',
            'privacy-policy' => '0.80',
            'category-list' => '0.80',
            'archive' => '0.80'
        ];

        foreach ($staticPages as $page => $priority) {
            $xml .= $this->addUrl($baseUrl . $page, date('c'), $priority);
        }

        // Add posts
        foreach ($posts as $post) {
            $postUrl = $baseUrl . 'post/' . $post['slug'];
            $lastmod = date('c', strtotime($post['created_at']));
            $xml .= $this->addUrl($postUrl, $lastmod, '0.80');
        }

        // Add categories
        foreach ($categories as $category) {
            $categoryUrl = $baseUrl . 'category/' . $category['slug'];
            $lastmod = isset($category['created_at']) ? date('c', strtotime($category['created_at'])) : date('c');
            $xml .= $this->addUrl($categoryUrl, $lastmod, '0.80');
        }

        // Add archive URLs (year/month)
        foreach ($archives as $archive) {
            $archiveUrl = $baseUrl . 'archive/' . $archive['year'] . '/' . $archive['month'];
            $xml .= $this->addUrl($archiveUrl, date('c'), '0.64');
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Helper method to add URL to sitemap
     */
    private function addUrl(string $loc, string $lastmod, string $priority): string
    {
        $xml = "<url>\n";
        $xml .= "  <loc>" . htmlspecialchars($loc) . "</loc>\n";
        $xml .= "  <lastmod>" . $lastmod . "</lastmod>\n";
        $xml .= "  <priority>" . $priority . "</priority>\n";
        $xml .= "</url>\n";

        return $xml;
    }
}
