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
        $this->serviceAccountKeyPath = storage_path('./star-taxi-bfd86-90bda9de299f.json');
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
    public function sendNotification($payload, $recipientValue, $recipientType='token')
    {
        $notificationPayload = [
            'message' => [
                $recipientType => $recipientValue, // 'topic' or 'token'
                'notification' => $payload['notification'] ?? [],
                'android' => [
                        'notification' =>
                            [
                                'channel_id' => 'channel_id2',
                                'sound' => 'default',
                                'notification_priority' => 'PRIORITY_HIGH',
                                'visibility' => 'PUBLIC'
                            ]
                    ] ?? [],
                'data' => $payload['data'] ?? [],
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
    }
}

/*
 * todo complete this tomorrow
    <?php

namespace App\Http\Controllers;

use App\Services\FcmNotificationService;
use Illuminate\Http\Request;
use Exception;

class FcmNotificationController extends Controller
{
    protected $fcmService;

    public function __construct(FcmNotificationService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function sendNotification(Request $request)
    {
        try {
            // التحقق من صحة البيانات المدخلة
            $request->validate([
                'recipient_type' => 'required|in:topic,token', // 'topic' أو 'token'
                'recipient_value' => 'required|string', // قيمة الموضوع أو رمز الجهاز
                'payload' => 'required|array', // جسم الإشعار
            ]);

            // الحصول على البيانات من الطلب
            $recipientType = $request->input('recipient_type');
            $recipientValue = $request->input('recipient_value');
            $payload = $request->input('payload');

            // الحصول على رمز الوصول
            $accessToken = $this->fcmService->getAccessToken();

            // إرسال الإشعار
            $response = $this->fcmService->sendNotification($accessToken, $payload, $recipientType, $recipientValue);

            return response()->json($response);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}



 {
    'recipient_type': 'topic', // أو 'token'
    'recipient_value': 'driver', // أو 'device_token'
    'payload': {
        'notification': {
            'title': 'Hello!',
            'body': 'This is a dynamic notification sent via FCM.'
        },
        'android': {
            'notification': {
                'channel_id': 'channel_id2',
                'sound': 'default',
                'notification_priority': 'PRIORITY_HIGH',
                'visibility': 'PUBLIC'
            }
        },
        'data': {
            'key1': 'value1',
            'key2': 'value2'
        }
    }
}
 */
