<?php

namespace App\Services;

use App\Mail\NotificationMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function send($userId, $type, $title, $message, $link = null, $sendEmail = true)
    {
        // Create in-app notification
        Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => false
        ]);

        // Send email notification if required
        if ($sendEmail) {
            $user = User::find($userId);
            if ($user && $user->email) {
                // Log the email address
                \Log::info('Attempting to send notification email', ['user_id' => $userId, 'email' => $user->email]);
                // Validate email address
                if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    Mail::to($user->email)->send(new NotificationMail(
                        $title,
                        $message,
                        'View Details',
                        $link
                    ));
                } else {
                    \Log::error('Invalid email address for notification', ['user_id' => $userId, 'email' => $user->email]);
                }
            }
        }
    }

    public function sendToAdmins($type, $title, $message, $link = null, $sendEmail = true)
    {
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            $this->send($admin->id, $type, $title, $message, $link, $sendEmail);
        }
    }
} 