# Sign-Up Endpoint Setup

## Overview
A sign-up endpoint has been created that links existing Member profiles to new User accounts. The system sends a welcome email with a default password and a password reset token.

## Environment Variables Required

Add the following to your `.env` or `.env.local` file:

```env
# Mailer Configuration
MAILER_DSN=smtps://info@scmcs.org:Scmcs_1234@mail.scmcs.org:465

# Application URL (for password reset links in emails)
APP_URL=http://localhost:8000
```

**Note:** Update `APP_URL` to your production domain when deploying.

## Database Migration

Run the migration to add the new fields to the User table:

```bash
php bin/console doctrine:migrations:migrate
```

This will add:
- `password_reset_token` - Token for password reset
- `password_reset_expires_at` - Expiration date for the token
- `must_change_password` - Flag indicating if password must be changed

## API Endpoints

### 1. Sign-Up Endpoint
**POST** `/api/sign-up`

Creates a new User account linked to an existing Member profile.

**Request Body:**
```json
{
  "email": "member@example.com"
}
```

**Success Response (201):**
```json
{
  "message": "User account created successfully. Welcome email sent.",
  "user": {
    "id": 1,
    "email": "member@example.com"
  }
}
```

**Error Responses:**
- `400` - Member profile not found for this email
- `409` - User account already exists for this email

### 2. Password Update Endpoint
**POST** `/api/password/update`

Updates user password using the reset token sent via email.

**Request Body:**
```json
{
  "token": "reset_token_from_email",
  "newPassword": "newSecurePassword123"
}
```

**Query Parameter (Alternative):**
- `token` - Can also be passed as a query parameter: `/api/password/update?token=xxx`

**Success Response (200):**
```json
{
  "message": "Password updated successfully. You can now log in with your new password."
}
```

**Error Responses:**
- `400` - Invalid request, missing token/password, or expired token
- `404` - Invalid token

## Email Template

The welcome email is sent from `info@scmcs.org` and includes:
- Default password (temporary)
- Password reset link with token
- Instructions to change password

The email template is located at: `templates/emails/welcome.html.twig`

## Security

Both endpoints are configured as public endpoints (no authentication required):
- `/api/sign-up` - Public access
- `/api/password/update` - Public access

All other API endpoints remain protected and require JWT authentication.

## How It Works

1. **Sign-Up Process:**
   - User provides email address
   - System checks if Member exists with that email
   - System checks if User account already exists
   - Creates new User account linked to Member
   - Generates random default password (16 characters)
   - Generates password reset token (64 characters, valid for 24 hours)
   - Sets `mustChangePassword` flag to `true`
   - Sends welcome email with default password and reset link

2. **Password Update Process:**
   - User clicks link in email or provides token manually
   - System validates token and checks expiration
   - User provides new password (minimum 8 characters)
   - System updates password and clears reset token
   - Sets `mustChangePassword` flag to `false`
   - User can now log in with new password

## Testing

1. Ensure a Member exists in `tbl_users` with a valid email
2. Call the sign-up endpoint with that email
3. Check email inbox for welcome message
4. Use the token from email to update password
5. Test login with new password

## Notes

- The default password is randomly generated (16 hex characters)
- Password reset tokens expire after 24 hours
- Email sending errors are caught but don't fail the sign-up request
- The system enforces password minimum length of 8 characters

