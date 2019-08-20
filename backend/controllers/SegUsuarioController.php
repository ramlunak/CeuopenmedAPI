<?php

namespace backend\controllers;

use app\models\SegUsuario;
use backend\behaviours\Verbcheck;
use backend\behaviours\Apiauth;

use Yii;

class SegUsuarioController extends RestController
{

    public function behaviors()
    {

        $behaviors = parent::behaviors();

        return $behaviors + [

            'apiauth' => [
                'class' => Apiauth::className(),
                'exclude' => [],
                'callback' => []
            ],
            'verbs' => [
                'class' => Verbcheck::className(),
                'actions' => [
                    'index' => ['GET', 'POST'],
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
        $response = SegUsuario::search($params);
        Yii::$app->api->sendSuccessResponse($response['data'], $response['info']);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->attributes = $this->request;

        if ($model->save()) {
            $data = $model->attributes;
            unset($data['auth_key']);
            unset($data['password_hash']);
            unset($data['password_reset_token']);
            Yii::$app->api->sendSuccessResponse($data);
        } else {
            Yii::$app->api->sendFailedResponse($model->errors);
        }
    }

    public function actionView($id)
    {
        $data = $this->findModel($id);
        $persona = $data->persona;

        $rol = $data->rol;
        $data = $data->attributes;
        $data['NombreCompleto'] = $persona->PrimerNombre . ' ' . $persona->SegundoNombre . ' ' . $persona->ApellidoPaterno . ' ' . $persona->ApellidoMaterno;
        $data['Rol'] = $rol->Rol;
        unset($data['auth_key']);
        unset($data['password_hash']);
        unset($data['password_reset_token']);
        Yii::$app->api->sendSuccessResponse($data);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        $data = $model->attributes;
        unset($data['auth_key']);
        unset($data['password_hash']);
        unset($data['password_reset_token']);
        Yii::$app->api->sendSuccessResponse($data);
    }

    protected function findModel($id)
    {
        if (($model = SegUsuario::findOne($id)) !== null) {
            return $model;
        } else {
            Yii::$app->api->sendFailedResponse("El Registro requerido no existe");
        }
    }
}
