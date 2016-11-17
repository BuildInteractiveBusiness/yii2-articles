<?php

namespace robot72\modules\articles;

use Yii;
use yii\filters\AccessControl;

/**
 * Module of the articles
 *
 * @author Robert Kuznetsov
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $controllerNamespace = 'app\modules\articles\controllers';

    /**
     * Configuration for
     *
     * @var array
     */
    public $accessOption;

    /**
     * List allowed type file extends
     *
     * @var string
     */
    public $imagetype = "jpg,jpeg,gif,png";

    /**
     * Path for big images
     *
     * @var string
     */
    public $imagepath = "imgage/articles/";

    /**
     * Path for thumb images(small images)
     *
     * @var string
     */
    public $thumbpath = "imgage/articles/thumb/";

    /**
     * 
     *
     * @var string
     */
    public $imgname = 'casual';


    public function init()
    {
        parent::init();

        $this->accessOption = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

    }
}
