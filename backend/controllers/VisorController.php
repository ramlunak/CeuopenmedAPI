<?php

namespace backend\controllers;
use app\models\Entidad;
use app\models\AdmTraducciones;
use app\models\TipoEntidad;
use backend\behaviours\Verbcheck;
use backend\behaviours\Apiauth;

use Yii;

class VisorController extends RestController
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
                    'entidad' => ['GET'],                  
                    'traducciones' => ['GET']                   
                ],
            ],

        ];
    }

    public function actionIndex()
    {
        $params = $this->request['search'];
        $response = TipoEntidad::search($params);
        Yii::$app->api->sendSuccessResponse($response['data'], $response['info']);
    }

    public function actionEntidad()
    {
        $params = $this->request['search'];
        $response = Entidad::search($params);
        Yii::$app->api->sendSuccessResponse($response['data'], $response['info']);
    }

    public function actionTraducciones()
    {
        $params = $this->request['search'];
        $response = AdmTraducciones::search($params);
        Yii::$app->api->sendSuccessResponse($response['data'], $response['info']);
    }

}