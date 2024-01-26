<?php

namespace common\models\UseCases;

use common\models\Entities\Boxes\Box;
use common\models\Entities\Boxes\BoxesStatus;
use common\models\Forms\Boxes\AddForm;
use common\models\Entities\Boxes\Boxes;
use common\models\Forms\Boxes\ChangeStatusAllForm;
use common\models\Forms\Boxes\ChangeStatusForm;
use common\models\Forms\Boxes\ChangeWeightForm;
use common\models\Forms\Boxes\EditForm;
use common\models\Repositories\BoxesRepository;
use common\models\Service\TransactionManager;

class BoxesService
{
    private $transactionManager;
    private $boxesRepository;

    public function __construct(
        TransactionManager $transactionManager,
        BoxesRepository $boxesRepository
    ) {
        $this->transactionManager = $transactionManager;
        $this->boxesRepository = $boxesRepository;
    }

    /**
     * @param AddForm $form
     * @return Boxes
     */
    public function add(AddForm $form) : Boxes {

        $model = Boxes::create(
            new Box(
                $form->title,
                $form->weight,
                $form->width,
                $form->length,
                $form->height,
                $form->reference
            )
        );

        $this->boxesRepository->save($model);

        return $model;

    }

    /**
     * @param int $id
     * @param EditForm $form
     */
    public function edit(int $id, EditForm $form) {

        $model = $this->boxesRepository->get($id);

        $model->edit(
            new Box(
                $form->title,
                $form->weight,
                $form->width,
                $form->length,
                $form->height,
                $form->reference,
                $form->status
            )
        );

        $this->boxesRepository->save($model);

    }

    /**
     * @param int $id
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function drop(int $id) {
        $model = $this->boxesRepository->get($id);
        $this->boxesRepository->delete($model);
    }

    /**
     * @param int $id
     * @param ChangeStatusForm $form
     */
    public function changeStatus(int $id, ChangeStatusForm $form) {

        $model = $this->boxesRepository->get($id);

        if($form->status == BoxesStatus::AT_WAREHOUSE) {
            if (!$model->weight || $model->existShippedQtyAndReceivedQtyDistinction()) {
                throw new \DomainException('Status can not be At warehouse');
            }
        }

        $model->setStatus($form->status);

        $this->boxesRepository->save($model);
    }

    /**
     * @param ChangeStatusAllForm $form
     */
    public function changeStatusAll(ChangeStatusAllForm $form) {

        $boxes = $this->boxesRepository->findAllByIds($form->items);

        foreach ($boxes as $box) {
            if($form->status == BoxesStatus::AT_WAREHOUSE) {
                if ($box->weight && !$box->existShippedQtyAndReceivedQtyDistinction()) {
                    $box->setStatus($form->status);
                }
            } else {
                $box->setStatus($form->status);
            }

            $this->boxesRepository->save($box);

        }

    }

    /**
     * @param int $id
     * @param ChangeWeightForm $form
     */
    public function changeWeight(int $id, ChangeWeightForm $form) {
        $model = $this->boxesRepository->get($id);
        $model->setWeight($form->value);
        $this->boxesRepository->save($model);
    }
}