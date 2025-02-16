<?php

declare(strict_types=1);

namespace app\models\BookCatalog;

use app\common\Contact\Phone;
use app\components\Author\AuthorSubscriptionService;
use yii\base\Model;

class AuthorSubscribeForm extends Model
{
    public string $phone = '';

    public function __construct(
        private readonly Author $author,
        private readonly AuthorSubscriptionService $authorSubscriptionService,
        array $config = []
    ) {
        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            [['phone'], 'required'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels(): array
    {
        return [
            'phone' => 'Телефон для отправки уведомлений',
        ];
    }

    public function getAuthorFullname(): string
    {
        return $this->author->fullname;
    }

    public function getAuthorId(): int
    {
        return $this->author->id;
    }

    public function subscribe(): true
    {
        $this->authorSubscriptionService->subscribe($this->author->id, Phone::create($this->phone));

        return true;
    }
}