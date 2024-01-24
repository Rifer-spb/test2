<?php

namespace backend\controllers;

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
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
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

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
