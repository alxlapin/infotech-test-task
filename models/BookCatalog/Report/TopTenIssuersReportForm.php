<?php

declare(strict_types=1);

namespace app\models\BookCatalog\Report;

use app\models\BookCatalog\Book;
use yii\base\Model;

class TopTenIssuersReportForm extends Model
{
    public int $year = 2024;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            [['year'], 'required'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels(): array
    {
        return [
            'year' => 'Год издания',
            'fullname' => 'ФИО',
            'booksCount' => 'Кол-во изданных книг',
        ];
    }

    /**
     * @return array<int, array{fullname: string, booksCount: int}>
     */
    public function buildReportData(): array
    {
        return Book::find()
            ->select(['authors.fullname', 'COUNT(*) as booksCount'])
            ->joinWith('authors')
            ->where(['books.publish_year' => $this->year])
            ->groupBy('authors.fullname')
            ->orderBy('booksCount DESC')
            ->limit(10)
            ->asArray()
            ->all();
    }
}