# Fix for `user_id` Cannot Be Null Error - Complete Solution

## Problem
Production error when logging in:
```
SQLSTATE[23000]: Integrity constraint violation: 1048 Column 'user_id' cannot be null
```

## Root Cause
1. **Database constraint**: `refresh_tokens.user_id` is `INT NOT NULL`
2. **JWT Refresh Token Bundle**: Creates refresh tokens but doesn't automatically set the `user` relationship
3. **Entity mismatch**: Entity had `nullable: true` but database requires `nullable: false`

## Solution Implemented

### 1. Fixed Entity Definition
**File**: `src/Entity/RefreshToken.php`
- Changed `nullable: true` → `nullable: false` to match database schema
- Ensures Doctrine validates the constraint at entity level

### 2. Added User Relationship
**File**: `src/Entity/User.php`
- Added `OneToMany` relationship for `refreshTokens`
- Completes bidirectional relationship

### 3. Created Event Listener (Primary Solution)
**File**: `src/EventListener/RefreshTokenCreatedListener.php`
- Listens to: `gesdinet_jwt_refresh_token.on_refresh_token_created`
- **How it works**:
  1. Checks if user is already set (skip if yes)
  2. Tries to get user from `TokenStorageInterface` (if available during login)
  3. Falls back to finding user by username (email) from refresh token
  4. Sets the user relationship before persistence

### 4. Created Doctrine PrePersist Subscriber (Fallback)
**File**: `src/EventListener/RefreshTokenPrePersistSubscriber.php`
- Listens to: Doctrine `prePersist` event
- **How it works**:
  1. Intercepts refresh token before database insert
  2. If user is not set, finds user by username (email)
  3. Sets user relationship
- **Why needed**: Ensures user is set even if bundle event doesn't fire in time

## How It Works Together

1. **During Login**:
   - User authenticates via `/api/login`
   - JWT Refresh Token bundle creates refresh token
   - `RefreshTokenCreatedListener` fires and sets user
   - If that fails, `RefreshTokenPrePersistSubscriber` catches it before DB insert
   - Refresh token is persisted with `user_id` set

2. **During Token Refresh**:
   - Same process ensures user is always set

## Files Changed
1. ✅ `src/Entity/RefreshToken.php` - Fixed nullable constraint
2. ✅ `src/Entity/User.php` - Added refreshTokens relationship
3. ✅ `src/EventListener/RefreshTokenCreatedListener.php` - Event listener
4. ✅ `src/EventListener/RefreshTokenPrePersistSubscriber.php` - Doctrine subscriber

## Testing
After deployment, test with:
```bash
curl -X POST 'https://app.api.scmcs.org/api/login' \
  -H 'Content-Type: application/json' \
  -H 'Origin: https://fpb.scmcs.org' \
  --data-raw '{"email":"isamuhammad0131@gmail.com","password":"12345678"}'
```

## Deployment Steps
1. Deploy code changes
2. Clear cache: `php bin/console cache:clear --env=prod`
3. Test login endpoint
4. Monitor logs for any remaining errors

## Status
✅ All fixes implemented and committed
✅ Dual-layer protection (event listener + Doctrine subscriber)
✅ Should prevent `user_id cannot be null` errors

