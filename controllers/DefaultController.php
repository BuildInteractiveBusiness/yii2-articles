<?php

namespace robot72\modules\articles\controllers;

use Yii;
use yii\web\Controller;

/**
 * @author Robert Kuznetsov
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [            
            'access' => Yii::$app->controller->module->accessOption,
        ];
    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }
}
