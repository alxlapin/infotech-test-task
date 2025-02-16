<?php

declare(strict_types=1);

namespace app\components\Author;

use app\common\Contact\Phone;
use app\models\BookCatalog\AuthorSubscription;
use Yii;

/**
 * Управление подпиской на новые книги автора.
 */
class AuthorSubscriptionService
{
    public function subscribe(int $id, Phone $phone): void
    {
        Yii::$app->db
            ->createCommand()
            ->upsert(
                AuthorSubscription::tableName(),
                ['phone_number' => $phone->getE164Format(), 'author_id' => $id],
                false
            )
            ->execute();
    }
}