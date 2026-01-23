# Deployment Instructions

## Post-Deployment Steps

After deploying to production, **you must clear the Symfony cache** to avoid route loading errors.

### Quick Fix (Recommended)

SSH into your production server and run:

```bash
cd /home2/scmcsor1/public_html/app_api
rm -rf var/cache/prod/*
php bin/console cache:warmup --env=prod --no-debug
```

### Alternative: Via FTP/cPanel

1. Navigate to: `/home2/scmcsor1/public_html/app_api/var/cache/prod/`
2. Delete all files and folders inside this directory
3. The cache will regenerate automatically on the next request (may be slow on first request)

## Common Errors After Deployment

### ParameterNotFoundException: "You have requested a non-existent parameter '216'"

This error occurs when API Platform tries to load routes with stale cached metadata. **Solution:** Clear the cache using the commands above.

### Why This Happens

- API Platform caches route metadata during application initialization
- When routes or configurations change, cached metadata becomes stale
- Symfony's route loader tries to resolve parameters that no longer exist
- Clearing the cache forces regeneration of route metadata

## Prevention

To avoid this in future deployments, consider:

1. **Automated cache clearing** - Add cache clearing to your deployment script
2. **Deployment automation** - Use CI/CD tools that can run commands on the server
3. **Cache warming** - Always warm up the cache after clearing it

