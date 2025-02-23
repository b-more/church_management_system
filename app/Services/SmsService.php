<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class SmsService
{
    private static function getConfig(): array
    {
        return [
            'username' => config('services.sms.username', 'Blessmore'),
            'password' => config('services.sms.password', 'Blessmore'),
            'shortcode' => config('services.sms.shortcode', '2343'),
            'sender_id' => config('services.sms.sender_id', 'HKC'),
            'api_key' => config('services.sms.api_key', '121231313213123123'),
            'api_url' => config('services.sms.api_url', 'https://www.cloudservicezm.com/smsservice/httpapi'),
        ];
    }

    public static function send(string $message, string $phone_number): bool
    {
        try {
            // Log the attempt
            Log::info('Sending SMS notification', [
                'phone' => $phone_number,
                'message' => $message
            ]);

            // Get configuration
            $config = self::getConfig();

            // Format phone number
            $phone_number = self::formatPhoneNumber($phone_number);

            // Build query parameters
            $params = [
                'username' => $config['username'],
                'password' => $config['password'],
                'msg' => $message,
                'shortcode' => $config['shortcode'],
                'sender_id' => $config['sender_id'],
                'phone' => $phone_number,
                'api_key' => $config['api_key'],
            ];

            // Make the request using Laravel's HTTP client
            $response = Http::get($config['api_url'], $params);

            // Check if request was successful
            if (!$response->successful()) {
                throw new \Exception("SMS API returned status code: {$response->status()}");
            }

            // Log success
            Log::info('SMS sent successfully', [
                'phone' => $phone_number,
                'response' => $response->body()
            ]);

            return true;

        } catch (\Exception $e) {
            // Log error
            Log::error('SMS sending failed', [
                'error' => $e->getMessage(),
                'phone' => $phone_number,
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    private static function formatPhoneNumber(string $phone): string
    {
        // Remove any spaces, dashes, or other formatting
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If the number starts with '0', replace it with '26'
        if (str_starts_with($phone, '0')) {
            $phone = '26' . substr($phone, 1);
        }

        // If the number doesn't start with '260', add it
        if (!str_starts_with($phone, '260')) {
            $phone = '260' . $phone;
        }

        return $phone;
    }
}
