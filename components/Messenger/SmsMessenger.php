<?php

declare(strict_types=1);

namespace app\components\Messenger;

use app\common\Contact\Phone;

interface SmsMessenger
{
    public function send(Phone $phone, string $message): void;

    /**
     * @param Phone[] $phones
     */
    public function sendBatch(array $phones, string $message): void;
}