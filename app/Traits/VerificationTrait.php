<?php

namespace App\Traits;

use HttpRequests;

trait VerificationTrait
{
    /**
     * Verify a single email using NeverBounce API.
     *
     * @param string $email
     * @return bool
     */
    public function verifySingleEmail($email)
    {
        $apiKey = config('services.neverbounce.api_key');
        $url = 'https://api.neverbounce.com/v4/single/check';

        $data = [
            'api_key' => $apiKey,
            'email' => $email,
        ];

        $response = HttpRequests::postWithHeader($url, [], $data);

        $result = $response->json();
        return isset($result['result']) ? $result['result'] === 'valid' : false;
    }

    /**
     * Verify bulk emails using NeverBounce API.
     *
     * @param array $emails
     * @return array
     */
    public function verifyBulkEmail($emails)
    {
        $apiKey = config('services.neverbounce.api_key');
        $url = 'https://api.neverbounce.com/v4/bulk/create';

        $data = [
            'api_key' => $apiKey,
            'input_location' => 'supplied',
            'filename' => 'BulkEmailValidation' . date('Y-m-d H:i:s') . '.csv',
            'auto_start' => 1,
            'auto_parse' => 1,
            'input' => $emails,
        ];

        $jobResult = HttpRequests::postWithHeader($url, [], $data)->json();

        if (isset($jobResult['status']) && $jobResult['status'] === 'success') {
            $jobId = $jobResult['data']['id'];
            $resultResponse = HttpRequests::getWithHeader("https://api.neverbounce.com/v4/bulk/results?api_key={$apiKey}&job_id={$jobId}");
            $results = $resultResponse->json();
            $validEmails = [];
            foreach ($results['results'] as $result) {
                if ($result['result'] === 'valid') {
                    $validEmails[] = $result['input'];
                }
            }

            return $validEmails;
        }

        return [];
    }
}
