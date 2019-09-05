<?php

namespace backend\controllers;

use app\models\Entidad;
use backend\behaviours\Verbcheck;
use backend\behaviours\Apiauth;

use Yii;

class EntidadController extends RestController
{

    public function behaviors()
    {

        $behaviors = parent::behaviors();

        return $behaviors + [

           'apiauth' => [
               'class' => Apiauth::className(),
               'exclude' => [],
               'callback'=>[]
           ],            
            'verbs' => [
                'class' => Verbcheck::className(),
                'actions' => [
                    'index' => ['GET', 'POST'],
                    'create' => ['POST'],
                    'update' => ['PUT'],
                    'view' => ['GET'],
                    'view-detalles' => ['GET'],
                    'delete' => ['DELETE']
                ],
            ],

        ];
    }

    public function actionIndex()
    {
        $params = $this->request['search'];
        $response = Entidad::search($params);
        Yii::$app->api->sendSuccessResponse($response['data'], $response['info']);
    }

    public function actionCreate()
    {
        $model = new Entidad;
        $model->attributes = $this->request;

        if ($model->save()) {
            Yii::$app->api->sendSuccessResponse($model->attributes);
        } else {
            Yii::$app->api->sendFailedResponse($model->errors);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->attributes = $this->request;

        if ($model->save()) {
            Yii::$app->api->sendSuccessResponse($model->attributes);
        } else {
            Yii::$app->api->sendFailedResponse($model->errors);
        }
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        Yii::$app->api->sendSuccessResponse($model->attributes);
    }

    public function actionViewDetalles($id)
    {
        $model = $this->findModel($id);
        $detalles = $model->getDetalleEntidads()->asArray(true);
        $additional_info = [
            'page' => 'No Define',
            'size' => 'No Define',
            'totalCount' => (int) $detalles->count()
        ];

        $response = [
            'data' => $detalles->all(),
            'info' => $additional_info
        ];
        Yii::$app->api->sendSuccessResponse($response['data'], $response['info']);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->api->sendSuccessResponse($model->attributes);
    }

    protected function findModel($id)
    {
        if (($model = Entidad::findOne($id)) !== null) {
            return $model;
        } else {
            Yii::$app->api->sendFailedResponse("El Registro requerido no existe");
        }
    }

}