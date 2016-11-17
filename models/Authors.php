<?php

namespace robot72\modules\articles\models;

use Yii;

/**
 * This is the model class for table "article_author".
 *
 * @author Robert Kuznetsov
 *
 * @property integer $id
 * @property string $fullname
 */
class Authors extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_author';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fullname'], 'required'],
            [['fullname', 'image'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fullname' => 'Имя автора',
            'image' => 'Изображение',
        ];
    }
    
    public function getItems()
    {
        return $this->hasMany(Items::className(), ['userid' => 'id']);
    }
    
    // Delete Image From Category
    public function deleteImage() {
        $image = Yii::getAlias('@webroot')."/".Yii::$app->controller->module->imagepath.$this->image;

        if (unlink($image)) {
            $this->image = "";
            $this->save();
            return true;
        }

        return false;
    }
}
