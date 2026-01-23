#!/bin/bash

# Script to clear Symfony cache on production
# Run this on the production server after deployment

echo "Clearing Symfony cache..."

# Navigate to project directory
cd /home2/scmcsor1/public_html/app_api || exit 1

# Remove cache directories
echo "Removing cache directories..."
rm -rf var/cache/prod/*
rm -rf var/cache/dev/*

# Warm up cache
echo "Warming up production cache..."
php bin/console cache:warmup --env=prod --no-debug

echo "Cache cleared and warmed up successfully!"

