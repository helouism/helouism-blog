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
        foreach ($cssDocument->getAllRuleSets() as $ruleSet) {
            $selectors = $ruleSet->getSelectors();
            $used = false;
            foreach ($selectors as $selectorObj) {
                $selector = (string) $selectorObj;
                if ($this->isSelectorUsed($selector)) {
                    $used = true;
                    break;
                }
            }
            if (!$used) {
                $cssDocument->remove($ruleSet);
            }
        }

        // Optionally, scale sizes (if you still want this feature)
        foreach ($cssDocument->getAllValues() as $value) {
            if ($value instanceof CSSSize && !$value->isRelative()) {
                $value->setSize($value->getSize() / 2);
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