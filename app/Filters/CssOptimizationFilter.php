<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Libraries\CssOptimizer;

class CssOptimizationFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $body = $response->getBody();

        if (strpos($response->getHeaderLine('Content-Type'), 'text/html') !== false) {
            // Extract CSS content from style tags and linked stylesheets
            preg_match_all('/<style[^>]*>(.*?)<\/style>/s', $body, $styles);
            $cssContent = implode("\n", $styles[1]);

            // Optimize CSS
            $optimizer = new CssOptimizer();
            $optimizedCss = $optimizer->optimize($cssContent, $body);

            // Replace original CSS with optimized version
            $body = preg_replace('/<style[^>]*>(.*?)<\/style>/s', '<style>' . $optimizedCss . '</style>', $body);

            $response->setBody($body);
        }

        return $response;
    }
}