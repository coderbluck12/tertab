<?php

namespace App\Mail;

use Illuminate\Mail\MailManager;
use Illuminate\Support\Arr;
use Swift_Mailer;
use Swift_SmtpTransport;

class CustomMailManager extends MailManager
{
    protected function createSmtpDriver(array $config)
    {
        $transport = new Swift_SmtpTransport(
            $config['host'],
            $config['port'],
            $config['encryption'] ?? null
        );

        if (isset($config['username'])) {
            $transport->setUsername($config['username']);
            $transport->setPassword($config['password']);
        }

        if (isset($config['timeout'])) {
            $transport->setTimeout($config['timeout']);
        }

        if (isset($config['local_domain'])) {
            $transport->setLocalDomain($config['local_domain']);
        }

        // Create a custom Swift_Mailer that ignores 550 errors
        $mailer = new class($transport) extends Swift_Mailer {
            public function send($message, &$failedRecipients = null)
            {
                try {
                    return parent::send($message, $failedRecipients);
                } catch (\Swift_TransportException $e) {
                    if (strpos($e->getMessage(), '550 No Such User Here') !== false) {
                        // Log the error but don't throw it
                        \Log::warning('Mail sending warning: ' . $e->getMessage());
                        return 1; // Return success
                    }
                    throw $e;
                }
            }
        };

        return $mailer;
    }
} 