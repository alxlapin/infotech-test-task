<?php

declare(strict_types=1);

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Инициализация ролей и прав доступа согласно тех. заданию.
 * Любому зарегистрированному пользователю по умолчанию присваивается роль `user`.
 */
class RbacController extends Controller
{
    public function actionInit(): int
    {
        $auth = Yii::$app->getAuthManager();
        $auth->removeAll();

        $loginPermission = $auth->createPermission('login');
        $loginPermission->description = 'Вход';
        $auth->add($loginPermission);

        $logoutPermission = $auth->createPermission('logout');
        $logoutPermission->description = 'Выход из системы';
        $auth->add($logoutPermission);

        $signUpPermission = $auth->createPermission('sign-up');
        $signUpPermission->description = 'Регистрация';
        $auth->add($signUpPermission);

        $errorPermission = $auth->createPermission('error');
        $errorPermission->description = 'Просмотр ошибки';
        $auth->add($errorPermission);

        $viewBooksPermission = $auth->createPermission('viewBooks');
        $viewBooksPermission->description = 'Просмотр библиотеки';
        $auth->add($viewBooksPermission);

        $createBookPermission = $auth->createPermission('createBook');
        $createBookPermission->description = 'Добавление книги';
        $auth->add($createBookPermission);

        $updateBookPermission = $auth->createPermission('updateBook');
        $updateBookPermission->description = 'Обновление информации о книге';
        $auth->add($updateBookPermission);

        $deleteBookPermission = $auth->createPermission('deleteBook');
        $deleteBookPermission->description = 'Удаление книги из библиотеки';
        $auth->add($deleteBookPermission);

        $downloadBookCoverPermission = $auth->createPermission('downloadBookCover');
        $downloadBookCoverPermission->description = 'Просмотр книжной обложки';
        $auth->add($downloadBookCoverPermission);

        $viewAuthorsPermission = $auth->createPermission('viewAuthors');
        $viewAuthorsPermission->description = 'Просмотр списка авторов';
        $auth->add($viewAuthorsPermission);

        $createAuthorPermission = $auth->createPermission('createAuthor');
        $createAuthorPermission->description = 'Добавление автора';
        $auth->add($createAuthorPermission);

        $updateAuthorPermission = $auth->createPermission('updateAuthor');
        $updateAuthorPermission->description = 'Обновление информации об авторе';
        $auth->add($updateAuthorPermission);

        $deleteAuthorPermission = $auth->createPermission('deleteAuthor');
        $deleteAuthorPermission->description = 'Удаление автора';
        $auth->add($deleteAuthorPermission);

        $subscribeToAuthorPermission = $auth->createPermission('subscribeToAuthor');
        $subscribeToAuthorPermission->description = 'Подписка на новые книги автора';
        $auth->add($subscribeToAuthorPermission);

        $viewTopTenIssuersReportPermission = $auth->createPermission('viewTopTenIssuersReport');
        $viewTopTenIssuersReportPermission->description = 'Просмотр отчета Топ 10 авторов';
        $auth->add($viewTopTenIssuersReportPermission);

        $guest = $auth->createRole('guest');
        $guest->description = 'Неавторизованный пользователь';
        $auth->add($guest);

        $user = $auth->createRole('user');
        $user->description = 'Роль по умолчанию для авторизованных пользователей';
        $auth->add($user);

        $auth->addChild($guest, $viewBooksPermission);
        $auth->addChild($guest, $viewAuthorsPermission);
        $auth->addChild($guest, $signUpPermission);
        $auth->addChild($guest, $errorPermission);
        $auth->addChild($guest, $subscribeToAuthorPermission);
        $auth->addChild($guest, $viewTopTenIssuersReportPermission);
        $auth->addChild($guest, $downloadBookCoverPermission);

        $auth->addChild($user, $guest);
        $auth->addChild($user, $createBookPermission);
        $auth->addChild($user, $updateBookPermission);
        $auth->addChild($user, $deleteBookPermission);
        $auth->addChild($user, $createAuthorPermission);
        $auth->addChild($user, $updateAuthorPermission);
        $auth->addChild($user, $deleteAuthorPermission);
        $auth->addChild($user, $logoutPermission);

        $this->stdout('Done!' . PHP_EOL);

        return ExitCode::OK;
    }
}
