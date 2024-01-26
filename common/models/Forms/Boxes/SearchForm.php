<?php

namespace common\models\Forms\Boxes;

use common\models\Entities\Products\Products;
use common\models\Helper\FileHelper;
use common\models\Helper\Helper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Entities\Boxes\Boxes as BoxesModel;

/**
 * Boxes represents the model behind the search form of `common\models\Entities\Boxes\Boxes`.
 */
class SearchForm extends BoxesModel
{
    private $isForExport;

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
            [['weight', 'width', 'length', 'height'], 'number'],
            [['reference'], 'safe'],
            [['search','date_from','date_to'], 'string'],
        ];
    }

    /**
     * SearchForm constructor.
     * @param bool $isForExport
     * @param array $config
     */
    public function __construct(bool $isForExport = null, $config = []) {
        $this->isForExport = $isForExport;
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
    public function search($params) {

        $query = BoxesModel::find()->alias('b');

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
            'b.id' => $this->id,
            'b.date_created' => $this->date_created,
            'b.weight' => $this->weight,
            'b.width' => $this->width,
            'b.length' => $this->length,
            'b.height' => $this->height,
            'b.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'b.reference', $this->reference]);
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

        return $dataProvider;
    }

    /**
     * @param $params
     * @return array|ActiveDataProvider|null
     */
    public function createExport($params) {

        $query = BoxesModel::find()->alias('b');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'b.id' => $this->id,
            'b.date_created' => $this->date_created,
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

        /** @var BoxesModel[] $models */
        $models = $dataProvider->getModels();

        if(!count($models)) {
            return null;
        }

        $items = [];
        foreach ($models as $model) {
            $items[] = [$model->id, $model->getDateCreatedString(), $model->weight, $model->getStatus()->name];
        }

        $tempPath = \Yii::getAlias('@Web');
        FileHelper::createFolder($tempPath, 0777);
        $filePath = "$tempPath/BoxesExport.csv";
        Helper::createFileCsv($filePath,['id','date created','weight','status'],$items);

        return ['path' => $filePath];
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
