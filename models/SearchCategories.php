<?php

namespace robot72\modules\articles\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use robot72\modules\articles\models\Categories;

/**
 * SearchCategories represents the model behind the search form about `app\modules\articles\models\Categories`.
 *
 * @author Robert Kuznetsov
 */
class SearchCategories extends Categories
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent', 'published', 'access', 'ordering'], 'integer'],
            [['name', 'alias', 'description', 'image', 'image_caption', 'image_credits', 'params', 'metadesc', 'metakey', 'robots', 'author', 'copyright', 'language'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Categories::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'parent' => $this->parent,
            'published' => $this->published,
            'access' => $this->access,
            'ordering' => $this->ordering,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'image_caption', $this->image_caption])
            ->andFilterWhere(['like', 'image_credits', $this->image_credits])
            ->andFilterWhere(['like', 'params', $this->params])
            ->andFilterWhere(['like', 'metadesc', $this->metadesc])
            ->andFilterWhere(['like', 'metakey', $this->metakey])
            ->andFilterWhere(['like', 'robots', $this->robots])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'copyright', $this->copyright])
            ->andFilterWhere(['like', 'language', $this->language]);

        return $dataProvider;
    }
}
