<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DeliveryService;
use Illuminate\Support\Facades\Log;
use App\Mail\DeliveryFailureNotification;
use Illuminate\Support\Facades\Mail;

class DeliveryController extends Controller
{
    private $deliveryService;

    public function __construct(DeliveryService $deliveryService)
    {
        $this->deliveryService = $deliveryService;
    }

    public function sendDelivery(Request $request)
    {
        $packageData = $request->input('package');
        $recipientData = $request->input('recipient');

        $requestData = [
            'customer_name' => $recipientData['full_name'],
            'phone_number' => $recipientData['phone_number'],
            'email' => $recipientData['email'],
            'sender_address' => config('app.sender_address'),
            'delivery_address' => $recipientData['address'],
        ];

        // Отправляем запрос на сервер курьерской службы
        $response = $this->deliveryService->sendRequest($requestData);

        // Логируем отправленные данные и ответ от сервера
        Log::info('Sent data to courier service', [
            'requestData' => $requestData,
            'response' => $response,
        ]);

        // Обрабатываем ответ от сервера курьерской службы
        if ($response['success']) {
            return response()->json(['message' => 'Данные успешно отправлены на сервер курьерской службы']);
        } else {
            // Логируем ошибку
            Log::error('Failed to send data to courier service', [
                'requestData' => $requestData,
                'response' => $response,
            ]);
            $this->sendDeliveryFailureNotification();

            return response()->json(['message' => 'Ошибка при отправке данных на сервер курьерской службы'], 500);
        }
    }

    private function sendDeliveryFailureNotification()
    {
        // Получаем адрес электронной почты администратора магазина из конфигурации
        $adminEmail = config('app.admin_email');

        Mail::to($adminEmail)->send(new DeliveryFailureNotification());
    }
}
