<?php

namespace common\models\Forms\Boxes;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Entities\Products\Products;
use common\models\Entities\Boxes\Boxes as BoxesModel;;

class ExportForm extends BoxesModel
{
    public $date_from;
    public $date_to;
    public $search;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'date_created', 'status'], 'integer'],
            [['search','date_from','date_to'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params) {

        $query = BoxesModel::find()
            ->alias('b');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params,'');

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'b.id' => $this->id,
            'b.status' => $this->status,
        ]);

        $query->andFilterWhere(['between', 'b.date_created', $this->getDateFrom(), $this->getDateTo()]);

        if(!empty($this->search)) {

            $query->joinWith('productRelations p');
            $query->andWhere('(
                (b.id LIKE "%' . $this->search . '%") OR
                (b.reference LIKE "%' . $this->search . '%") OR
                (SELECT title FROM ' . Products::tableName() . ' WHERE id=p.id) LIKE "%' . $this->search . '%" OR
                (SELECT sku FROM ' . Products::tableName() . ' WHERE id=p.id) LIKE "%' . $this->search . '%"
            )');
        }

        var_dump($dataProvider->getModels());exit();

        return $dataProvider;
    }

    /**
     * @return false|int|null
     */
    public function getDateFrom() {
        if(empty($this->date_from)) {
            return null;
        }
        $date = explode('-', $this->date_from);
        return mktime(0,0,0,$date[1],$date[0],$date[2]);
    }

    /**
     * @return false|int|null
     */
    public function getDateTo() {
        if(empty($this->date_to)) {
            return null;
        }
        $date = explode('-', $this->date_to);
        return mktime(0,0,0,$date[1],$date[0],$date[2]);
    }
}
