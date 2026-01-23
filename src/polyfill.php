<?php

/**
 * PHP 8.4 Compatibility Polyfills
 * 
 * This file provides polyfills for PHP 8.4 functions to ensure compatibility
 * with PHP 8.2+. These functions are automatically loaded via Composer autoload.
 * 
 * @see https://www.php.net/manual/en/migration84.new-functions.php
 */

if (!function_exists('array_any')) {
    /**
     * Checks if any element in the array satisfies the callback condition.
     * 
     * This is a polyfill for PHP 8.4's array_any() function.
     * 
     * @param array $array The array to check
     * @param callable $callback The callback function to apply to each element
     * @return bool True if any element satisfies the condition, false otherwise
     * 
     * @see https://www.php.net/manual/en/function.array-any.php
     */
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

