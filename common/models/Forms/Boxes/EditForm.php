<?php

namespace common\models\Forms\Boxes;

use common\models\Entities\Boxes\Boxes;
use yii\base\Model;

/**
 * Class AddForm
 * @package common\models\Forms\Boxes
 *
 * @property string $title
 * @property string $reference
 * @property int $status
 * @property float $weight
 * @property float $width
 * @property float $length
 * @property float $height
 */
class EditForm extends Model
{
    public $title;
    public $reference;
    public $status = 0;
    public $weight = 0;
    public $width = 0;
    public $length = 0;
    public $height = 0;

    /**
     * EditForm constructor.
     * @param Boxes $box
     * @param array $config
     */
    public function __construct(Boxes $box, $config = []) {
        $this->title = $box->title;
        $this->reference = $box->reference;
        $this->status = $box->status;
        $this->weight = $box->weight;
        $this->width = $box->width;
        $this->length = $box->length;
        $this->height = $box->height;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reference','title'], 'required'],
            [['status'], 'integer'],
            [['weight', 'width', 'length', 'height'], 'number'],
            [['reference','title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'weight' => 'Weight, kg',
            'width' => 'Width, cm',
            'length' => 'Length, cm',
            'height' => 'Height, cm',
            'reference' => 'Reference',
            'status' => 'Status',
        ];
    }
}