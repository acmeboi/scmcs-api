#!/bin/bash

# Script to find where parameter "216" is referenced
# Run this on production to identify the source of the ParameterNotFoundException

echo "Searching for parameter '216' references..."
echo "============================================"
echo ""

# Search in PHP files for ApiResource with description containing % and 216
echo "1. Checking ApiResource attributes with descriptions..."
find src -name "*.php" -exec grep -l "ApiResource" {} \; | while read file; do
    if grep -A 30 "#\[ApiResource" "$file" 2>/dev/null | grep -qE "description.*%|description.*216|%.*216|216.*%"; then
        echo "   FOUND in: $file"
        grep -A 30 "#\[ApiResource" "$file" | grep -E "description|%|216" -B 2 -A 2
        echo ""
    fi
done

# Search for %216% in all files
echo "2. Searching for %216% pattern..."
if grep -r "%216%" src/ config/ 2>/dev/null; then
    echo "   Found %216% references above"
else
    echo "   No %216% found"
fi
echo ""

# Search for description: with % or 216
echo "3. Searching for 'description:' containing % or 216..."
if grep -r "description:" src/ config/ 2>/dev/null | grep -E "%|216"; then
    echo "   Found description with % or 216 above"
else
    echo "   No description with % or 216 found"
fi
echo ""

# Check cached route files
echo "4. Checking cached route files..."
if [ -d "var/cache" ]; then
    if find var/cache -name "*.php" -exec grep -l "216" {} \; 2>/dev/null | head -5; then
        echo "   Found '216' in cached files above"
    else
        echo "   No '216' found in cached files"
    fi
else
    echo "   Cache directory not found"
fi
echo ""

# Check for parameters.yml files
echo "5. Checking for parameters.yml files..."
if [ -f "config/parameters.yml" ]; then
    echo "   Found config/parameters.yml"
    if grep -q "216" config/parameters.yml; then
        echo "   ⚠️  Contains '216':"
        grep -n "216" config/parameters.yml
    fi
else
    echo "   No config/parameters.yml found"
fi

if [ -f "config/parameters.yml.dist" ]; then
    echo "   Found config/parameters.yml.dist"
    if grep -q "216" config/parameters.yml.dist; then
        echo "   ⚠️  Contains '216':"
        grep -n "216" config/parameters.yml.dist
    fi
else
    echo "   No config/parameters.yml.dist found"
fi
echo ""

# Check all YAML files for parameter references
echo "6. Checking all YAML files for %216%..."
if grep -r "%216%" config/ 2>/dev/null; then
    echo "   Found %216% in config files above"
else
    echo "   No %216% found in config files"
fi
echo ""

echo "============================================"
echo "Search complete!"

