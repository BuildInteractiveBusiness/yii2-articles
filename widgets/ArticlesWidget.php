<?php

namespace robot72\modules\articles\widgets;

use yii\base\Widget;
use robot72\modules\articles\models\Items;

/**
 *
 * @author Robert Kuznetsov
 */
class ArticlesWidget extends Widget
{
    public $model;
    
    public function init() 
    {
        parent::init();
        $this->model = Items::find()->where(['published' => 1])->limit(2)->orderBy('created')->all();
    } 
    
    public function run() 
    {
        parent::run();
        return $this->render('articles_widget', ['models' => $this->model]);
    }
}