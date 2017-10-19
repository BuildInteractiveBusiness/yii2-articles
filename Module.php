<?php

namespace robot72\modules\articles;

use Yii;
use yii\filters\AccessControl;

/**
 * Module of the articles.
 *
 * @author Robert Kuznetsov
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $controllerNamespace = 'robot72\modules\articles\controllers';

    /**
     * Alias for view files
     *
     * @var string
     */
    public $viewPath = '@vendor/robot72/yii2-articles/views';

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
    public $imagepath = "upload/images/articles/";

    /**
     * Path for thumb images(small images)
     *
     * @var string
     */
    public $thumbpath = "upload/images/articles/thumbs/";

    /**
     * 
     *
     * @var string
     */
    public $imgname = 'casual';
    
    public $thumbWidthItem = 524;
    
    public $thumbHeightItem = 450;
    
    public $frontendAction = 'main/articles/view';


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
