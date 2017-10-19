<?php

namespace robot72\modules\articles\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use robot72\modules\sitemap\behaviors\SitemapBehavior;

/**
 * This is the model class for table "article_categories".
 *
 * @author Robert Kuznetsov
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property string $description
 * @property integer $parent
 * @property integer $published
 * @property integer $access
 * @property integer $ordering
 * @property string $image
 * @property string $image_caption
 * @property string $image_credits
 * @property string $params
 * @property string $metadesc
 * @property string $metakey
 * @property string $robots
 * @property string $author
 * @property string $copyright
 * @property string $language
 */
class Categories extends ActiveRecord
{
    const BRANCH_EXPERT_OPINION = 0;
    const BRANCH_NEWS = 1;
    
    const PUBLISHED = 1;
    const NOT_PUBLISHED = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_categories';
    }
    
    public function behaviors() {
        return [
            'sitemap' => [
                'class' => SitemapBehavior::className(),
                'scope' => function ($model) {
                    /** @var \yii\db\ActiveQuery $model */
                    $model->select(['id']);
                    $model->andWhere(['published' => 1]);
                },
                'dataClosure' => function ($model) {
                    /** @var self $model */
                    return [
                        'loc' => Url::to('/articles/items/category?id='. $model->id, true),
                        'lastmod' => '2015-06-12T11:27:07+03:00',
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
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description', 'image_caption', 'params', 'metadesc', 'metakey'], 'string'],
            [['parent', 'published', 'access', 'ordering' ,'branch'], 'integer'],
            [['name', 'alias', 'image', 'image_credits'], 'string', 'max' => 255],
            [['robots'], 'string', 'max' => 20],
            [['author', 'copyright'], 'string', 'max' => 50],
            [['language'], 'string', 'max' => 7]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование категории',
            'alias' => 'Алиас',
            'description' => 'Описание',
            'parent' => 'Parent',
            'published' => 'Опубликовано',
            'access' => 'Access',
            'ordering' => 'Сортировка',
            'image' => 'Изображение',
            'image_caption' => 'Описание изображения',
            'image_credits' => 'Image Credits',
            'params' => 'Порядок!',
            'metadesc' => 'Metadesc',
            'metakey' => 'Metakey',
            'robots' => 'Robots',
            'author' => 'Автор',
            'copyright' => 'Copyright',
            'language' => 'Language',
            'branch' => 'Раздел (Ветка)',
        ];
    }
    
    public function getBranchNames() 
    {
        $brancheNames = [
            'Статьи',
            'Объявления'
        ];
        
        return $brancheNames;
    }
    
    public function getItem()
    {
        return $this->hasMany("Items", ['catid' => 'id']);
    }
}
