<?php

namespace backend\controllers;

use common\models\Forms\Boxes\EditForm;
use Yii;
use common\models\UseCases\BoxesService;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use common\models\Entities\Boxes\Boxes;
use common\models\Forms\Boxes\SearchForm;
use common\models\Forms\Boxes\AddForm;
use common\models\ReadModels\BoxesReadRepository;
use yii\web\Response;

/**
 * BoxesController implements the CRUD actions for Boxes model.
 */
class BoxesController extends Controller
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

        $searchModel = new SearchForm();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

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
