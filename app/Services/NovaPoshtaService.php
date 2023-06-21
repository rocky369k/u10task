<?php

namespace App\Services;

class NovaPoshtaService implements DeliveryService
{
    public function sendRequest(array $requestData): array
    {
        // Код для отправки запроса на сервер Новой почты
        return ['success' => true];
    }
}
