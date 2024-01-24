<?php

namespace common\models\Entities\Boxes;

class Box
{
    public $title;
    public $reference;
    public $status;
    public $weight;
    public $width;
    public $length;
    public $height;

    /**
     * Box constructor.
     * @param string $title
     * @param float $weight
     * @param float $width
     * @param float $length
     * @param float $height
     * @param string $reference
     * @param int $status
     */
    public function __construct(string $title, float $weight, float $width, float $length, float $height, string $reference, int $status = null)
    {
        $this->title = $title;
        $this->weight = $weight;
        $this->width = $width;
        $this->length = $length;
        $this->height = $height;
        $this->reference = $reference;
        $this->status = $status;
    }
}