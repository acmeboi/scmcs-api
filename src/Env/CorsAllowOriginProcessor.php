<?php

namespace App\Env;

use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;

class CorsAllowOriginProcessor implements EnvVarProcessorInterface
{
    public function getEnv(string $prefix, string $name, \Closure $getEnv): mixed
    {
        $env = $getEnv($name);
        
        // If empty or null, return empty array
        if (empty($env) && $env !== '0') {
            return [];
        }
        
        // If it's already an array, return it
        if (is_array($env)) {
            return $env;
        }
        
        // If it's a string, split by comma and trim each value
        if (is_string($env)) {
            $values = array_map('trim', explode(',', $env));
            return array_filter($values, fn($v) => $v !== ''); // Remove empty values
        }
        
        // Fallback: return as single-item array
        return [$env];
    }

    public static function getProvidedTypes(): array
    {
        return [
            'cors_origin' => 'array',
        ];
    }
}

