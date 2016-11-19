<?php

use robot72\components\helpers\MigrationHelper;

/**
 * @author Robert Kuznetsov
 */
class m161117_082717_create_article_categories extends MigrationHelper
{
    public $tableName = '{{%article_categories}}';

    public function up()
    {
        $this->setTableOptions();
        $length = 11;
        $lengthString = 255;
        $columns = [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'alias' => $this->string($lengthString),
            'description' => $this->text(),
            'parent' => $this->integer($length),
            'userid' => $this->integer($length),
            'published' => $this->smallInteger(6),
            'access' => $this->integer($length),
            'ordering' => $this->integer($length),
            'image' => $this->string($lengthString),
            'image_caption' => $this->text(),
            'image_credits' => $this->string($lengthString),
            'params' => $this->text(),
            'metadesc' => $this->text(),
            'metakey' => $this->text(),
            'robots' => $this->string(20),
            'author' => $this->string(50),
            'copyright' => $this->string(50),
            'language' => $this->char(7),
            'branch' => $this->integer(3),
            'created' => $this->dateTime(),
            'created_by' => $this->integer($length),
            'modified' => $this->dateTime(),
            'modified_by' => $this->integer($length),
            'hits' => $this->integer($length),
            'deleted' => $this->boolean(),
        ];
        $this->createTable($this->tableName, $columns, $this->tableOptions);
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
