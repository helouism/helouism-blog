<?php

namespace App\Libraries;

use Sabberworm\CSS\Parser;
use Sabberworm\CSS\OutputFormat;
use Sabberworm\CSS\Settings;

class CssOptimizer
{
    private $usedSelectors = [];

    public function optimize($cssContent, $htmlContent)
    {
        // Parse the CSS
        $settings = Settings::create()->beStrict();
        $parser = new Parser($cssContent, $settings);
        $cssDocument = $parser->parse();

        // Extract used selectors from HTML
        $this->extractUsedSelectors($htmlContent);

        // Remove unused rules
        foreach ($cssDocument->getAllDeclarationBlocks() as $block) {
            $keep = false;
            foreach ($block->getSelectors() as $selector) {
                if ($this->isSelectorUsed($selector->getSelector())) {
                    $keep = true;
                    break;
                }
            }
            if (!$keep) {
                $block->remove();
            }
        }

        // Output optimized CSS
        $format = OutputFormat::createCompact();
        return $cssDocument->render($format);
    }

    private function extractUsedSelectors($html)
    {
        // Extract class names
        preg_match_all('/class=["\']([^"\']*)["\']/', $html, $classes);
        foreach ($classes[1] as $class) {
            $classNames = explode(' ', $class);
            foreach ($classNames as $className) {
                if (!empty($className)) {
                    $this->usedSelectors[] = '.' . trim($className);
                }
            }
        }

        // Extract IDs
        preg_match_all('/id=["\']([^"\']*)["\']/', $html, $ids);
        foreach ($ids[1] as $id) {
            if (!empty($id)) {
                $this->usedSelectors[] = '#' . trim($id);
            }
        }
    }

    private function isSelectorUsed($selector)
    {
        // Basic implementation - can be enhanced for complex selectors
        foreach ($this->usedSelectors as $usedSelector) {
            if (strpos($selector, $usedSelector) !== false) {
                return true;
            }
        }
        return false;
    }
}