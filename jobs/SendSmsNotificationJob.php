<?php

declare(strict_types=1);

namespace app\jobs;

use app\models\Book;
use Yii;
use yii\base\BaseObject;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\queue\JobInterface;

class SendSmsNotificationJob extends BaseObject implements JobInterface
{
    /** @var int ID созданной книги */
    public int $bookId;

    public function execute($queue): void
    {
        $book = Book::find()->where(['id' => $this->bookId])->with('authors')->one();
        if ($book === null) {
            return; 
        }

        $authorNames = [];
        foreach ($book->authors as $author) {
            $authorNames[] = $author->full_name;
        }
        $text = "Новинка: \"{$book->title}\" от " . implode(', ', $authorNames) . ". Подробнее на сайте!";

        $apiKey = Yii::$app->params['smsPilotApiKey'] ?? null;
        $sender = Yii::$app->params['smsSenderName'] ?? 'INFORM';

        if (!$apiKey) {
            Yii::error("SMS Pilot API Key не задан в params", 'sms-pilot');
            return;
        }

        $phones = ['+79991112233', '+79992223344']; // Тестовые номера

        $sendBatch = [];
        $batchSize = 200;

        foreach ($phones as $phone) {
            // Чистим номер от лишних символов
            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
            if (empty($cleanPhone)) {
                continue;
            }

            // Формируем структуру под один номер для пакета
            $sendBatch[] = [
                'id' => uniqid('sms_', true),
                'to' => $cleanPhone,
                'text' => $text,
                'sender' => $sender
            ];

            // Если набралось 200 номеров — отправляем пакет и очищаем массив
            if (count($sendBatch) === $batchSize) {
                $this->sendSmsPackage($sendBatch, $apiKey);
                $sendBatch = []; // Сброс пакета
            }
        }

        // Отправляем остаток (если номеров было меньше 50)
        if (!empty($sendBatch)) {
            $this->sendSmsPackage($sendBatch, $apiKey);
        }
    }

    /**
     * Отправка пакета СМС через JSON API SMSPilot
     */
    private function sendSmsPackage(array $sendBatch, string $apiKey): void
    {
        try {
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl('https://smspilot.ru/api2.php')
                ->setFormat(Client::FORMAT_JSON)
                ->setData([
                    'send' => $sendBatch,
                    'apikey' => $apiKey,
                    'format' => 'json'
                ])
                ->send();

            if (!$response->isOk) {
                Yii::error("[HTTP SMS] Ошибка отправки пакета. Код ответа: {$response->statusCode}", 'sms-pilot');
                return;
            }

            $result = $response->data;

            if (isset($result['error'])) {
                $errDesc = $result['error']['description'] ?? 'Неизвестная ошибка шлюза';
                Yii::error("[SMS Pilot API] Ошибка в ответе шлюза: {$errDesc}", 'sms-pilot');
                return;
            }

            if (isset($result['send'])) {
                $rawResponse = Json::encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                // Логируем успешную отправку вместе с содержимым ответа
                Yii::warning("[SMS] Пакет из " . count($sendBatch) . " СМС успешно отправлен. Ответ шлюза:\n" . $rawResponse, 'sms-pilot');
            }

        } catch (\Throwable $e) {
            Yii::error("[SMS Exception] Критическая ошибка при отправке пакета СМС: " . $e->getMessage(), 'sms-pilot');
        }
    }
}
