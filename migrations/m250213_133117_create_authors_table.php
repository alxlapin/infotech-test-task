<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%authors}}`.
 */
class m250213_133117_create_authors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%authors}}', [
            'id' => $this->primaryKey(),
            'fullname' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);

        $this->createTable('{{%books_authors}}', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('ux_books_authors', '{{%books_authors}}', ['book_id', 'author_id'], true);

        $this->addForeignKey(
            'fk_rel_to_books',
            '{{%books_authors}}',
            'book_id',
            '{{%books}}',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_rel_to_authors',
            '{{%books_authors}}',
            'author_id',
            '{{%authors}}',
            'id',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%books_authors}}');
        $this->dropTable('{{%authors}}');
    }
}
