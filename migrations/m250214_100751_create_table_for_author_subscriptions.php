<?php

use yii\db\Migration;

/**
 * Class m250214_100751_create_table_for_author_subscriptions
 */
class m250214_100751_create_table_for_author_subscriptions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%author_subscriptions}}', [
            'id' => $this->primaryKey(),
            'phone_number' => $this->string()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('ux_author_subscriptions', '{{%author_subscriptions}}', ['phone_number', 'author_id'], true);

        $this->addForeignKey(
            'fk_sub_to_authors',
            '{{%author_subscriptions}}',
            'author_id',
            '{{%authors}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%author_subscriptions}}');
    }
}
