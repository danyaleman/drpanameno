<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DailyService
{
    private $apiKey;
    private $baseUrl = 'https://api.daily.co/v1';

    public function __construct()
    {
        $this->apiKey = env('DAILY_API_KEY');
    }

    /**
     * Create a room in Daily.co
     *
     * @param string|null $roomName Optional custom room name.
     * @param array $properties Optional room properties.
     * @return array|null 
     */
    public function createRoom($roomName = null, $properties = [])
    {
        $defaultProperties = [
            'exp' => time() + 86400, // Expires in 24 hours
            'enable_chat' => true,
            'enable_recording' => 'cloud',
        ];

        // Ensure token generation is allowed and recording triggers automatically if desired
        // Actually daily records automatically if 'start_cloud_recording' property is set, or user can start it via UI.

        $payload = [
            'privacy' => 'public', // Temporarily public for testing, or we generate meeting tokens for private
            'properties' => array_merge($defaultProperties, $properties)
        ];

        if ($roomName) {
            $payload['name'] = $roomName;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/rooms', $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Daily.co API Failed to create room: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Daily.co API Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete a room in Daily.co
     *
     * @param string $roomName
     * @return boolean
     */
    public function deleteRoom($roomName)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->delete($this->baseUrl . '/rooms/' . $roomName);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Daily.co API Exception on delete: ' . $e->getMessage());
            return false;
        }
    }
}
