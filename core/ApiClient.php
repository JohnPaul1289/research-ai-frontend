<?php

class ApiClient {
    private static function getBaseUrl(): string {
        return getenv('API_URL') ?: 'https://research-ai-api.onrender.com';
    }

    public static function post(string $endpoint, array $data): ?array {
        $url = self::getBaseUrl() . $endpoint;
        
        $ch = curl_init($url);
        $jsonData = json_encode($data);
        
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];
        
        $token = Session::get('jwt_token');
        if ($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        if ($curlError) {
            error_log("ApiClient POST error: $curlError");
            return null;
        }

        if ($response) {
            $decoded = json_decode($response, true);
            if ($decoded !== null) {
                return $decoded;
            }
        }
        
        return null; 
    }
    
    public static function get(string $endpoint): ?array {
        $url = self::getBaseUrl() . $endpoint;
        
        $ch = curl_init($url);
        
        $headers = [
            'Accept: application/json'
        ];
        
        $token = Session::get('jwt_token');
        if ($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        if ($curlError) {
            error_log("ApiClient GET error: $curlError");
            return null;
        }

        if ($response) {
            $decoded = json_decode($response, true);
            if ($decoded !== null) {
                return $decoded;
            }
        }
        
        return null;
    }
}
