<?php

namespace common\models\UseCases;

use common\models\Forms\Boxes\Products\AddForm;
use common\models\Forms\Boxes\Products\EditForm;
use common\models\Repositories\BoxesRepository;
use common\models\Service\TransactionManager;

class ProductsService
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
     * @param int $boxId
     * @param AddForm $form
     * @throws \Exception
     */
    public function add(int $boxId, AddForm $form) {

        $box = $this->boxesRepository->get($boxId);

        $transaction = $this->transactionManager->begin();

        try {

            $box->addProduct(
                $form->title,
                $form->sku,
                $form->shipped_qty,
                $form->received_qty,
                $form->price
            );

            $this->boxesRepository->save($box);

            $transaction->commit();

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

    }

    /**
     * @param int $boxId
     * @param EditForm $form
     */
    public function edit(int $boxId, EditForm $form) {

        $box = $this->boxesRepository->get($boxId);

        $box->editProduct(
            $form->id,
            $form->title,
            $form->sku,
            $form->shipped_qty,
            $form->received_qty,
            $form->price
        );

        $this->boxesRepository->save($box);

    }

    /**
     * @param int $boxId
     * @param int $productId
     */
    public function delete(int $boxId, int $productId) {
        $box = $this->boxesRepository->get($boxId);
        $box->removeProduct($productId);
        $this->boxesRepository->save($box);
    }
}