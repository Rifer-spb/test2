<?php

namespace common\models\Forms\Boxes;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Entities\Boxes\Boxes as BoxesModel;

/**
 * Boxes represents the model behind the search form of `common\models\Entities\Boxes\Boxes`.
 */
class SearchForm extends BoxesModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'date_created', 'status'], 'integer'],
            [['weight', 'width', 'length', 'height'], 'number'],
            [['reference'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = BoxesModel::find();

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
            'date_created' => $this->date_created,
            'weight' => $this->weight,
            'width' => $this->width,
            'length' => $this->length,
            'height' => $this->height,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'reference', $this->reference]);

        return $dataProvider;
    }
}
