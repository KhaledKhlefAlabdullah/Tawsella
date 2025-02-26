<?php

namespace App\Services;

use Google\Client;
use GuzzleHttp\Client as GuzzleClient;
use Exception;

class FcmNotificationService
{
    protected $serviceAccountKeyPath;

    public function __construct()
    {
        // Path to your service account key JSON file
        $this->serviceAccountKeyPath = __DIR__ . '/star-taxi-bfd86-90bda9de299f.json';
    }

    /**
     * Get the access token using the service account key.
     *
     * @return string
     * @throws Exception
     */
    public function getAccessToken()
    {
        $client = new Client();
        $client->setAuthConfig($this->serviceAccountKeyPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->setSubject(json_decode(file_get_contents($this->serviceAccountKeyPath), true)['client_email']);

        $accessToken = $client->fetchAccessTokenWithAssertion();

        if (!isset($accessToken['access_token'])) {
            throw new Exception('Failed to generate access token');
        }

        return $accessToken['access_token'];
    }

    /**
     * Send an FCM notification.
     *
     * @param array $payload
     * @param string $recipientType (e.g., 'topic', 'token')
     * @param string $recipientValue (e.g., 'driver', 'device_token')
     * @return array
     * @throws Exception
     */
    public function sendNotification($payload, $recipientValue, $recipientType = 'token')
    {
        $notificationPayload = [
            'message' => [
                $recipientType => $recipientValue, // 'topic' or 'token'
//                'notification' => [
//                    'title' => $payload['notification']['title'] ?? 'Default Title',
//                    'body' => $payload['notification']['body'] ?? 'Default Body',
//                ],
                'android' => [
                    'priority' => 'HIGH',
                    'notification' => [
                        'channel_id' => 'channel_id2',
                        'sound' => 'default',
                        'notification_priority' => 'PRIORITY_HIGH',
                        'visibility' => 'PUBLIC',
                        'title' => $payload['notification']['title'] ?? 'Default Title',
                        'body' => $payload['notification']['body'] ?? 'Default Body',
                    ]
                ],
                'apns' => [
                    'headers' => [
                        'apns-priority' => '10',
                        'apns-push-type' => 'alert',
                    ],
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                            'content-available' => 1,
                            'mutable-content' => 1,
                        ]
                    ]
                ],
                'data' => $payload['data'] ??  [
                        'key1' => 'value1',
                        'key2' => 'value2',
                    ],
            ],
        ];

        $httpClient = new GuzzleClient();

        try {
            $accessToken = $this->getAccessToken();
            $response = $httpClient->post('https://fcm.googleapis.com/v1/projects/star-taxi-bfd86/messages:send', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $notificationPayload,
            ]);

            return [
                'success' => true,
                'response' => json_decode($response->getBody(), true),
            ];
        } catch (Exception $e) {
            throw new Exception('Error sending notification: ' . $e->getMessage());
        }
    }}
