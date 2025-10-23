# Referral System Documentation

## Overview
The referral system allows students, lecturers, and affiliates to refer new users to the platform and earn commissions. The system tracks referrals, manages affiliate applications, and provides comprehensive analytics.

## Features

### 1. **Affiliate Program for Non-Registered Users**
- Public affiliate application page at `/affiliate`
- Application form with validation
- Admin approval workflow
- Automatic account creation upon approval

### 2. **Referral Links for Students and Lecturers**
- Unique referral code generated for each user
- Shareable referral link: `https://yoursite.com/register?ref=XXXXXXXX`
- Social media sharing buttons (WhatsApp, Twitter, Facebook)
- Copy-to-clipboard functionality

### 3. **Referral Tracking**
- Automatic tracking when users register via referral link
- Referral status: pending, completed, rewarded
- Commission tracking and payment
- Real-time statistics dashboard

### 4. **Admin Management**
- View all affiliate applications
- Approve/reject applications
- Filter by status (pending, approved, rejected)
- View referral statistics

## Database Structure

### Tables Created

#### 1. `users` table (modified)
- `referral_code` - Unique 8-character code for each user
- `referred_by` - Foreign key to the user who referred them

#### 2. `referrals` table
- `referrer_id` - User who made the referral
- `referred_user_id` - User who was referred
- `referral_code` - Code used for referral
- `status` - pending, completed, rewarded
- `commission_amount` - Amount earned
- `commission_paid` - Boolean flag
- `completed_at` - When referral was completed
- `rewarded_at` - When commission was paid

#### 3. `affiliate_applications` table
- `name` - Applicant's name
- `email` - Applicant's email
- `phone` - Applicant's phone (optional)
- `reason` - Why they want to be an affiliate
- `status` - pending, approved, rejected
- `user_id` - Created user account (after approval)
- `admin_notes` - Admin comments
- `approved_at` - Approval timestamp

## Routes

### Public Routes
```php
GET  /affiliate                    - Affiliate application page
POST /affiliate/apply              - Submit affiliate application
GET  /ref/{code}                   - Track referral click and redirect to register
```

### Authenticated Routes
```php
GET  /referrals                    - Referral dashboard
POST /referrals/generate           - Generate referral code
GET  /referrals/statistics         - Get referral statistics (JSON)
```

### Admin Routes
```php
GET    /admin/affiliates           - List all affiliate applications
GET    /admin/affiliates/{id}      - View single application (JSON)
POST   /admin/affiliates/{id}/approve - Approve application
POST   /admin/affiliates/{id}/reject  - Reject application
DELETE /admin/affiliates/{id}      - Delete application
```

## Models

### User Model
**New Methods:**
- `generateReferralCode()` - Static method to generate unique code
- `getReferralLinkAttribute()` - Get full referral URL
- `getTotalReferralEarningsAttribute()` - Calculate total earnings
- `getPendingReferralEarningsAttribute()` - Calculate pending earnings

**New Relationships:**
- `referrer()` - User who referred this user
- `referrals()` - Users referred by this user
- `referralsMade()` - Referral records as referrer
- `referralReceived()` - Referral record as referred user

### Referral Model
**Methods:**
- `markAsCompleted($commissionAmount)` - Mark referral as completed
- `markAsRewarded()` - Mark commission as paid

**Scopes:**
- `pending()` - Get pending referrals
- `completed()` - Get completed referrals
- `rewarded()` - Get rewarded referrals

### AffiliateApplication Model
**Methods:**
- `approve($adminNotes)` - Approve application
- `reject($adminNotes)` - Reject application

**Scopes:**
- `pending()` - Get pending applications
- `approved()` - Get approved applications
- `rejected()` - Get rejected applications

## Controllers

### ReferralController
- `index()` - Display referral dashboard
- `generateCode()` - Generate referral code for user
- `statistics()` - Get referral statistics (API)
- `trackClick($code)` - Track referral link clicks
- `processReferral($newUser, $referralCode)` - Static method to process referral
- `completeReferral($userId, $commissionAmount)` - Static method to complete referral

### AffiliateController
- `index()` - Show affiliate application page
- `store()` - Submit affiliate application
- `adminIndex()` - Admin view of all applications
- `show($id)` - View single application (JSON)
- `approve($id)` - Approve application
- `reject($id)` - Reject application
- `destroy($id)` - Delete application

