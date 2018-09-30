<?php
namespace addons\RfExample\common\models;

use Yii;
use yii\base\Model;

/**
 * Class CurdSearch
 * @package addons\RfExample\common\models
 */
class CurdSearch extends Curd
{
    public function init()
    {
        !$this->stat_time && $this->stat_time = date('Y-m-d', strtotime("-60 day"));
        !$this->end_time && $this->end_time  = date('Y-m-d', strtotime("+1 day"));

        parent::init();
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
     * @return \yii\db\ActiveQuery
     */
    public function search($params)
    {
        $query = Curd::find();
        $this->attributes = $params;

        $query->andFilterWhere([
            'title' => $this->title,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['between','created_at', strtotime($this->stat_time), strtotime($this->end_time)]);

        return $query;
    }
}