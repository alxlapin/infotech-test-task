<?php

declare(strict_types=1);

namespace app\models\BookCatalog;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "author_subscriptions".
 *
 * @property int $id
 * @property string $phone_number
 * @property int $author_id
 *
 * @property Author $author
 */
class AuthorSubscription extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'author_subscriptions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['phone_number', 'author_id'], 'required'],
            [['author_id'], 'integer'],
            [['phone_number'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
