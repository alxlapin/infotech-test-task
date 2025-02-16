<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\BookCatalog\Author;
use app\models\BookCatalog\Book;
use app\models\BookCatalog\BookSearch;
use app\models\BookCatalog\BookUploadForm;
use Throwable;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BooksController implements the CRUD actions for Book model.
 */
class BooksController extends Controller
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
                        'permissions' => ['viewBooks'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'permissions' => ['viewBooks'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'permissions' => ['createBook'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'permissions' => ['updateBook'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'permissions' => ['deleteBook'],
                    ],
                    [
                        'actions' => ['download-cover'],
                        'allow' => true,
                        'permissions' => ['downloadBookCover'],
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
     * Lists all Book models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $authorsList = ArrayHelper::map(Author::find()->asArray()->all(), 'id', 'name');

        return $this->render('index', compact('searchModel', 'dataProvider', 'authorsList'));
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
     */
    public function actionCreate(): Response|string
    {
        $model = new BookUploadForm(new Book());

        if ($this->request->isPost) {
            if ($model->upload(Yii::$app->request->post())) {
                return $this->redirect(['view', 'id' => $model->book->id]);
            }
        }

        $authorsList = ArrayHelper::map(Author::find()->asArray()->all(), 'id', 'fullname');

        return $this->render('create', [
            'model' => $model,
            'authorsList' => $authorsList,
        ]);
    }

    public function actionUpdate(int $id): Response|string
    {
        $model = new BookUploadForm($this->findModel($id));

        if ($this->request->isPost) {
            if ($model->upload(Yii::$app->request->post())) {
                return $this->redirect(['view', 'id' => $model->book->id]);
            }
        }

        $authorsList = ArrayHelper::map(Author::find()->asArray()->all(), 'id', 'fullname');

        return $this->render('update', [
            'model' => $model,
            'authorsList' => $authorsList,
        ]);
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

    /**
     * @throws NotFoundHttpException
     */
    public function actionDownloadCover(int $id): Response
    {
        return Yii::$app->response->sendFile(Yii::getAlias($this->findModel($id)->cover_photo_path));
    }

    /**
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Book
    {
        if (($model = Book::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
