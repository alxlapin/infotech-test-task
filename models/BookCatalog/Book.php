<?php

declare(strict_types=1);

namespace app\models\BookCatalog;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string $name
 * @property int $publish_year
 * @property string $description
 * @property string $isbn
 * @property string $cover_photo_path
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BookAuthorRelation[] $booksAuthors
 */
class Book extends ActiveRecord
{
    /**
     * Расширенное событие добавления новой записи,
     * срабатывающее после всех доп. действий: сохранение связей авторов и книги и т.д.
     */
    public const string EVENT_AFTER_FULL_INSERT = 'EVENT_AFTER_FULL_INSERT';

    /**
     * @var array изспользуется для загрузки id авторов из формы, чтобы не путать с виртуальной связью [[Authors]].
     */
    private array $authorsId = [];

    /**
     * @inheritDoc
     */
    public function save($runValidation = true, $attributeNames = null): bool
    {
        return static::getDb()->transaction(function () use ($runValidation, $attributeNames) {
            return parent::save($runValidation, $attributeNames);
        });
    }

    /**
     * @inheritDoc
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        BookAuthorRelation::deleteAll(['book_id' => $this->id]);

        $rows = array_map(fn($authorId) => [$this->id, $authorId], $this->authorsId);

        static::getDb()->createCommand()
            ->batchInsert(BookAuthorRelation::tableName(), ['book_id', 'author_id'], $rows)
            ->execute();

        if ($insert) {
            $this->trigger(self::EVENT_AFTER_FULL_INSERT);
        } elseif (array_key_exists('cover_photo_path', $changedAttributes)) {
            FileHelper::unlink(Yii::getAlias($changedAttributes['cover_photo_path']));
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(): bool
    {
        if (parent::delete()) {
            FileHelper::unlink(Yii::getAlias($this->cover_photo_path));
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'publish_year', 'description', 'isbn'], 'required'],
            [['name', '!cover_photo_path'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['isbn'], 'string', 'length' => 13],
            [['isbn'], 'unique'],
            [['publish_year'], 'integer', 'min' => 1],
            [['authorsId'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'publish_year' => 'Год выпуска',
            'description' => 'Описание',
            'isbn' => 'Isbn',
            'cover_photo_path' => 'Фото обложки',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
            'authorsId' => 'Авторы',
        ];
    }

    public function setAuthorsId(array $authorsId): void
    {
        $this->authorsId = $authorsId;
    }

    public function getAuthorsId(): array
    {
        if ($this->authorsId === []) {
            $this->authorsId = $this->getAuthors()->select('id')->column();
        }

        return $this->authorsId;
    }

    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])->via('booksAuthors');
    }

    public function getBooksAuthors(): ActiveQuery
    {
        return $this->hasMany(BookAuthorRelation::class, ['book_id' => 'id']);
    }

}
