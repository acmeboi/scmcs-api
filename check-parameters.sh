#!/bin/bash

# Script to check for generated parameter files and validate configuration
# Run this on production after composer install to ensure no invalid parameters exist

echo "Checking for parameter files..."
echo "================================"

# Check for legacy parameters.yml files
if [ -f "config/parameters.yml" ]; then
    echo "⚠️  Found config/parameters.yml"
    echo "Checking for invalid parameter references..."
    if grep -q "%216%" config/parameters.yml 2>/dev/null; then
        echo "❌ ERROR: Found %216% in parameters.yml"
        grep -n "%216%" config/parameters.yml
        exit 1
    else
        echo "✓ No %216% found in parameters.yml"
    fi
else
    echo "✓ No config/parameters.yml found (expected for Symfony 7.3+)"
fi

# Check for parameters.yml.dist
if [ -f "config/parameters.yml.dist" ]; then
    echo "⚠️  Found config/parameters.yml.dist"
    if grep -q "%216%" config/parameters.yml.dist 2>/dev/null; then
        echo "❌ ERROR: Found %216% in parameters.yml.dist"
        grep -n "%216%" config/parameters.yml.dist
        exit 1
    else
        echo "✓ No %216% found in parameters.yml.dist"
    fi
fi

# Check all YAML files in config for %216%
echo ""
echo "Checking all config YAML files for %216%..."
if grep -r "%216%" config/*.yaml config/**/*.yaml 2>/dev/null; then
    echo "❌ ERROR: Found %216% in config files"
    exit 1
else
    echo "✓ No %216% found in config YAML files"
fi

# Check services.yaml parameters section
echo ""
echo "Checking services.yaml parameters..."
if grep -A 20 "^parameters:" config/services.yaml | grep -q "216"; then
    echo "⚠️  Found '216' in services.yaml parameters section"
    grep -A 20 "^parameters:" config/services.yaml | grep -n "216"
else
    echo "✓ No '216' found in services.yaml parameters"
fi

# Check for any parameter references that might be invalid
echo ""
echo "Checking for numeric parameter references..."
if grep -rE "%[0-9]+%" config/ 2>/dev/null | grep -v "%kernel\|%env\|%public_dir%"; then
    echo "⚠️  Found numeric parameter references (may be invalid):"
    grep -rnE "%[0-9]+%" config/ 2>/dev/null | grep -v "%kernel\|%env\|%public_dir%"
else
    echo "✓ No suspicious numeric parameter references found"
fi

echo ""
echo "================================"
echo "Parameter check complete!"
echo ""
echo "If errors are found, check:"
echo "1. config/parameters.yml (if exists)"
echo "2. config/parameters.yml.dist (if exists)"
echo "3. config/services.yaml"
echo "4. Any generated files from composer install"

