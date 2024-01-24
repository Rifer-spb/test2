<?php

namespace common\models\UseCases;

use common\models\Entities\Boxes\Box;
use common\models\Forms\Boxes\AddForm;
use common\models\Entities\Boxes\Boxes;
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
}