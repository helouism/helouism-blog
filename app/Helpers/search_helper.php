<?php

/**
 * Helper function to safely highlight search terms
 */
function highlightSearchTerms($text, $query)
{
    if (empty($query) || empty($text)) {
        return $text;
    }

    // Split query into individual terms
    $terms = explode(' ', trim($query));

    foreach ($terms as $term) {
        if (strlen($term) > 2) { // Only highlight terms longer than 2 characters
            $pattern = '/(' . preg_quote($term, '/') . ')/i';
            $text = preg_replace($pattern, '<mark>$1</mark>', $text);
        }
    }

    return $text;
}

/**
 * Helper function to truncate text
 */
function truncateText($text, $limit = 200)
{
    if (strlen($text) <= $limit) {
        return $text;
    }

    return substr($text, 0, $limit) . '...';
}