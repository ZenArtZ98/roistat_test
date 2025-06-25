<?php
namespace Src;

class AmoSender
{
    private string $token;
    private string $webhookUrl;

    public function __construct(string $token)
    {
        $this->token = $token;
        $this->webhookUrl = 'https://sergeyvolkov98.amocrm.ru/api/v4/leads/complex';
    }

    public function send(array $payload): array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->webhookUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->token}",
                "Content-Type: application/json"
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([$payload]),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            Logger::write("cURL error: " . curl_error($ch));
        } else {
            Logger::write("Response code: $httpCode, Body: $response");
        }

        curl_close($ch);

        return ['code' => $httpCode, 'response' => $response];
    }
}
