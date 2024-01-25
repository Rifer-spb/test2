<?php

namespace backend\controllers\boxes;

use common\models\Entities\Products\Products;
use common\models\Forms\Boxes\ChangeStatusForm;
use common\models\Forms\Boxes\ChangeWeightForm;
use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use common\models\Entities\Boxes\Boxes;
use common\models\Forms\Boxes\SearchForm as SearchBoxesForm;
use common\models\Forms\Boxes\AddForm;
use common\models\Forms\Boxes\EditForm;
use common\models\UseCases\BoxesService;
use common\models\ReadModels\BoxesReadRepository;
use common\models\Forms\Boxes\Products\SearchForm as SearchProductsForm;
use yii\widgets\ActiveForm;

/**
 * BoxesController implements the CRUD actions for Boxes model.
 */
class DefaultController extends Controller
{
    private $service;
    private $boxesReadRepository;

    public function __construct(
        $id,
        $module,
        BoxesService $service,
        BoxesReadRepository $boxesReadRepository,
        $config = []
    ) {
        $this->service = $service;
        $this->boxesReadRepository = $boxesReadRepository;
        parent::__construct($id, $module, $config);
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
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
     * @return string
     */
    public function actionIndex() {

        $searchModel = new SearchBoxesForm();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'statuses' => $this->boxesReadRepository->findStatusArray()
        ]);

    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id) {

        $box = $this->findModel($id);

        $searchBoxesForm = new SearchProductsForm($box->id);
        $dataProductProvider = $searchBoxesForm->search($this->request->queryParams);

        return $this->render('view', [
            'box' => $box,
            'searchBoxesForm' => $searchBoxesForm,
            'dataProductProvider' => $dataProductProvider,
            'statuses' => $this->boxesReadRepository->findStatusArray()
        ]);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionCreate() {

        $form = new AddForm();

        if($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $model = $this->service->add($form);
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $form,
            'statuses' => $this->boxesReadRepository->findStatusArray()
        ]);

    }

    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id) {

        $model = $this->findModel($id);

        $form = new EditForm($model);
        if($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($model->id, $form);
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'statuses' => $this->boxesReadRepository->findStatusArray(),
            'id' => $model->id
        ]);

    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionDelete($id) {

        $model = $this->findModel($id);

        try {
            $this->service->drop($model->id);
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionChangeStatus($id) {

        $model = $this->findModel($id);

        $form = new ChangeStatusForm();
        if($form->load(Yii::$app->request->post(),'') && $form->validate()) {
            try {
                $this->service->changeStatus($model->id, $form);
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->redirect(Yii::$app->request->referrer);

    }

    /**
     * @param $id
     * @return array|Response
     * @throws NotFoundHttpException
     */
    public function actionChangeWeight($id) {

        $model = $this->findModel($id);

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $form = new ChangeWeightForm();
        if($form->load(Yii::$app->request->post(),'')) {
            if(!$form->validate()) {
                return ['errorValidate' => ActiveForm::validate($form)];
            }
            if($model->isStatusAtWarehouse() and !$form->value) {
                return $this->redirect(Yii::$app->request->referrer);
            }
            try {
                $this->service->changeWeight($model->id, $form);
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->redirect(Yii::$app->request->referrer);

    }

    /**
     * @return Boxes|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id) {
        if (!$model = $this->boxesReadRepository->findBox($id)) {
            throw new NotFoundHttpException('Page not found');
        }
        return $model;
    }
}
