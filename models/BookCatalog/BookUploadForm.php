<?php

declare(strict_types=1);

namespace app\models\BookCatalog;

use Throwable;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class BookUploadForm extends Model
{
    public ?UploadedFile $bookCoverImg = null;

    public function __construct(public readonly Book $book, array $config = [])
    {
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['bookCoverImg'],
                'image',
                'mimeTypes' => ['image/jpeg', 'image/png'],
                'skipOnEmpty' => !$this->book->isNewRecord
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'bookCoverImg' => 'Фото обложки'
        ];
    }

    public function upload(array $data): bool
    {
        $this->bookCoverImg = UploadedFile::getInstance($this, 'bookCoverImg');

        $this->book->load($data);

        if ($this->validate() && $this->book->validate()) {
            if ($this->bookCoverImg === null) {
                if ($this->book->save()) {
                    return true;
                }
            } else {
                $imgUploadPath = $this->buildUploadPath();

                if ($this->bookCoverImg->saveAs($imgUploadPath)) {
                    $this->book->cover_photo_path = $imgUploadPath;
                    try {
                        if ($this->book->save()) {
                            return true;
                        }

                        FileHelper::unlink(Yii::getAlias($imgUploadPath));
                    } catch (Throwable) {
                        FileHelper::unlink(Yii::getAlias($imgUploadPath));
                    }
                }
            }
        }

        return false;
    }

    private function buildUploadPath(): string
    {
        return '@bookCoversPath/' . md5(uniqid($this->bookCoverImg->baseName, true)) . '.' . $this->bookCoverImg->extension;
    }
}