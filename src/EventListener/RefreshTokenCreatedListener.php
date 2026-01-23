<?php

namespace App\EventListener;

/**
 * NOTE: This listener is no longer needed.
 * 
 * The Gesdinet bundle does NOT dispatch a RefreshTokenCreatedEvent.
 * Instead, we override RefreshToken::createForUserWithTtl() to set the user
 * when the refresh token is created, and we use RefreshTokenPrePersistSubscriber
 * as a fallback to ensure the user is set before persistence.
 * 
 * This file is kept for reference but the class is intentionally empty.
 */
class RefreshTokenCreatedListener
{
    // This listener is no longer used - the user is set in RefreshToken::createForUserWithTtl()
    // and RefreshTokenPrePersistSubscriber ensures it's set before persistence
}

