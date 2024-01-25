<?php

namespace common\models\Forms\Boxes\Products;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Entities\Products\Products;

/**
 * ProductsSearch represents the model behind the search form of `common\models\Entities\Products\Products`.
 */
class SearchForm extends Products
{
    private $boxId;
    public $pageSize = 5;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'shipped_qty', 'received_qty'], 'integer'],
            [['title', 'sku'], 'safe'],
            [['price'], 'number'],
        ];
    }

    /**
     * SearchForm constructor.
     * @param int $boxId
     * @param array $config
     */
    public function __construct(int $boxId, $config = []) {
        $this->boxId = $boxId;
        parent::__construct($config);
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
        $query = Products::find();
        $query->alias('p');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pageSize' => $this->pageSize ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('boxProductRelation bp');
        $query->where(['bp.box' => $this->boxId]);

        // grid filtering conditions
        $query->andFilterWhere([
            'p.id' => $this->id,
            'p.shipped_qty' => $this->shipped_qty,
            'p.received_qty' => $this->received_qty,
            'p.price' => $this->price,
        ]);

        $query->andFilterWhere(['like', 'p.title', $this->title]);
        $query->andFilterWhere(['like', 'p.sku', $this->sku]);

        return $dataProvider;
    }
}
