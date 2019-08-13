<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asociacion".
 *
 * @property int $IdAsociacion
 * @property int $IdEntidad1
 * @property int $IdEntidad2
 * @property int $IdTipoAsociacion
 * @property int $IdEstudiante
 * @property int $IdProfesor
 * @property int $Evaluacion
 * @property int $Estado
 *
 * @property DocEstudiante $estudiante
 * @property DocProfesor $profesor
 * @property Entidad $entidad1
 * @property Entidad $entidad2
 * @property TipoAsociacion $tipoAsociacion
 */
class Asociacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asociacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdEntidad1', 'IdEntidad2', 'IdTipoAsociacion', 'IdEstudiante', 'IdProfesor'], 'required'],
            [['IdEntidad1', 'IdEntidad2', 'IdTipoAsociacion', 'IdEstudiante', 'IdProfesor', 'Evaluacion', 'Estado'], 'integer'],
            [
                ['IdEstudiante'], 'exist', 'skipOnError' => true, 'targetClass' => DocEstudiante::className(),
                'targetAttribute' => ['IdEstudiante' => 'IdEstudiante'], 'message' => 'El estudiante que seleccionó no existe en la Base de Datos del Sistema.'
            ],
            [
                ['IdProfesor'], 'exist', 'skipOnError' => true, 'targetClass' => DocProfesor::className(),
                'targetAttribute' => ['IdProfesor' => 'IdProfesor'], 'message' => 'El profesor que seleccionó no existe en la Base de Datos del Sistema.'
            ],
            [
                ['IdEntidad1'], 'exist', 'skipOnError' => true, 'targetClass' => Entidad::className(),
                'targetAttribute' => ['IdEntidad1' => 'IdEntidad'], 'message' => 'La entidad que seleccionó no existe en la Base de Datos del Sistema.'
            ],
            [
                ['IdEntidad2'], 'exist', 'skipOnError' => true, 'targetClass' => Entidad::className(),
                'targetAttribute' => ['IdEntidad2' => 'IdEntidad'], 'message' => 'La entidad que seleccionó no existe en la Base de Datos del Sistema.'
            ],
            [
                ['IdTipoAsociacion'], 'exist', 'skipOnError' => true, 'targetClass' => TipoAsociacion::className(),
                'targetAttribute' => ['IdTipoAsociacion' => 'IdTipoAsociacion'], 'message' => 'El tipo asociación que seleccionó no existe en la Base de Datos del Sistema.'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdAsociacion' => 'Id Asociacion',
            'IdEntidad1' => 'Id Entidad1',
            'IdEntidad2' => 'Id Entidad2',
            'IdTipoAsociacion' => 'Id Tipo Asociacion',
            'IdEstudiante' => 'Id Estudiante',
            'IdProfesor' => 'Id Profesor',
            'Evaluacion' => 'Evaluacion',
            'Estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstudiante()
    {
        return $this->hasOne(DocEstudiante::className(), ['IdEstudiante' => 'IdEstudiante']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesor()
    {
        return $this->hasOne(DocProfesor::className(), ['IdProfesor' => 'IdProfesor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntidad1()
    {
        return $this->hasOne(Entidad::className(), ['IdEntidad' => 'IdEntidad1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntidad2()
    {
        return $this->hasOne(Entidad::className(), ['IdEntidad' => 'IdEntidad2']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoAsociacion()
    {
        return $this->hasOne(TipoAsociacion::className(), ['IdTipoAsociacion' => 'IdTipoAsociacion']);
    }

    static public function search($params)
    {
        $order = Yii::$app->getRequest()->getQueryParam('order');

        $search = Yii::$app->getRequest()->getQueryParam('search');

        if (isset($search)) {
            $params = $search;
        }


        $query = Asociacion::find()
            ->select(['IdAsociacion', 'IdEntidad1', 'IdEntidad2', 'IdTipoAsociacion', 'IdEstudiante', 'IdProfesor', 'Evaluacion', 'Estado'])
            ->asArray(true);


        if (isset($params['IdAsociacion'])) {
            $query->andFilterWhere(['IdAsociacion' => $params['IdAsociacion']]);
        }
        if (isset($params['IdEntidad1'])) {
            $query->andFilterWhere(['IdEntidad1' => $params['IdEntidad1']]);
        }
        if (isset($params['IdEntidad2'])) {
            $query->andFilterWhere(['IdEntidad2' => $params['IdEntidad2']]);
        }
        if (isset($params['IdTipoAsociacion'])) {
            $query->andFilterWhere(['IdTipoAsociacion' => $params['IdTipoAsociacion']]);
        }
        if (isset($params['IdEstudiante'])) {
            $query->andFilterWhere(['IdEstudiante' => $params['IdEstudiante']]);
        }
        if (isset($params['IdProfesor'])) {
            $query->andFilterWhere(['IdProfesor' => $params['IdProfesor']]);
        }        
        if (isset($params['Evaluacion'])) {
            $query->andFilterWhere(['Evaluacion' => $params['Evaluacion']]);
        }
        if (isset($params['Estado'])) {
            $query->andFilterWhere(['Estado' => $params['Estado']]);
        }


        if (isset($order)) {
            $query->orderBy($order);
        }

        $additional_info = [
            'page' => 'No Define',
            'size' => 'No Define',
            'totalCount' => (int) $query->count()
        ];

        return [
            'data' => $query->all(),
            'info' => $additional_info
        ];
    }
}
