<?php

namespace robot72\modules\articles\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\Url;
use robot72\modules\sitemap\behaviors\SitemapBehavior;
use robot72\modules\urlalias\models\UrlRule;
//use dosamigos\transliterator\TransliteratorHelper;

/**
 * This is the model class for table "article_items".
 *
 * @author Robert Kuznetsov
 *
 * @property integer $id
 * @property string $title
 * @property integer $catid
 * @property integer $userid
 * @property integer $published
 * @property string $introtext
 * @property string $fulltext
 * @property string $image
 * @property string $image_caption
 * @property string $image_credits
 * @property string $video
 * @property string $video_caption
 * @property string $video_credits
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 * @property integer $access
 * @property integer $ordering
 * @property integer $hits
 * @property string $alias
 * @property string $metadesc
 * @property string $metakey
 * @property string $robots
 * @property string $author
 * @property string $copyright
 * @property string $params
 * @property string $language
 */
class Items extends ActiveRecord {

    const DELETED = 1;
    const NOT_DELETED = 0;
    
    public function behaviors() 
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created', 'modified'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['modified'],
                ],
                'value' => function() {
                    return date('Y-m-d H:i:s');
                },
            ],
            'sitemap' => [
                'class' => SitemapBehavior::className(),
                'scope' => function ($model) {
                    /** @var \yii\db\ActiveQuery $model */
                    $model->select(['alias', 'created']);
                    $model->andWhere(['published' => 1]);
                },
                'dataClosure' => function ($model) {
                    /** @var self $model */
                    return [
                        'loc' => Url::to($model->alias, true),
                        'lastmod' => strtotime($model->created),
                        'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                        'priority' => 0.8
                    ];
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName() 
    {
        return 'article_items';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'catid', 'userid',], 'required'],
            [['catid', 'userid', 'published', 'created_by', 'modified_by', 'access', 'ordering', 'hits', 'deleted'], 'integer'],
            [['introtext', 'fulltext', 'image_caption', 'video', 'video_caption', 'metadesc', 'metakey', 'params'], 'string'],
            [['created', 'modified'], 'safe'],
            [['title', 'image', 'image_credits', 'video_credits', 'alias'], 'string', 'max' => 255],
            [['robots'], 'string', 'max' => 20],
            [['author', 'copyright'], 'string', 'max' => 50],
            [['language'], 'string', 'max' => 7],
            [['alias'], 'filter', 'filter' => 'trim'],
            [['alias'], 'filter', 'filter' => function($value) {
            if (empty($value)) {
                return Inflector::slug($this->title);
            } else {
                return Inflector::slug($value);
            }
        }],
            [['alias'], 'unique'],
            [['alias'], 'unique', 'targetClass' => UrlRule::className(), 'targetAttribute' => 'slug', 'on' => ['create']],
                //[['alias'], 'unique', 'targetClass' => \app\modules\services\models\Items::className(), 'targetAttribute' => 'alias'],
                //[['alias'], 'unique', 'targetClass' => \app\modules\analytics\models\Items::className(), 'targetAttribute' => 'alias'],
                //[['alias'], 'required', 'enableClientValidation' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'catid' => 'Категория',
            'userid' => 'Автор',
            'published' => 'Опубликовано',
            'introtext' => 'Вступление',
            'fulltext' => 'Полный текст',
            'image' => 'Изображение',
            'image_caption' => 'Описание изображения',
            'image_credits' => 'Image Credits',
            'video' => 'Video',
            'video_caption' => 'Video Caption',
            'video_credits' => 'Video Credits',
            'created' => 'Самые новые',
            'created_by' => 'Кем создано',
            'modified' => 'Редактировано',
            'modified_by' => 'Кем редактировано',
            'access' => 'Доступ',
            'ordering' => 'Сотрировка',
            'hits' => 'Самые интересные',
            'alias' => 'Алиас',
            'metadesc' => 'Metadesc',
            'metakey' => 'Metakey',
            'robots' => 'Robots',
            'author' => 'Автор',
            'copyright' => 'Копирайт',
            'params' => 'Параметры',
            'language' => 'Язык',
            'deleted' => 'Удалено',
        ];
    }

    /**
     * Delete Image in a Category
     * 
     * @return boolean 
     */
    public function deleteImage() 
    {
        $image = Yii::getAlias('@webroot') . "/" . Yii::$app->controller->module->imagepath . $this->image;

        if (unlink($image)) {
            $this->image = "";
            $this->save();
            return true;
        }

        return false;
    }

    public function beforeSave($insert) 
    {
        parent::beforeSave($insert);
        if ($this->isNewRecord) {
            $alias = new UrlRule();
            $this->saveAlias($alias);
        } else {
            //get Items model from db :)
            $model = self::findOne($this->id);
            //get UrlAlias model with help alias by Items model :)
            $alias = UrlRule::find()->where(['slug' => $model->alias])->one();
            if (isset($alias)) {
                $this->saveAlias($alias);
            }
        }
        return true;
    }

    public function saveAlias($alias) 
    {
        $alias->slug = $this->alias;
        $alias->route = 'articles/items/view';
        $alias->status = 1;
        $alias->redirect = 1;
        $alias->params = serialize(['id' => "$this->id"]);
        if ($alias->validate()) {
            $alias->save();
            //return true;
        }
    }

    public function beforeDelete() 
    {
        parent::beforeDelete();
        //get Items model from db :)
        $model = self::findOne($this->id);
        //get UrlAlias model with help alias by Items model :)
        $alias = UrlRule::find()->where(['slug' => $model->alias])->one();
        if (isset($alias)) {
            $alias->delete();
        }
        return TRUE;
    }

    public function getCategory() 
    {
        return $this->hasOne(Categories::className(), ['id' => 'catid']);
    }

    public function getAuthor() 
    {
        return $this->hasOne(Authors::className(), ['id' => 'userid']);
    }

    public function getCategoryAtBranch($branchId) {
        return $this->hasOne(Categories::className(), ['id' => 'catid'])->where('branch =: branchId', [':branchId' => $branchId]);
    }

}
