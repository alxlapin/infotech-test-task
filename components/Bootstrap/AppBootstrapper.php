<?php

declare(strict_types=1);

namespace app\components\Bootstrap;

use app\components\Books\Events\NewBookArrivalEventHandler;
use app\components\Messenger\SmsMessenger;
use app\components\Messenger\SmsPilotMessenger;
use app\models\BookCatalog\Book;
use GuzzleHttp\Client;
use Override;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;

class AppBootstrapper implements BootstrapInterface
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function bootstrap($app): void
    {
        Yii::setAlias('@bookCoversPath', '@webroot/uploads/book_catalog/covers');

        Yii::$container->setSingleton(SmsMessenger::class, static function () {
            return new SmsPilotMessenger(
                new Client(['base_uri' => 'https://smspilot.ru/api.php/']),
                getenv('SMSPILOT_API_KEY'),
            );
        });

        Event::on(Book::class, Book::EVENT_AFTER_FULL_INSERT, static function (Event $event) {
            Yii::createObject(NewBookArrivalEventHandler::class)->handle($event);
        });
    }
}