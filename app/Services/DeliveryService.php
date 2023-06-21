<?php

namespace App\Services;

interface DeliveryService
{
    public function sendRequest(array $requestData): array;
}