## Views

### Public Views
- `affiliate/index.blade.php` - Affiliate application page

### User Views
- `referrals/index.blade.php` - Referral dashboard for students/lecturers

### Admin Views
- `admin/affiliates/index.blade.php` - Manage affiliate applications

## Usage

### For Users (Students/Lecturers)

1. **Access Referral Dashboard:**
   - Navigate to "Referrals" in the main menu
   - View your referral statistics and link

2. **Share Referral Link:**
   - Copy your unique referral link
   - Share via social media or direct messaging
   - Track referrals in real-time

3. **Earn Commissions:**
   - Earn when referred users complete their first transaction
   - View earnings in the dashboard
   - Commissions credited to wallet

### For Affiliates

1. **Apply:**
   - Visit `/affiliate` page
   - Fill out application form
   - Wait for admin approval

2. **Get Approved:**
   - Admin reviews application
   - Account created with temporary password
   - Receive login credentials via email

3. **Start Referring:**
   - Login to dashboard
   - Get referral link
   - Start promoting

### For Admins

1. **Manage Applications:**
   - Navigate to `/admin/affiliates`
   - Filter by status
   - View application details

2. **Approve/Reject:**
   - Review application reason
   - Add admin notes
   - Approve or reject

3. **Monitor Referrals:**
   - Track all referrals across platform
   - View commission payouts
   - Manage affiliate accounts

## Commission System

### How It Works

1. **User Registers with Referral Code:**
   - Code stored in session during registration
   - User account created with `referred_by` field
   - Referral record created with status "pending"

2. **Referral Completion:**
   - Triggered when referred user makes first transaction
   - Call `ReferralController::completeReferral($userId, $amount)`
   - Status changes to "completed"
   - Commission amount recorded

3. **Commission Payment:**
   - Commission credited to referrer's wallet
   - Status changes to "rewarded"
   - Referrer notified

### Integration Points

To complete referrals when users make transactions, add this code:

```php
use App\Http\Controllers\ReferralController;

// After successful payment/transaction
ReferralController::completeReferral($userId, $commissionAmount);
```

## Configuration

### Commission Rates
Set commission rates in your `.env` file or platform settings:

```env
REFERRAL_COMMISSION_STUDENT=500
REFERRAL_COMMISSION_LECTURER=1000
```

### Referral Code Length
Modify in `User::generateReferralCode()` method (default: 8 characters)

## Security Considerations

1. **Validation:**
   - Email uniqueness checked across users and applications
   - Minimum 50 characters for affiliate application reason
   - reCAPTCHA on registration form

2. **Fraud Prevention:**
   - Users cannot refer themselves
   - Referral codes are unique and random
   - Commission only paid after transaction completion

3. **Admin Controls:**
   - All affiliate applications require approval
   - Admins can add notes and reject applications
   - Full audit trail of referral activities

## Testing

### Test Scenarios

1. **Affiliate Application:**
   - Visit `/affiliate`
   - Submit application
   - Check admin panel for new application

2. **Referral Link:**
   - Login as user
   - Copy referral link
   - Open in incognito/private window
   - Register new account
   - Verify referral tracked

3. **Commission Flow:**
   - Create referral
   - Simulate transaction for referred user
   - Verify commission credited

## Migration

Run the migration to set up the database:

```bash
php artisan migrate
```

This will:
- Add `referral_code` and `referred_by` columns to `users` table
- Create `referrals` table
- Create `affiliate_applications` table

## Future Enhancements

1. **Email Notifications:**
   - Notify users when someone uses their referral link
   - Notify affiliates when application is approved/rejected
   - Monthly referral performance reports

2. **Advanced Analytics:**
   - Conversion rates
   - Top referrers leaderboard
   - Geographic distribution of referrals

3. **Tiered Commission:**
   - Different rates based on referrer level
   - Bonus for reaching milestones
   - Recurring commissions

4. **Marketing Materials:**
   - Downloadable banners and graphics
   - Email templates
   - Social media post templates

## Support

For issues or questions about the referral system:
- Check this documentation
- Review the code comments
- Contact the development team
