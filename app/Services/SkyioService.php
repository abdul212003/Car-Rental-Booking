<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SkyioService
{
    protected $baseUrl;
    protected $apiKey;
    
    public function __construct()
    {
        $this->baseUrl = config('services.skyio.base_url');
        $this->apiKey = config('services.skyio.api_key');
    }

      public function sendSMS($to, $message, $from = null)
    {
        try {
            $payload = [
                'to' => $to,
                'message' => $message,
            ];

            // Add sender ID if provided
            if ($from) {
                $payload['from'] = $from;
            }

            Log::info('Sending SMS via SkyIO', [
                'endpoint' => $this->baseUrl . '/api/sms/send',
                'to' => $to,
                'message_length' => strlen($message),
                'payload' => $payload
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->baseUrl . '/api/sms/send', $payload);

            $responseData = $response->json();
            
            Log::info('SkyIO SMS Response', [
                'status_code' => $response->status(),
                'response' => $responseData
            ]);

            return $responseData;
        } catch (\Exception $e) {
            Log::error('SkyIO SMS sending failed: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    // public function getBalance()
    // {
    //     try {
    //         $response = Http::withHeaders([
    //             'Authorization' => 'Bearer' . $this->apiKey,
    //         ])->get($this->baseUrl . '/account/balance');

    //         return $response->json();
    //     } catch (\Exception $e) {
    //         Log::error('SkyIO balance check failed: ' . $e->getMessage());
    //         return ['error' => $e->getMessage()];
    //     }
    // }

    // public function getSMSStatus($messageId)
    // {
    //     try {
    //         $response = Http::withHeader([
    //             'Authorization' => 'Bearer' . $this->apiKey,
    //         ])->get($this->baseUrl . '/sms/status/' . $messageId);

    //         return $response->json();
    //     } catch (\Exception $e) {
    //         Log::error('SkyIO status check failed: ' . $e->getMessage());
    //         return ['error' => $e->getMessage()];
    //     }
    // }
}