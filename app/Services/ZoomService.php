<?php 

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;

class ZoomService
{
    protected $client;
    protected $clientId;
    protected $clientSecret;
    protected $accountId;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->clientId = config('services.zoom.client_id');
        $this->clientSecret = config('services.zoom.client_secret');
        $this->accountId = config('services.zoom.account_id');
        $this->baseUrl = 'https://api.zoom.us/v2/';
    }

    protected function getAccessToken()
    {
        if(Cache::has('zoom_access_token')){
            return cache::get('zoom_access_token');
        }

        try {
            // Generate a new token
            $credentials = base64_encode("{$this->clientId}:{$this->clientSecret}");
            $response = $this->client->request('POST', 'https://zoom.us/oauth/token', [
                'headers' => [
                    'Authorization' => 'Basic ' . $credentials,
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => [
                    'grant_type' => 'account_credentials',
                    'account_id' => $this->accountId,
                ],
            ]);

            $accessToken = json_decode($response->getBody(), true)['access_token'];

            Cache::put('zoom_access_token', $accessToken, 3600); // Cache for 1 hour

            return $accessToken;
        }  catch (Exception $e) {
            Log::error('Error fetching Zoom access token: ' . $e->getMessage());
            throw new \Exception('Unable to fetch Zoom access token.');
        }
    }

    protected function getRecordings($from, $to)
    {
        try {
            $accessToken = $this->getAccessToken();
            $response = $this->client->request(
                'GET', 
                "{$this->baseUrl}accounts/me/recordings", [
                    'headers' => [
                        'Authorization' => 'Bearer' . $accessToken,
                    ],
                    'query' => [
                        'from' => $from,
                        'to' => $to,
                    ],
                ]
            );

            return json_decode($response->getBody(), true);
        } catch (Exception $e) {
            Log::error('Error fetching Zoom account recordings: ' . $e->getMessage());
            throw new \Exception('Unable to fetch Zoom account recordings.');
        }
    }

    public function fetchRecordings($from , $to)
    {
        $recordings = $this->getRecordings($from , $to);
        return $recordings;
    }

    public function getMeetings($userId)
    {
        try{
            $accessToken = $this->getAccessToken();
            $response = $this->client->request(
                'GET',
                "{$this->baseUrl}users/{$userId}/meetings",
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                    ]
                ]
            );

            return json_decode($response->getBody(), true);
        } catch (Exception $e) {
            Log::error('Error fetching Zoom account meetings: ' . $e->getMessage());
            throw new \Exception('Unable to fetch Zoom account meetings.');
        }

    }

    public function getRecordingDetails($meetingId)
    {
        try{
            $accessToken = $this->getAccessToken();
            $response = $this->client->request(
                'GET',
                "{$this->baseUrl}meetings/{$meetingId}/recordings",
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                    ]
                ]
            );

            return json_decode($response->getBody(), true);
        } catch (Exception $e) {
            Log::error('Error fetching Zoom account recordings: ' . $e->getMessage());
            throw new \Exception('Unable to fetch Zoom account recordings.');
        }
        
    }
}
