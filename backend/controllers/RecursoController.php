<?php

namespace backend\controllers;

use app\models\Recurso;
use backend\behaviours\Verbcheck;
use backend\behaviours\Apiauth;

use Yii;

class RecursoController extends RestController
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
                    'delete' => ['DELETE']
                ],
            ],

        ];
    }

    public function actionIndex()
    {
        $params = $this->request['search'];
        $response = Recurso::search($params);
        Yii::$app->api->sendSuccessResponse($response['data'], $response['info']);
    }

    public function actionCreate()
    {
        $model = new Recurso;
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
        $persona = $model->getIdioma()->select(["Idioma"])->asArray(true)->one();
        $response = array_merge($model->attributes, $persona);
        Yii::$app->api->sendSuccessResponse($response);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->api->sendSuccessResponse($model->attributes);
    }

    protected function findModel($id)
    {
        if (($model = Recurso::findOne($id)) !== null) {
            return $model;
        } else {
            Yii::$app->api->sendFailedResponse("El Registro requerido no existe");
        }
    }
}