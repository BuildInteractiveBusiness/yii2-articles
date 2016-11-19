<?php

use robot72\components\helpers\MigrationHelper;

/**
 * @author Robert Kuznetsov
 */
class m161117_082658_create_article_items extends MigrationHelper
{
    public $tableName = '{{%article_items}}';

    public function up()
    {
        $this->setTableOptions();
        $length = 11;
        $lengthString = 255;
        $columns = [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'catid' => $this->integer($length),
            'userid' => $this->integer($length),
            'published' => $this->smallInteger(6),
            'introtext' => $this->text(),
            'fulltext' => $this->text(),
            'image' => $this->string($lengthString),
            'image_caption' => $this->text(),
            'image_credits' => $this->string($lengthString),
            'video' => $this->text(),
            'video_caption' => $this->text(),
            'video_credits' => $this->string($lengthString),
            'created' => $this->dateTime(),
            'created_by' => $this->integer($length),
            'modified' => $this->dateTime(),
            'modified_by' => $this->integer($length),
            'access' => $this->integer($length),
            'ordering' => $this->integer($length),
            'hits' => $this->integer($length),
            'alias' => $this->string($lengthString),
            'metadesc' => $this->text(),
            'metakey' => $this->text(),
            'robots' => $this->string(20),
            'author' => $this->string(50),
            'copyright' => $this->string(50),
            'params' => $this->text(),
            'language' => $this->char(7),
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
