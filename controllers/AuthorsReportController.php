<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\BookCatalog\Report\TopTenIssuersReportForm;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

class AuthorsReportController extends Controller
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
                        'actions' => ['top-ten-issuers'],
                        'allow' => true,
                        'permissions' => ['viewTopTenIssuersReport'],
                    ]
                ]
            ]
        ];
    }

    public function actionTopTenIssuers(): string
    {
        $model = new TopTenIssuersReportForm();

        $dataProvider = new ArrayDataProvider([]);
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $dataProvider->allModels = $model->buildReportData();
            }
        }

        return $this->render('top-ten-issuers', compact('dataProvider', 'model'));
    }
}