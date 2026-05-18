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
        $this->apiKey = config('services.daily.api_key');
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
            'exp' => time() + (86400 * 30), // Expires in 30 days
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
            $http = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ]);

            // En entorno local (WAMP/XAMPP) cURL no tiene certificados CA configurados.
            // Solo desactivamos la verificación SSL en desarrollo, nunca en producción.
            if (app()->environment('local', 'development')) {
                $http = $http->withoutVerifying();
            }

            $response = $http->post($this->baseUrl . '/rooms', $payload);

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
    /**
     * Check if a room exists and has not expired on Daily.co
     */
    public function roomExists($roomName): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/rooms/' . $roomName);

            if (!$response->successful()) {
                return false;
            }

            $data = $response->json();
            // If it has an expiry, check it hasn't passed
            if (!empty($data['config']['exp']) && $data['config']['exp'] < time()) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Daily.co roomExists Exception: ' . $e->getMessage());
            return false;
        }
    }

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

    /**
     * List recordings from Daily.co
     *
     * @param int $limit
     * @return array|null
     */
    public function getRecordings($limit = 100)
    {
        try {
            $http = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ]);

            if (app()->environment('local', 'development')) {
                $http = $http->withoutVerifying();
            }

            $response = $http->get($this->baseUrl . '/recordings', [
                'limit' => $limit,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Daily.co API Failed to get recordings: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Daily.co getRecordings Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get a single recording by ID
     *
     * @param string $recordingId
     * @return array|null
     */
    public function getRecording($recordingId)
    {
        try {
            $http = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ]);

            if (app()->environment('local', 'development')) {
                $http = $http->withoutVerifying();
            }

            $response = $http->get($this->baseUrl . '/recordings/' . $recordingId);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Daily.co API Failed to get recording: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Daily.co getRecording Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get a temporary access link for a recording (playback/download)
     *
     * @param string $recordingId
     * @return array|null Returns ['download_link' => '...'] or null
     */
    public function getRecordingAccessLink($recordingId)
    {
        try {
            $http = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ]);

            if (app()->environment('local', 'development')) {
                $http = $http->withoutVerifying();
            }

            $response = $http->get($this->baseUrl . '/recordings/' . $recordingId . '/access-link');

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Daily.co API Failed to get access link: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Daily.co getRecordingAccessLink Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get transcript for a recording
     *
     * @param string $recordingId
     * @return array|null
     */
    public function getTranscript($recordingId)
    {
        try {
            $http = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ]);

            if (app()->environment('local', 'development')) {
                $http = $http->withoutVerifying();
            }

            $response = $http->get($this->baseUrl . '/recordings/' . $recordingId . '/transcript');

            if ($response->successful()) {
                return $response->json();
            }

            // Transcript may not exist for all recordings
            return null;
        } catch (\Exception $e) {
            Log::error('Daily.co getTranscript Exception: ' . $e->getMessage());
            return null;
        }
    }
}
