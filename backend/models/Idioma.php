<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "idioma".
 *
 * @property int $IdIdioma
 * @property string $Idioma
 *
 * @property Entidad[] $entidads
 * @property TipoEntidad[] $tipoEntidads
 */
class Idioma extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'idioma';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Idioma'], 'required'],
            [['Idioma'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdIdioma' => 'Id Idioma',
            'Idioma' => 'Idioma',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntidads()
    {
        return $this->hasMany(Entidad::className(), ['IdIdioma' => 'IdIdioma']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoEntidads()
    {
        return $this->hasMany(TipoEntidad::className(), ['IdIdioma' => 'IdIdioma']);
    }

    static public function search($params)
    {
        $order = Yii::$app->getRequest()->getQueryParam('order');

        $search = Yii::$app->getRequest()->getQueryParam('search');

        if (isset($search)) {
            $params = $search;
        }


        $query = Idioma::find()
            ->select(['IdIdioma', 'Idioma'])
            ->asArray(true);


        if (isset($params['IdIdioma'])) {
            $query->andFilterWhere(['IdIdioma' => $params['IdIdioma']]);
        }
        if (isset($params['Idioma'])) {
            $query->andFilterWhere(['like', 'Idioma', $params['Idioma']]);
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
