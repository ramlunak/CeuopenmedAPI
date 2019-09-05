<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_entidad".
 *
 * @property int $IdTipoEntidad
 * @property int $IdIdioma
 * @property int $IdEstudiante
 * @property string $TipoEntidad
 *
 * @property Entidad[] $entidads
 * @property DocEstudiante $estudiante
 * @property Idioma $idioma
 */
class TipoEntidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_entidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdIdioma', 'IdEstudiante', 'TipoEntidad'], 'required'],
            [['IdIdioma', 'IdEstudiante'], 'integer'],
            [['TipoEntidad'], 'string', 'max' => 100],
            [
                ['IdEstudiante'], 'exist', 'skipOnError' => true, 'targetClass' => DocEstudiante::className(),
                'targetAttribute' => ['IdEstudiante' => 'IdEstudiante'], 'message' => 'El estudiante que seleccionó no existe en la Base de Datos del Sistema.'
            ],
            [
                ['IdIdioma'], 'exist', 'skipOnError' => true, 'targetClass' => Idioma::className(),
                'targetAttribute' => ['IdIdioma' => 'IdIdioma'], 'message' => 'El idioma que seleccionó no existe en la Base de Datos del Sistema.'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdTipoEntidad' => 'Id Tipo Entidad',
            'IdIdioma' => 'Id Idioma',
            'IdEstudiante' => 'Id Estudiante',
            'TipoEntidad' => 'Tipo de Entidad',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntidads()
    {
        return $this->hasMany(Entidad::className(), ['IdTipoEntidad' => 'IdTipoEntidad']);
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
    public function getIdioma()
    {
        return $this->hasOne(Idioma::className(), ['IdIdioma' => 'IdIdioma']);
    }

    static public function search($params)
    {
        $order = Yii::$app->getRequest()->getQueryParam('order');

        $search = Yii::$app->getRequest()->getQueryParam('search');

        if (isset($search)) {
            $params = $search;
        }


        $query = TipoEntidad::find()
            ->select(['{{tipo_entidad}}.*', "CONCAT(
                PrimerNombre, ' ', IFNULL(SegundoNombre, ''), ' ', 
                ApellidoPaterno, ' ', ApellidoMaterno) AS NombreCompleto", 'Idioma'])
            ->leftJoin('idioma', '`tipo_entidad`.`IdIdioma` = `idioma`.`IdIdioma`')
            ->leftJoin('doc_estudiante', '`tipo_entidad`.`IdEstudiante` = `doc_estudiante`.`IdEstudiante`')
            ->leftJoin('adm_persona', '`doc_estudiante`.`IdPersona` = `adm_persona`.`IdPersona`')
            ->asArray(true);


        if (isset($params['IdTipoEntidad'])) {
            $query->andFilterWhere(['IdTipoEntidad' => $params['IdTipoEntidad']]);
        }
        if (isset($params['IdIdioma'])) {
            $query->andFilterWhere(['IdIdioma' => $params['IdIdioma']]);
        }
        if (isset($params['IdEstudiante'])) {
            $query->andFilterWhere(['IdEstudiante' => $params['IdEstudiante']]);
        }
        if (isset($params['TipoEntidad'])) {
            $query->andFilterWhere(['like', 'TipoEntidad', $params['TipoEntidad']]);
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