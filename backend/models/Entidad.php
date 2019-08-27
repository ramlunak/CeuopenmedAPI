<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entidad".
 *
 * @property int $IdEntidad
 * @property int $IdTipoEntidad
 * @property int $IdIdioma
 * @property int $IdEstudiante
 * @property int $IdProfesor
 * @property string $Entidad
 * @property int $Evaluacion
 * @property int $Estado
 *
 * @property Asociacion[] $asociacions
 * @property Asociacion[] $asociacions0
 * @property DocEstudiante $estudiante
 * @property DocProfesor $profesor
 * @property Idioma $idioma
 * @property TipoEntidad $tipoEntidad
 * @property TipoAsociacion[] $tipoAsociacions
 * @property TipoAsociacion[] $tipoAsociacions0
 */
class Entidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdTipoEntidad', 'IdIdioma', 'IdEstudiante', 'IdProfesor', 'Entidad'], 'required'],
            [['IdTipoEntidad', 'IdIdioma', 'IdEstudiante', 'IdProfesor', 'Evaluacion', 'Estado'], 'integer'],
            [['Entidad'], 'string', 'max' => 255],
            [
                ['IdEstudiante'], 'exist', 'skipOnError' => true, 'targetClass' => DocEstudiante::className(),
                'targetAttribute' => ['IdEstudiante' => 'IdEstudiante'], 'message' => 'El estudiante que seleccionó no existe en la Base de Datos del Sistema.'
            ],
            /*[
                ['IdProfesor'], 'exist', 'skipOnError' => true, 'targetClass' => DocProfesor::className(),
                'targetAttribute' => ['IdProfesor' => 'IdProfesor'], 'message' => 'El profesor que seleccionó no existe en la Base de Datos del Sistema.'
            ],*/
            [
                ['IdIdioma'], 'exist', 'skipOnError' => true, 'targetClass' => Idioma::className(),
                'targetAttribute' => ['IdIdioma' => 'IdIdioma'], 'message' => 'El idioma que seleccionó no existe en la Base de Datos del Sistema.'
            ],
            [
                ['IdTipoEntidad'], 'exist', 'skipOnError' => true, 'targetClass' => TipoEntidad::className(),
                'targetAttribute' => ['IdTipoEntidad' => 'IdTipoEntidad'], 'message' => 'El tipo entidad que seleccionó no existe en la Base de Datos del Sistema.'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdEntidad' => 'Id Entidad',
            'IdTipoEntidad' => 'Id Tipo Entidad',
            'IdIdioma' => 'Id Idioma',
            'IdEstudiante' => 'Id Estudiante',
            'IdProfesor' => 'Id Profesor',
            'Entidad' => 'Entidad',
            'Evaluacion' => 'Evaluacion',
            'Estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsociacions()
    {
        return $this->hasMany(Asociacion::className(), ['IdEntidad1' => 'IdEntidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsociacions0()
    {
        return $this->hasMany(Asociacion::className(), ['IdEntidad2' => 'IdEntidad']);
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
    public function getIdioma()
    {
        return $this->hasOne(Idioma::className(), ['IdIdioma' => 'IdIdioma']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoEntidad()
    {
        return $this->hasOne(TipoEntidad::className(), ['IdTipoEntidad' => 'IdTipoEntidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoAsociacions()
    {
        return $this->hasMany(TipoAsociacion::className(), ['IdEntidad1' => 'IdEntidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoAsociacions0()
    {
        return $this->hasMany(TipoAsociacion::className(), ['IdEntidad2' => 'IdEntidad']);
    }

    static public function search($params)
    {
        $order = Yii::$app->getRequest()->getQueryParam('order');

        $search = Yii::$app->getRequest()->getQueryParam('search');

        if (isset($search)) {
            $params = $search;
        }


        $query = Entidad::find()
            ->select([
                '{{entidad}}.*', "CONCAT(est.PrimerNombre, ' ', IFNULL(est.SegundoNombre, ''), 
                ' ', est.ApellidoPaterno, ' ', est.ApellidoMaterno) AS Estudiante",
                "CONCAT(prof.PrimerNombre, ' ', IFNULL(prof.SegundoNombre, ''), 
                ' ', prof.ApellidoPaterno, ' ', prof.ApellidoMaterno) AS Profesor",
                'TipoEntidad',
                'Idioma'
            ])
            ->leftJoin('tipo_entidad', '`entidad`.`IdTipoEntidad` = `tipo_entidad`.`IdTipoEntidad`')
            ->leftJoin('idioma', '`entidad`.`IdIdioma` = `idioma`.`IdIdioma`')
            ->leftJoin('doc_estudiante', '`entidad`.`IdEstudiante` = `doc_estudiante`.`IdEstudiante`')
            ->leftJoin('adm_persona AS est', '`doc_estudiante`.`IdPersona` = `est`.`IdPersona`')
            ->leftJoin('doc_profesor', '`entidad`.`IdProfesor` = `doc_profesor`.`IdProfesor`')
            ->leftJoin('adm_persona AS prof', '`doc_profesor`.`IdPersona` = `prof`.`IdPersona`')
            ->asArray(true);


        if (isset($params['IdEntidad'])) {
            $query->andFilterWhere(['IdEntidad' => $params['IdEntidad']]);
        }
        if (isset($params['IdTipoEntidad'])) {
            $query->andFilterWhere(['IdTipoEntidad' => $params['IdTipoEntidad']]);
        }
        if (isset($params['IdIdioma'])) {
            $query->andFilterWhere(['IdIdioma' => $params['IdIdioma']]);
        }
        if (isset($params['IdEstudiante'])) {
            $query->andFilterWhere(['IdEstudiante' => $params['IdEstudiante']]);
        }
        if (isset($params['IdProfesor'])) {
            $query->andFilterWhere(['IdProfesor' => $params['IdProfesor']]);
        }
        if (isset($params['Entidad'])) {
            $query->andFilterWhere(['like', 'Entidad', $params['Entidad']]);
        }
        if (isset($params['Evaluacion'])) {
            $query->andFilterWhere(['Evaluacion' => $params['Evaluacion']]);
        }
        if (isset($params['Estado'])) {
            $query->andFilterWhere(['Estado' => $params['Estado']]);
        }
        if (isset($params['Comentario'])) {
            $query->andFilterWhere(['like', 'Comentario', $params['Comentario']]);
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
