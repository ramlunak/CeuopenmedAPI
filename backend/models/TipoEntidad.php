<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_entidad".
 *
 * @property int $IdTipoEntidad
 * @property int $IdIdioma
 * @property int $IdEstudiante
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
            [['IdIdioma', 'IdEstudiante'], 'required'],
            [['IdIdioma', 'IdEstudiante'], 'integer'],
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
            ->select(['IdTipoEntidad', 'IdIdioma', 'IdEstudiante'])
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
