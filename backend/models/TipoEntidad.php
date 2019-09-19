<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_entidad".
 *
 * @property int $IdTipoEntidad
 * @property int $IdIdioma
 * @property string $TipoEntidad
 *
 * @property Entidad[] $entidads
 * @property TipoAsociacion[] $tipoAsociacions
 * @property TipoAsociacion[] $tipoAsociacions0
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
            [['IdIdioma', 'TipoEntidad'], 'required'],
            [['IdIdioma'], 'integer'],
            [['TipoEntidad'], 'string', 'max' => 100],
            [['TipoEntidad'], 'unique'],     
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
            'TipoEntidad' => 'Tipo Entidad',
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
    public function getTipoAsociacions()
    {
        return $this->hasMany(TipoAsociacion::className(), ['IdTipoEntidad1' => 'IdTipoEntidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoAsociacions0()
    {
        return $this->hasMany(TipoAsociacion::className(), ['IdTipoEntidad2' => 'IdTipoEntidad']);
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
            ->select(['{{tipo_entidad}}.*', 'Idioma'])
            ->leftJoin('idioma', '`tipo_entidad`.`IdIdioma` = `idioma`.`IdIdioma`')
            ->asArray(true);


        if (isset($params['IdTipoEntidad'])) {
            $query->andFilterWhere(['IdTipoEntidad' => $params['IdTipoEntidad']]);
        }
        if (isset($params['IdIdioma'])) {
            $query->andFilterWhere(['tipo_entidad.IdIdioma' => $params['IdIdioma']]);
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
