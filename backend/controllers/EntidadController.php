<?php

namespace backend\controllers;

use app\models\Entidad;
use app\models\DocProfesor;
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
                'callback' => []
            ],
            'verbs' => [
                'class' => Verbcheck::className(),
                'actions' => [
                    'index' => ['GET', 'POST'],
                    'create' => ['POST'],
                    'update' => ['PUT'],
                    'view' => ['GET'],
                    'view-detalles' => ['GET'],
                    'profesor-evaluations' => ['GET'],
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
        $detalles = $model->getDetalleEntidads()
            ->select(['{{detalle_entidad}}.*', 'Idioma'])
            ->leftJoin('idioma', '`detalle_entidad`.`IdIdioma` = `idioma`.`IdIdioma`')
            ->asArray(true);
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

    protected function findProfesorModel($id)
    {
        if (($model = DocProfesor::findOne($id)) !== null) {
            return $model;
        } else {
            Yii::$app->api->sendFailedResponse("El Registro requerido no existe");
        }
    }

    public  function actionProfesorEvaluations($idprofesor, $estado)
    {
        $model = $this->findProfesorModel($idprofesor);
        $grupos = $model->getGrupos()
            ->select([
                '{{entidad}}.*', "CONCAT(est.PrimerNombre, ' ', IFNULL(est.SegundoNombre, ''), 
                ' ', est.ApellidoPaterno, ' ', est.ApellidoMaterno) AS Estudiante", 'Grupo',
                'TipoEntidad'/*, 'Entidad', 'Idioma'*/
            ])
            //->distinct('entidad.IdEntidad')
            ->leftJoin('doc_estudiante', '`doc_grupo`.`IdGrupo` = `doc_estudiante`.`IdGrupo`')
            ->leftJoin('adm_persona AS est', '`doc_estudiante`.`IdPersona` = `est`.`IdPersona`')
            ->leftJoin('entidad', '`doc_estudiante`.`IdEstudiante` = `entidad`.`IdEstudiante`')
            ->leftJoin('tipo_entidad', '`entidad`.`IdTipoEntidad` = `tipo_entidad`.`IdTipoEntidad`')
            //->leftJoin('detalle_entidad', '`entidad`.`IdEntidad` = `detalle_entidad`.`IdEntidad`')
            //->leftJoin('idioma', '`detalle_entidad`.`IdIdioma` = `idioma`.`IdIdioma`')
            ->andFilterWhere(['entidad.Estado' => $estado])
            ->asArray(true);
        $additional_info = [
            'page' => 'No Define',
            'size' => 'No Define',
            'totalCount' => (int) $grupos->count()
        ];

        $response = [
            'data' => $grupos->all(),
            'info' => $additional_info
        ];
        Yii::$app->api->sendSuccessResponse($response['data'], $response['info']);
    }
}
