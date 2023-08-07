<?php

namespace sadi01\bidashboard\controllers;

use sadi01\bidashboard\models\ExternalDataValue;
use Yii;
use sadi01\bidashboard\models\ExternalData;
use sadi01\bidashboard\models\ExternalDataSearch;
use sadi01\bidashboard\traits\AjaxValidationTrait;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ExternalDataController implements the CRUD actions for ExternalData model.
 */
class ExternalDataController extends Controller
{
    use AjaxValidationTrait;

    public $layout = 'bid_main';

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all ExternalData models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ExternalDataSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $dataProviderValues = new ActiveDataProvider([
            'query' => ExternalDataValue::find(),
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);


        if ($this->request->isPjax){
            return $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'dataProviderValues' => $dataProviderValues,
            ]);
        }else{
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'dataProviderValues' => $dataProviderValues,
            ]);
        }
    }

    /**
     * Displays a single ExternalData model.
     * @param int $id 
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $dataProviderValue = ExternalDataValue::find()->where(['external_data_id' => $id]);
        $dataProviderValue = new ActiveDataProvider([
            'query' => $dataProviderValue,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProviderValue' => $dataProviderValue,
        ]);
    }

    /**
     * Creates a new ExternalData model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ExternalData();

        if ($model->load($this->request->post())) {
            if ($model->save()) {
                return $this->asJson([
                    'status' => true,
                    'message' => Yii::t("app", 'Success')
                ]);
            } else {
                return $this->asJson([
                    'status' => false,
                    'message' => Yii::t("app", 'Fail in Save')
                ]);
            }
        }

        $this->performAjaxValidation($model);
        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ExternalData model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id 
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load($this->request->post())) {
            if ($model->save()) {
                return $this->asJson([
                    'status' => true,
                    'message' => Yii::t("app", 'Success')
                ]);
            } else {
                return $this->asJson([
                    'status' => false,
                    'message' => Yii::t("app", 'Fail in Save')
                ]);
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ExternalData model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id 
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ExternalData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id 
     * @return ExternalData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExternalData::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('biDashboard', 'The requested page does not exist.'));
    }
}
