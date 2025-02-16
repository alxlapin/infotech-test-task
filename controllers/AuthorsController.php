<?php

declare(strict_types=1);

namespace app\controllers;

use app\components\Author\AuthorSubscriptionService;
use app\models\BookCatalog\Author;
use app\models\BookCatalog\AuthorSearch;
use app\models\BookCatalog\AuthorSubscribeForm;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AuthorsController implements the CRUD actions for Author model.
 */
class AuthorsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'permissions' => ['viewAuthors'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'permissions' => ['viewAuthors'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'permissions' => ['createAuthor'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'permissions' => ['updateAuthor'],
                    ],
                    [
                        'actions' => ['subscribe'],
                        'allow' => true,
                        'permissions' => ['subscribeToAuthor'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Author models.
     */
    public function actionIndex(): string
    {
        $searchModel = new AuthorSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', compact('searchModel', 'dataProvider'));
    }

    /**
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate(): Response|string
    {
        $model = new Author();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact('model'));
    }

    /**
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Exception
     */
    public function actionUpdate($id): Response|string
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', compact('model'));
    }

    /**
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSubscribe(int $id): Response|string
    {
        $form = new AuthorSubscribeForm(
            $this->findModel($id),
            Yii::createObject(AuthorSubscriptionService::class)
        );

        if (Yii::$app->request->isPost && $form->load($this->request->post()) && $form->subscribe()) {
            Yii::$app->session->setFlash(
                'success',
                "Подписка на новые книги от автора {$form->getAuthorFullname()} оформлена на номер $form->phone"
            );

            return $this->redirect(['index']);
        }

        return $this->render('subscribe', ['model' => $form]);
    }

    /**
     * @return Author the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Author
    {
        if (($model = Author::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
