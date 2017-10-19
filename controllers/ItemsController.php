<?php

namespace robot72\modules\articles\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\imagine\Image;
use yii\data\ActiveDataProvider;
use vova07\imperavi\actions\GetAction;
use robot72\modules\articles\models\Items;
use robot72\modules\articles\models\Categories;
use robot72\modules\articles\models\SearchItems;
use robot72\modules\articles\models\Authors;

/**
 * ItemsController implements the CRUD actions for Items model.
 *
 * @author Robert Kuznetsov
 */
class ItemsController extends Controller
{
    
    private $folder = 'items/';

    public function getThumbPath()
    {
        return Yii::getAlias('@webroot') . "/" .
                Yii::$app->controller->module->thumbpath .
                $this->folder;
    }

    public function getImagePath()
    {
        return Yii::getAlias('@webroot') . "/" .
                Yii::$app->controller->module->imagepath .
                $this->folder;
    }

    public function getImageUrl()
    {
        return Yii::$app->homeUrl .
                Yii::$app->controller->module->imagepath .
                $this->folder;
    }
    
    public function actions() {
        return [
            'images-get' => [
                'class' => 'vova07\imperavi\actions\GetAction',
                'url' => $this->imageUrl, 
                'path' => $this->imagePath, 
                'type' => GetAction::TYPE_IMAGES,
            ], 
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url' => $this->imageUrl, 
                'path' => $this->imagePath,
            ],
        ];
    }
    
    public function behaviors()
    {
        return [            
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'index', 'delete', 'view', 'deleteimage', 'all', 'news', 'expertopinion', 'basket', 'movetobasket', 'movefrombasket'],
                'rules' => [                    
                    [
                        'actions' => ['view', 'all', 'news', 'expertopinion'],
                        'allow' => TRUE,
                        'roles' => ['?', '@']
                    ],                    
                    [
                        'actions' => ['create', 'update', 'index', 'delete', 'deleteimage', 'basket', 'movetobasket', 'movefrombasket'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],                    
                ],
            ],
        ];
    }

    //------------ Backend Actions
    /**
     * Lists all Items models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchItems();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionBasket()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Items::find()->where(['deleted' => 1]),
        ]);
       
        return $this->render('basket', [            
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Items model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Items([
            'scenario' => 'create',
        ]);
        $model->image = "";
        $model->deleted = Items::NOT_DELETED;

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
        {		
            // Upload Image and Thumb if is not Null
            $imagepath   = $this->imagePath;
            $thumbpath   = $this->thumbPath;
            $imgnametype = Yii::$app->controller->module->imgname;
            $imgname     = $model->title;

            $file = \yii\web\UploadedFile::getInstance($model, 'image');

            // If is set an image, update it
            if(isset($file))
            {
                if ($file->name != "")
                { 
                    $filename = $this->uploadCatImage($file,$imagepath,$thumbpath,$imgname,$imgnametype);
                    $model->image = $filename;	
                }
            }
            // Save changes
            $model->save();	

            Yii::$app->session->setFlash('success', 'Статья сохранена');
            return $this->redirect([
                'index'
            ]);
        } else {
            //Yii::$app->session->setFlash('error', 'Модель данных не может быть сохранена');
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Items model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Upload Image and Thumb if is not Null
            $imagepath   = $this->imagePath;
            $thumbpath   = $this->thumbPath;
            $imgnametype = Yii::$app->controller->module->imgname;
            $imgname     = $model->title;

            $file = \yii\web\UploadedFile::getInstance($model, 'image');

            // If is set an image, update it
            if(isset($file))
            {
                if ($file->name != "")
                { 
                    $filename = $this->uploadCatImage($file,$imagepath,$thumbpath,$imgname,$imgnametype);
                    $model->image = $filename;	
                }
            }
            

            $model->save();	

            Yii::$app->session->setFlash('success', 'Статья сохранена');
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Items model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['basket']);
    }
    
    public function actionDeleteimage($id) 
    {
        $model = $this->findModel($id);

        if ($model->deleteImage()) {
            Yii::$app->session->setFlash('success', 'Изображение успешно удалено. Теперь ты можешь загрузить другое:)');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка, при удалении изображения! Пожалуйста, попробуйте позже или свяжитесь с разработчиком');
        }

        return $this->redirect([
            'update', 'id' => $model->id,
        ]);
    }
    
    public function actionMoveToBasket($id) 
    {
        $m = $this->findModel($id);
        $m->published = 0;
        $m->deleted = Items::DELETED;
        if($m->validate() && $m->save())
        {
            return $this->redirect(['index']);
        } else {
            throw new NotFoundHttpException('Ошибка при перемещении в карзину. Сообщите об ошибке разработчику сайта.');
        }
    }
    
    public function actionMoveFromBasket($id) 
    {
        $m = $this->findModel($id);
        $m->deleted = 0;
        if($m->validate() && $m->save())
        {
            return $this->redirect(['basket']);
        } else {
            throw new NotFoundHttpException('Ошибка при перемещении в карзину. Сообщите об ошибке разработчику сайта.');
        }
    }
    
    //--------------- Frontend Actions:
    /**
     * Displays a single Items model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->layout = '@app/views/layouts/frontend_main';
        $m = $this->findModel($id);
        $m->updateCounters(['hits' => 1]);
        return $this->render('view', [
            'model' => $m,
        ]);
    }
    
    public function actionAll()
    {
        $this->layout = '@app/views/layouts/frontend_main';
        $dataProvider = new ActiveDataProvider([
            'query' => Items::find()->where(['published' => 1]),
            'pagination' => [
                'pageSize' => 7,
            ]
        ]);
        
        $categories = Categories::findAll(['published' => 1]);
        return $this->render('all', [
            'dataProvider' => $dataProvider,
            'categories' => $categories,
        ]);
    }
    
    public function actionExpertOpinion()
    {
        $this->layout = '@app/views/layouts/frontend_main';
        $dataProvider = new ActiveDataProvider([
            //'query' => Items::find()->with(['category' => function($query) { $query->andWhere('branch = 0'); } ])->where(['published' => 1]),
            'query' => Items::find()->joinWith('category')->where(['article_items.published' => 1, 'article_categories.branch' => Categories::BRANCH_EXPERT_OPINION]),
            'pagination' => [
                'pageSize' => 7,
            ]
        ]);
        
        $categories = Categories::findAll(['published' => 1, 'branch' => Categories::BRANCH_EXPERT_OPINION]);
        return $this->render('expert_opinion', [
            'dataProvider' => $dataProvider,
            'categories' => $categories,
        ]);
    }
    
    public function actionExpertOpinionCategory($id)
    {
        $this->layout = '@app/views/layouts/frontend_main';
        $dataProvider = new ActiveDataProvider([            
            'query' => Items::find()->joinWith('category')->where(['article_items.published' => 1, 'article_items.catid' => $id, 'article_categories.branch' => Categories::BRANCH_EXPERT_OPINION]),
            'pagination' => [
                'pageSize' => 7,
            ]
        ]);
        
        $categories = Categories::findAll(['published' => 1, 'branch' => Categories::BRANCH_EXPERT_OPINION]);
        return $this->render('expert_opinion', [
            'dataProvider' => $dataProvider,
            'categories' => $categories,
        ]);
    }
    
    public function actionNews()
    {
        $this->layout = '@app/views/layouts/frontend_main';
        $dataProvider = new ActiveDataProvider([
            //'query' => Items::find()->with(['category' => function($query) { $query->andWhere('branch = 0'); } ])->where(['published' => 1]),
            'query' => Items::find()->joinWith('category')->where(['article_items.published' => 1, 'article_categories.branch' => Categories::BRANCH_NEWS]),
            'pagination' => [
                'pageSize' => 7,
            ]
        ]);
        
        $categories = Categories::findAll(['published' => 1, 'branch' => Categories::BRANCH_NEWS]);
        return $this->render('news', [
            'dataProvider' => $dataProvider,
            'categories' => $categories,
        ]);
    }
    
    public function actionNewsCategory($id)
    {
        $this->layout = '@app/views/layouts/frontend_main';
        $dataProvider = new ActiveDataProvider([            
            'query' => Items::find()->joinWith('category')->where(['article_items.published' => 1, 'article_items.catid' => $id, 'article_categories.branch' => Categories::BRANCH_NEWS]),
            'pagination' => [
                'pageSize' => 7,
            ]
        ]);
        
        $categories = Categories::findAll(['published' => 1, 'branch' => Categories::BRANCH_NEWS]);
        return $this->render('news', [
            'dataProvider' => $dataProvider,
            'categories' => $categories,
        ]);
    }
    
    public function actionBlogs()
    {
        $this->layout = '@app/views/layouts/frontend_main';
        $dataProvider = new ActiveDataProvider([            
            //'query' => Authors::find()->joinWith('items')->where(['article_items.published' => 1, 'article_items.author' => 'article_author.id']),
            'query' => Authors::find(),
            'pagination' => [
                'pageSize' => 7,
            ]
        ]);
        
        $authors = Authors::find()->all();
        return $this->render('blogs', [
            'dataProvider' => $dataProvider,
            'authors' => $authors,
        ]);
    }

    public function actionCategory($id)
    {
        $this->layout = '@app/views/layouts/frontend_main';
        $dataProvider = new ActiveDataProvider([
            'query' => Items::find()->where(['published' => 1, 'catid' => $id]),
            'pagination' => [
                'pageSize' => 7,
            ]
        ]);
        
        $categories = Categories::findAll(['published' => 1]);
        return $this->render('all', [
            'dataProvider' => $dataProvider,
            'categories' => $categories,
        ]);
    }
    
    public function actionAuthor($id)
    {
        $this->layout = '@app/views/layouts/frontend_main';
        $dataProvider = new ActiveDataProvider([
            'query' => Items::find()->where(['published' => 1, 'userid' => $id]),
            'pagination' => [
                'pageSize' => 7,
            ]
        ]);
        
        $author = Authors::findOne($id);
        $authors = Authors::find()->all();
        return $this->render('author', [
            'dataProvider' => $dataProvider,
            'authors' => $authors,
            'author' => $author,
        ]);
    }
    
    //------------------ Provides Methods:
    /**
     * Finds the Items model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Items the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Items::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не существует!!!');
        }
    }
    
    // Upload Image in a select Folder
    protected function uploadCatImage($file,$imagepath,$thumbpath,$imgname,$imgnametype)
    {
        $type = $file->type;
        $type = str_replace("image/","",$type);
        $size = $file->size;

        switch($imgnametype) 
        {
            case "original":
                $name = $this->generateAlias($file->name,"img");
                break;

            case "casual":
                $name = uniqid(rand(), true).".".$type;
                break;

            default:
                $name = $this->generateAlias($imgname,"img").".".$type;
                break;
        }

        // Save the file in the Image Folder
        $path = $imagepath.$name;
        $file->saveAs($path);

        // Save Image Thumb
        Image::thumbnail($imagepath.$name, Yii::$app->controller->module->thumbWidthItem, Yii::$app->controller->module->thumbHeightItem)->save($thumbpath.$name, ['quality' => 50]);	

        return $name;
    }
    
    // Generate URL or IMG alias
    protected function generateAlias($name,$type)
    {
        // remove any '-' from the string they will be used as concatonater
        $str = str_replace('-', ' ', $name);
        $str = str_replace('_', ' ', $name);

        // remove any duplicate whitespace, and ensure all characters are alphanumeric
        if($type == "img") 
        {
            $str = preg_replace(array('/\s+/','/[^A-Za-z0-9\-]/'), array('_',''), $str);
        } else {
            $str = preg_replace(array('/\s+/','/[^A-Za-z0-9\-]/'), array('-',''), $str);
        }

        // lowercase and trim
        $str = trim(strtolower($str));
		
        return $str;
    }
}