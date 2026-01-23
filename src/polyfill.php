<?php

/**
 * Polyfill for PHP 8.4 array_any() function
 * 
 * This function checks if any element in the array satisfies the callback condition.
 * 
 * @param array $array The array to check
 * @param callable $callback The callback function to apply
 * @return bool True if any element satisfies the condition, false otherwise
 */
if (!function_exists('array_any')) {
    function array_any(array $array, callable $callback): bool
    {
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return true;
            }
        }
        return false;
    }
}

