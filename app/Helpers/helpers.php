<?php

if (!function_exists('str_limit')) {
    /**
     * Limit the number of characters in a string.
     * This is a fallback for Str::limit() when mbstring extension is not available.
     *
     * @param string $value
     * @param int $limit
     * @param string $end
     * @return string
     */
    function str_limit($value, $limit = 100, $end = '...')
    {
        if (strlen($value) <= $limit) {
            return $value;
        }
        
        return substr($value, 0, $limit) . $end;
    }
}
