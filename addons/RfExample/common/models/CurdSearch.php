<?php
namespace addons\RfExample\common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class CurdSearch
 * @package addons\RfExample\common\models
 */
class CurdSearch extends Curd
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'cate_id', 'manager_id', 'sort', 'position', 'sex', 'views', 'status', 'created_at', 'updated_at'], 'integer'],
            [['content', 'covers', 'files'], 'string'],
            [['price'], 'number'],
            [['start_time', 'end_time'], 'safe'],
            [['title'], 'string', 'max' => 50],
            [['cover', 'attachfile', 'keywords', 'tag'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 60],
            [['provinces', 'city', 'area'], 'integer'],
            [['ip'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Curd::find();
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'cate_id' => $this->cate_id,
            'manager_id' => $this->manager_id,
            'sort' => $this->sort,
            'position' => $this->position,
            'sex' => $this->sex,
            'price' => $this->price,
            'views' => $this->views,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'cover', $this->cover])
            ->andFilterWhere(['like', 'covers', $this->covers])
            ->andFilterWhere(['like', 'attachfile', $this->attachfile])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'provinces', $this->provinces])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}