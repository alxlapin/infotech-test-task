<?php

declare(strict_types=1);

namespace app\components\Books\Events;

use app\common\Contact\Phone;
use app\components\Messenger\SmsMessenger;
use app\models\BookCatalog\AuthorSubscription;
use app\models\BookCatalog\Book;
use yii\base\Event;

/**
 * Рассылка смс-уведомлений о поступлении новой книги.
 *
 * P.S. лучшим сценарием будет использование асинхронной обработки (с использованием брокера сообщений),
 * т.к. подписчиков может быть много и не хочется отнимать ценное время рантайма.
 */
readonly class NewBookArrivalEventHandler
{
    public function __construct(private SmsMessenger $messenger)
    {
    }

    public function handle(Event $event): void
    {
        /** @var Book $book */
        $book = $event->sender;

        $authors = $book->getAuthors()->select('id')->column();

        $phonesQuery = AuthorSubscription::find()
            ->select('phone_number')
            ->where(['author_id' => $authors])
            ->groupBy('phone_number')
            ->orderBy('phone_number')
            ->asArray();

        foreach ($phonesQuery->batch() as $phones) {
            $phones = array_map(static fn(array $row) => Phone::create($row['phone_number']), $phones);

            $message = "Вышла новая книга $book->name";

            if (count($phones) > 1) {
                $this->messenger->sendBatch($phones, $message);
            } else {
                $this->messenger->send($phones[0], $message);
            }
        }
    }
}