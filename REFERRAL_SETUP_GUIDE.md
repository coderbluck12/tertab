# Referral System - Quick Setup Guide

## ✅ Installation Complete!

The referral system has been successfully installed and configured. Here's what was added:

## 🎯 What's New

### 1. **Database Tables**
- ✅ `referrals` - Tracks all referral relationships
- ✅ `affiliate_applications` - Manages affiliate program applications
- ✅ Updated `users` table with `referral_code` and `referred_by` columns

### 2. **New Pages**
- 🌐 **Public Affiliate Page**: `/affiliate`
- 👥 **Referral Dashboard**: `/referrals` (for logged-in users)
- 🔧 **Admin Affiliate Management**: `/admin/affiliates`

### 3. **Navigation Updates**
- Added "Become Affiliate" link to homepage
- Added "Referrals" menu item for students and lecturers
- Admin menu includes affiliate management

## 🚀 Quick Start

### For Users (Students & Lecturers)

1. **Login to your account**
2. **Click "Referrals" in the navigation menu**
3. **Copy your unique referral link**
4. **Share with friends and colleagues**
5. **Earn commissions when they sign up!**

### For Affiliates (Non-Users)

1. **Visit**: `https://yoursite.com/affiliate`
2. **Fill out the application form**
3. **Wait for admin approval**
4. **Receive login credentials**
5. **Start promoting and earning!**

### For Admins

1. **Login to admin panel**
2. **Navigate to**: `/admin/affiliates`
3. **Review pending applications**
4. **Approve or reject applications**
5. **Monitor referral performance**

## 📊 Features Available

### Referral Dashboard
- View total referrals count
- Track pending, completed, and rewarded referrals
- See total earnings and pending commissions
- Copy referral link with one click
- Share on social media (WhatsApp, Twitter, Facebook)
- View detailed referral history

### Affiliate Application
- Professional application form
- Email and phone validation
- Detailed reason field (minimum 50 characters)
- Terms and conditions acceptance
- Automatic status tracking

### Admin Management
- Filter applications by status
- View detailed application information
- Approve with account creation
- Reject with admin notes
- Full audit trail

## 🔧 Configuration Needed

### 1. Set Commission Rates (Optional)

Add to your `.env` file:
```env
REFERRAL_COMMISSION_STUDENT=500
REFERRAL_COMMISSION_LECTURER=1000
REFERRAL_COMMISSION_AFFILIATE=1500
```

### 2. Email Notifications (Recommended)

To enable email notifications for affiliates, update:
- `AffiliateController::approve()` - Add email sending logic
- `AffiliateController::reject()` - Add rejection email

Example:
```php
use Illuminate\Support\Facades\Mail;
use App\Mail\AffiliateApproved;

// In approve method
Mail::to($application->email)->send(new AffiliateApproved($user, $temporaryPassword));
```

### 3. Commission Payment Integration

To automatically credit commissions, integrate with your payment system:

```php
// Example: In your payment success handler
use App\Http\Controllers\ReferralController;

// After user completes first transaction
if ($user->referralReceived && $user->referralReceived->status === 'pending') {
    $commissionAmount = 500; // Set your commission amount
    ReferralController::completeReferral($user->id, $commissionAmount);
}
```

## 🧪 Testing Checklist

### Test Affiliate Application
- [ ] Visit `/affiliate` page
- [ ] Submit application with valid data
- [ ] Check validation errors work
- [ ] Verify application appears in admin panel
- [ ] Test approve/reject functionality

### Test Referral Links
- [ ] Login as student or lecturer
- [ ] Visit `/referrals` page
- [ ] Copy referral link
- [ ] Open link in incognito window
- [ ] Register new account
- [ ] Verify referral is tracked in database
- [ ] Check referral appears in dashboard

### Test Social Sharing
- [ ] Click WhatsApp share button
- [ ] Click Twitter share button
- [ ] Click Facebook share button
- [ ] Verify links work correctly

### Test Admin Features
- [ ] Login as admin
- [ ] Visit `/admin/affiliates`
- [ ] Filter by different statuses
- [ ] View application details
- [ ] Approve an application
- [ ] Verify user account created
- [ ] Reject an application

## 📁 Files Created/Modified

### New Files
```
database/migrations/2025_10_18_000000_create_referrals_table.php
app/Models/Referral.php
app/Models/AffiliateApplication.php
app/Http/Controllers/ReferralController.php
app/Http/Controllers/AffiliateController.php
resources/views/affiliate/index.blade.php
resources/views/referrals/index.blade.php
resources/views/admin/affiliates/index.blade.php
REFERRAL_SYSTEM_README.md
REFERRAL_SETUP_GUIDE.md
```

### Modified Files
```
app/Models/User.php
app/Http/Controllers/Auth/RegisteredUserController.php
routes/web.php
resources/views/welcome.blade.php
resources/views/layouts/navigation.blade.php
```

## 🎨 Customization

### Change Referral Code Length
Edit `User::generateReferralCode()` in `app/Models/User.php`:
```php
$code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8)); // Change 8 to desired length
```

### Customize Commission Amounts
Edit in `ReferralController::completeReferral()` or create a settings table.

### Add More Social Share Buttons
Edit `resources/views/referrals/index.blade.php` and add more share buttons.

## 🔒 Security Notes

1. **Referral codes are unique** - No duplicates possible
2. **Users cannot refer themselves** - Validation in place
3. **Affiliate applications require approval** - Admin control
4. **Email uniqueness enforced** - Prevents duplicate applications
5. **Commission only paid after transaction** - Prevents fraud

## 📈 Next Steps

1. **Test the system thoroughly**
2. **Configure commission rates**
3. **Set up email notifications**
4. **Integrate commission payment**
5. **Promote the affiliate program**
6. **Monitor referral performance**

## 🆘 Troubleshooting

### Referral link not working?
- Check if referral code exists in database
- Verify route is registered: `php artisan route:list | grep referral`
- Clear cache: `php artisan cache:clear`

### Migration errors?
- Check database connection
- Verify no duplicate columns exist
- Run: `php artisan migrate:fresh` (⚠️ WARNING: This will delete all data!)

### Referral not tracked?
- Check session storage
- Verify `ReferralController::processReferral()` is called
- Check database for referral record

## 📞 Support

For detailed documentation, see `REFERRAL_SYSTEM_README.md`

---

**🎉 Congratulations! Your referral system is ready to use!**

Start promoting your platform and watch your user base grow! 🚀
