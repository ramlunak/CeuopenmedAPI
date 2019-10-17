<?php

namespace backend\controllers;

use app\models\Asociacion;
use app\models\Entidad;
use backend\behaviours\Verbcheck;
use backend\behaviours\Apiauth;

use Yii;

class AsociacionController extends RestController
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
                    'associate-entitys' => ['GET'],
                    'evaluated-associate-entitys' => ['GET'],
                    'delete' => ['DELETE']
                ],
            ],

        ];
    }

    public function actionIndex()
    {
        $params = $this->request['search'];
        $response = Asociacion::search($params);
        Yii::$app->api->sendSuccessResponse($response['data'], $response['info']);
    }

    public function actionCreate()
    {
        $model = new Asociacion;
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

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->api->sendSuccessResponse($model->attributes);
    }

    protected function findModel($id)
    {
        if (($model = Asociacion::findOne($id)) !== null) {
            return $model;
        } else {
            Yii::$app->api->sendFailedResponse("El Registro requerido no existe");
        }
    }

    protected function findEntidadModel($id)
    {
        if (($model = Entidad::findOne($id)) !== null) {
            return $model;
        } else {
            Yii::$app->api->sendFailedResponse("El Registro requerido no existe");
        }
    }

    public  function actionAssociateEntitys($identidad)
    {
        $this->findEntidadModel($identidad);
        $model = (new \yii\db\Query())
            ->select([
                'entidad.IdEntidad',
                'entidad.IdTipoEntidad',
                'entidad.IdEstudiante',
                "CONCAT(est.PrimerNombre, ' ', IFNULL(est.SegundoNombre, ''), 
                ' ', est.ApellidoPaterno, ' ', est.ApellidoMaterno) AS Estudiante",
                '(SELECT Entidad FROM detalle_entidad WHERE Entidad.IdEntidad = detalle_entidad.IdEntidad LIMIT 1) AS Entidad',
                "(SELECT IdIdioma FROM detalle_entidad WHERE Entidad.IdEntidad = detalle_entidad.IdEntidad LIMIT 1) AS DetalleIdEntidad",
                "(SELECT idioma FROM Idioma WHERE IdIdioma = DetalleIdEntidad LIMIT 1) AS Idioma",
                "(SELECT TipoEntidad FROM tipo_entidad WHERE IdTipoEntidad = entidad.IdTipoEntidad LIMIT 1) AS TipoEntidad",
                "(SELECT Estado FROM asociacion WHERE (IdEntidad1 = " . $identidad . " AND IdEntidad2 = entidad.IdEntidad ) OR (IdEntidad2 = " . $identidad . " AND IdEntidad1 = entidad.IdEntidad) LIMIT 1) as Estado",
                "(SELECT Evaluacion FROM asociacion WHERE (IdEntidad1 = " . $identidad . " AND IdEntidad2 = entidad.IdEntidad ) OR (IdEntidad2 = " . $identidad . " AND IdEntidad1 = entidad.IdEntidad) LIMIT 1) as Evaluacion",
                "(SELECT Comentario FROM asociacion WHERE (IdEntidad1 = " . $identidad . " AND IdEntidad2 = entidad.IdEntidad ) OR (IdEntidad2 = " . $identidad . " AND IdEntidad1 = entidad.IdEntidad) LIMIT 1) as Comentario",
                "(SELECT Nivel FROM asociacion WHERE (IdEntidad1 = " . $identidad . " AND IdEntidad2 = entidad.IdEntidad ) OR (IdEntidad2 = " . $identidad . " AND IdEntidad1 = entidad.IdEntidad) LIMIT 1) as Nivel",
                "(SELECT IdProfesor FROM asociacion WHERE (IdEntidad1 = " . $identidad . " AND IdEntidad2 = entidad.IdEntidad ) OR (IdEntidad2 = " . $identidad . " AND IdEntidad1 = entidad.IdEntidad) LIMIT 1) as IdProfesor",
                "(SELECT IdAsociacion FROM asociacion WHERE (IdEntidad1 = " . $identidad . " AND IdEntidad2 = entidad.IdEntidad ) OR (IdEntidad2 = " . $identidad . " AND IdEntidad1 = entidad.IdEntidad) LIMIT 1) as IdAsociacion",
                "(SELECT IdTipoAsociacion FROM asociacion WHERE (IdEntidad1 = " . $identidad . " AND IdEntidad2 = entidad.IdEntidad ) OR (IdEntidad2 = " . $identidad . " AND IdEntidad1 = entidad.IdEntidad) LIMIT 1) as asociacionIdTipoAsociacion",
                "(SELECT IdTipoAsociacion FROM asociacion WHERE (IdEntidad1 = " . $identidad . " AND IdEntidad2 = entidad.IdEntidad ) OR (IdEntidad2 = " . $identidad . " AND IdEntidad1 = entidad.IdEntidad) LIMIT 1) as IdTipoAsociacion",
                "(SELECT TipoAsociacion FROM tipo_asociacion WHERE IdTipoAsociacion = asociacionIdTipoAsociacion) as TipoAsociacion",
                
            ])
            ->distinct('entidad.IdEntidad')
            ->from('doc_profesor_has_doc_grupo,doc_estudiante,entidad,adm_persona AS est,tipo_asociacion')
            ->where('doc_profesor_has_doc_grupo.IdGrupo = doc_estudiante.IdGrupo')
            ->andWhere('entidad.IdEstudiante = doc_estudiante.IdEstudiante')
            ->andWhere('est.IdPersona = doc_estudiante.IdPersona')
            ->andWhere("
                (((tipo_asociacion.IdTipoEntidad1 = (SELECT IdTipoEntidad FROM entidad WHERE IdEntidad = " . $identidad . ") 
                AND tipo_asociacion.IdTipoEntidad2 = entidad.IdTipoEntidad ) 
                OR (tipo_asociacion.IdTipoEntidad2 = (SELECT IdTipoEntidad FROM entidad WHERE IdEntidad = " . $identidad . ") 
                AND tipo_asociacion.IdTipoEntidad1 = entidad.IdTipoEntidad)))
            ");

        $additional_info = [
            'page' => 'No Define',
            'size' => 'No Define',
            'totalCount' => (int) $model->count()
        ];

        $response = [
            'data' => $model->all(),
            'info' => $additional_info
        ];
        Yii::$app->api->sendSuccessResponse($response['data'], $response['info']);
    }

    public  function actionEvaluatedAssociateEntitys($identidad)
    {
        $this->findEntidadModel($identidad);
        $model = (new \yii\db\Query())
            ->select([
                'entidad.IdEntidad',
                'entidad.IdTipoEntidad',
                'entidad.IdEstudiante',
                "CONCAT(est.PrimerNombre, ' ', IFNULL(est.SegundoNombre, ''), 
                ' ', est.ApellidoPaterno, ' ', est.ApellidoMaterno) AS Estudiante",
                '(SELECT Entidad FROM detalle_entidad WHERE Entidad.IdEntidad = detalle_entidad.IdEntidad LIMIT 1) AS Entidad',
                "(SELECT IdIdioma FROM detalle_entidad WHERE Entidad.IdEntidad = detalle_entidad.IdEntidad LIMIT 1) AS DetalleIdEntidad",
                "(SELECT idioma FROM Idioma WHERE IdIdioma = DetalleIdEntidad LIMIT 1) AS Idioma",
                "(SELECT TipoEntidad FROM tipo_entidad WHERE IdTipoEntidad = entidad.IdTipoEntidad LIMIT 1) AS TipoEntidad",
                "(SELECT Estado FROM asociacion WHERE (IdEntidad1 = " . $identidad . " AND IdEntidad2 = entidad.IdEntidad ) OR (IdEntidad2 = " . $identidad . " AND IdEntidad1 = entidad.IdEntidad) LIMIT 1) as Estado",
                "(SELECT Evaluacion FROM asociacion WHERE (IdEntidad1 = " . $identidad . " AND IdEntidad2 = entidad.IdEntidad ) OR (IdEntidad2 = " . $identidad . " AND IdEntidad1 = entidad.IdEntidad) LIMIT 1) as Evaluacion",
                "(SELECT Comentario FROM asociacion WHERE (IdEntidad1 = " . $identidad . " AND IdEntidad2 = entidad.IdEntidad ) OR (IdEntidad2 = " . $identidad . " AND IdEntidad1 = entidad.IdEntidad) LIMIT 1) as Comentario",
                "(SELECT Nivel FROM asociacion WHERE (IdEntidad1 = " . $identidad . " AND IdEntidad2 = entidad.IdEntidad ) OR (IdEntidad2 = " . $identidad . " AND IdEntidad1 = entidad.IdEntidad) LIMIT 1) as Nivel",
                "(SELECT IdProfesor FROM asociacion WHERE (IdEntidad1 = " . $identidad . " AND IdEntidad2 = entidad.IdEntidad ) OR (IdEntidad2 = " . $identidad . " AND IdEntidad1 = entidad.IdEntidad) LIMIT 1) as IdProfesor",
                "(SELECT IdAsociacion FROM asociacion WHERE (IdEntidad1 = " . $identidad . " AND IdEntidad2 = entidad.IdEntidad ) OR (IdEntidad2 = " . $identidad . " AND IdEntidad1 = entidad.IdEntidad) LIMIT 1) as IdAsociacion",
                "(SELECT IdTipoAsociacion FROM asociacion WHERE (IdEntidad1 = " . $identidad . " AND IdEntidad2 = entidad.IdEntidad ) OR (IdEntidad2 = " . $identidad . " AND IdEntidad1 = entidad.IdEntidad) LIMIT 1) as AsociacionIdTipoAsociacion",
                "(SELECT IdTipoAsociacion FROM asociacion WHERE (IdEntidad1 = " . $identidad . " AND IdEntidad2 = entidad.IdEntidad ) OR (IdEntidad2 = " . $identidad . " AND IdEntidad1 = entidad.IdEntidad) LIMIT 1) as IdTipoAsociacion",
                "(SELECT TipoAsociacion FROM tipo_asociacion WHERE IdTipoAsociacion = asociacionIdTipoAsociacion) as TipoAsociacion",

            ])
            ->distinct('entidad.IdEntidad')
            ->from('doc_profesor_has_doc_grupo,doc_estudiante,entidad,adm_persona AS est,tipo_asociacion')
            ->where('doc_profesor_has_doc_grupo.IdGrupo = doc_estudiante.IdGrupo')
            ->andWhere('entidad.IdEstudiante = doc_estudiante.IdEstudiante')
            ->andWhere('est.IdPersona = doc_estudiante.IdPersona')
            ->andWhere("
                (((tipo_asociacion.IdTipoEntidad1 = (SELECT IdTipoEntidad FROM entidad WHERE IdEntidad = " . $identidad . ") 
                AND tipo_asociacion.IdTipoEntidad2 = entidad.IdTipoEntidad ) 
                OR (tipo_asociacion.IdTipoEntidad2 = (SELECT IdTipoEntidad FROM entidad WHERE IdEntidad = " . $identidad . ") 
                AND tipo_asociacion.IdTipoEntidad1 = entidad.IdTipoEntidad)))
            ")
            ->andWhere('entidad.Estado = 1 AND entidad.Evaluacion = 1 AND entidad.IdEntidad != ' . $identidad . '')
            ->andWhere('entidad.Evaluacion = 1')
            ->andWhere("(SELECT IdAsociacion FROM asociacion WHERE (IdEntidad1 = " . $identidad ." AND IdEntidad2 = entidad.IdEntidad ) OR (IdEntidad2 = ". $identidad ." AND IdEntidad1 = entidad.IdEntidad) LIMIT 1) > 0");

        $additional_info = [
            'page' => 'No Define',
            'size' => 'No Define',
            'totalCount' => (int) $model->count()
        ];

        $response = [
            'data' => $model->all(),
            'info' => $additional_info
        ];
        Yii::$app->api->sendSuccessResponse($response['data'], $response['info']);
    }

}
