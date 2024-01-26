<?php

namespace backend\controllers\boxes;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use common\models\Forms\Boxes\Products\EditForm;
use common\models\Forms\Boxes\Products\AddForm;
use common\models\ReadModels\BoxesReadRepository;
use common\models\UseCases\ProductsService;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller
{
    private $service;
    private $boxesReadRepository;

    public function __construct(
        $id,
        $module,
        ProductsService $service,
        BoxesReadRepository $boxesReadRepository,
        $config = []
    ) {
        $this->service = $service;
        $this->boxesReadRepository = $boxesReadRepository;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array
     */
    public function behaviors() {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * @param $boxId
     * @param $productId
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($boxId,$productId) {

        if(!$box = $this->boxesReadRepository->findBox($boxId)) {
            throw new NotFoundHttpException('Page not found');
        }

        return $this->render('view', [
            'box' => $box,
            'model' => $box->getProduct($productId),
        ]);
    }

    /**
     * @param $boxId
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionAdd($boxId) {

        if(!$box = $this->boxesReadRepository->findBox($boxId)) {
            throw new NotFoundHttpException('Page not found');
        }

        $form = new AddForm();
        if($form->load(\Yii::$app->request->post()) and $form->validate()) {
            try {
                $this->service->add($box->id, $form);
                return $this->redirect(['boxes/view', 'id' => $box->id]);
            } catch(\Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $form,
            'box' => $box
        ]);
    }

    /**
     * @param $boxId
     * @param $productId
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($boxId, $productId) {

        if(!$box = $this->boxesReadRepository->findBox($boxId)) {
            throw new NotFoundHttpException('Page not found');
        }

        if(!$product = $box->findProduct($productId)) {
            throw new NotFoundHttpException('Page not found');
        }

        $form = new EditForm($product);

        if($form->load(\Yii::$app->request->post()) and $form->validate()) {
            try {
                $this->service->edit($box->id, $form);
                return $this->redirect(['boxes/view', 'id' => $box->id]);
            } catch(\Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'box' => $box
        ]);
    }

    /**
     * @param $boxId
     * @param $productId
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($boxId, $productId) {

        if(!$box = $this->boxesReadRepository->findBox($boxId)) {
            throw new NotFoundHttpException('Page not found');
        }

        try {
            $this->service->delete($box->id, $productId);
        } catch(\Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['boxes/index']);
    }
}
