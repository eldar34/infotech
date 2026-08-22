<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'bsVersion' => '5',
    // Параметры SMS Pilot 
    'smsPilotApiKey' => ($_ENV['SMS_KEY'] ?? 'XXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZXXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZ'), // Тестовый API-ключ
    'smsSenderName' => ($_ENV['SMS_INF'] ?? 'INFORM'), // Тестовый отправитель
];
