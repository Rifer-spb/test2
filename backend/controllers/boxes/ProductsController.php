<?php

namespace backend\controllers\boxes;

use common\models\Forms\Boxes\Products\EditForm;
use Yii;
use common\models\Entities\Products\Products;
use common\models\Forms\Boxes\Products\AddForm;
use common\models\ReadModels\BoxesReadRepository;
use common\models\UseCases\ProductsService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

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
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
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
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
}
