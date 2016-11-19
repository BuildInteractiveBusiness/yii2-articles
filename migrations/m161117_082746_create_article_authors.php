<?php

use robot72\components\helpers\MigrationHelper;

/**
 * @author Robert Kuznetsov
 */
class m161117_082746_create_article_authors extends MigrationHelper
{
    public $tableName = '{{%article_authors}}';

    public function up()
    {
        $this->setTableOptions();
        $length = 255;
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'fullname' => $this->string($length),
            'image' => $this->string($length),
        ], $this->tableOptions);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
