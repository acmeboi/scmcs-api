<?php

namespace App\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CorsAllowOriginCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('cors.allow_origin.resolved')) {
            return;
        }
        
        // Get the raw env var value
        $envValue = $_ENV['CORS_ALLOW_ORIGIN'] ?? $_SERVER['CORS_ALLOW_ORIGIN'] ?? getenv('CORS_ALLOW_ORIGIN') ?: null;
        
        // If env var is set, use it; otherwise keep the default
        if (!empty($envValue)) {
            $value = array_filter(array_map('trim', explode(',', $envValue)));
            if (!empty($value)) {
                $container->setParameter('cors.allow_origin.resolved', array_values($value));
            }
        }
    }
}

