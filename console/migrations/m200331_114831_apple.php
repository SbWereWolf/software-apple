<?php

use yii\db\Migration;

/**
 * Class m200331_114831_apple
 */
class m200331_114831_apple extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions =
                'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('apple', [
            'id' => $this->primaryKey(),
            'color' => $this->string(50),
            'created_at' => $this->integer(),
            'fell_at' => $this->integer(),
            'status' => $this->string(50)->defaultValue('на дереве'),
            'used_percentage' => $this->decimal(4,2)->defaultValue(0),

        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('apple');
    }

}
