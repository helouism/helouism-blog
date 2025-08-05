<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class HtmlMinifier implements FilterInterface
{
    private $preservedBlocks = [];
    private $placeholder = '___PRESERVE_BLOCK_%d___';

    public function before(RequestInterface $request, $arguments = null)
    {
        // Do nothing before
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Only minify HTML responses
        if (strpos($response->getHeaderLine('Content-Type'), 'text/html') !== false) {
            $html = $response->getBody();
            $minified = $this->minifyHtml($html);
            $response->setBody($minified);
        }
    }

    private function minifyHtml($html)
    {
        // Reset preserved blocks for each minification
        $this->preservedBlocks = [];
        
        // Step 1: Preserve content that shouldn't be minified
        $html = $this->preserveBlocks($html);
        
        // Step 2: Minify the remaining HTML
        $search = array(
            '/\n/',                 // replace end of line by a space
            '/\>[^\S ]+/s',        // strip whitespaces after tags, except space
            '/[^\S ]+\</s',        // strip whitespaces before tags, except space
            '/(\s)+/s'             // shorten multiple whitespace sequences
        );

        $replace = array(
            ' ',
            '>',
            '<',
            '\\1'
        );

        $html = preg_replace($search, $replace, $html);
        
        // Step 3: Restore preserved blocks
        $html = $this->restoreBlocks($html);
        
        return $html;
    }

    private function preserveBlocks($html)
    {
        // Preserve <pre> tags and their content (case-insensitive)
        $html = preg_replace_callback(
            '/<pre\b[^>]*>.*?<\/pre>/si',
            [$this, 'storeBlock'],
            $html
        );

        // Preserve <script> tags and their content (case-insensitive)
        $html = preg_replace_callback(
            '/<script\b[^>]*>.*?<\/script>/si',
            [$this, 'storeBlock'],
            $html
        );

        // Preserve <style> tags and their content (case-insensitive)
        $html = preg_replace_callback(
            '/<style\b[^>]*>.*?<\/style>/si',
            [$this, 'storeBlock'],
            $html
        );

        // Preserve <textarea> tags and their content (case-insensitive)
        $html = preg_replace_callback(
            '/<textarea\b[^>]*>.*?<\/textarea>/si',
            [$this, 'storeBlock'],
            $html
        );

        return $html;
    }

    private function storeBlock($matches)
    {
        $index = count($this->preservedBlocks);
        $this->preservedBlocks[$index] = $matches[0];
        return sprintf($this->placeholder, $index);
    }

    private function restoreBlocks($html)
    {
        foreach ($this->preservedBlocks as $index => $block) {
            $placeholder = sprintf($this->placeholder, $index);
            $html = str_replace($placeholder, $block, $html);
        }
        return $html;
    }
}
