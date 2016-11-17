<?php

namespace robot72\modules\articles\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\imagine\Image;
use robot72\modules\articles\models\Authors;

/**
 * AuthorsController implements the CRUD actions for Authors model.
 *
 * @author Robert Kuznetsov
 */
class AuthorsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => Yii::$app->controller->module->accessOption,
        ];
    }

    /**
     * Lists all Authors models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Authors::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Authors model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Authors();
        $model->image = "";
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Upload Image and Thumb if is not Null
            $imagepath   = Yii::getAlias('@webroot')."/".Yii::$app->controller->module->imagepath;
            $thumbpath   = Yii::getAlias('@webroot')."/".Yii::$app->controller->module->thumbpath;
            $imgnametype = Yii::$app->controller->module->imgname;
            $imgname     = $model->fullname;

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
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Authors model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Upload Image and Thumb if is not Null
            $imagepath   = Yii::getAlias('@webroot')."/".Yii::$app->controller->module->imagepath;
            $thumbpath   = Yii::getAlias('@webroot')."/".Yii::$app->controller->module->thumbpath;
            $imgnametype = Yii::$app->controller->module->imgname;
            $imgname     = $model->fullname;

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
            
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Authors model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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

    /**
     * Finds the Authors model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Authors the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Authors::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    //------------------ Provides Methods:
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
        Image::thumbnail($imagepath.$name, 120, 120)->save($thumbpath.$name, ['quality' => 50]);	

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
        }
        else 
        {
            $str = preg_replace(array('/\s+/','/[^A-Za-z0-9\-]/'), array('-',''), $str);
        }

        // lowercase and trim
        $str = trim(strtolower($str));
		
        return $str;
    }
}
