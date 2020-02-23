<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "adm_traducciones".
 *
 * @property int $IdTraduccion
 * @property string $Tabla
 * @property int $IdTabla
 * @property int $IdIdioma
 * @property string $Traduccion
 */
class AdmTraducciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'adm_traducciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Tabla', 'IdTabla', 'Traduccion'], 'required'],
            [['IdTabla','IdIdioma'], 'integer'],
            [['Tabla', 'Traduccion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdTraduccion' => 'Id Traduccion',
            'Tabla' => 'Tabla',
            'IdTabla' => 'Id Tabla',
            'Traduccion' => 'Traduccion',
        ];
    }

    
    static public function search($params)
    {
        $order = Yii::$app->getRequest()->getQueryParam('order');

        $search = Yii::$app->getRequest()->getQueryParam('search');

        if (isset($search)) {
            $params = $search;
        }

        $query = AdmTraducciones::find()
            ->select(['IdTraduccion', 'adm_traducciones.IdIdioma','idioma.Idioma', 'IdTabla', 'Tabla', 'Traduccion','Idioma'])
            ->leftJoin('idioma', '`adm_traducciones`.`IdIdioma` = `idioma`.`IdIdioma`')
            ->asArray(true);


        if (isset($params['IdTraduccion'])) {
            $query->andFilterWhere(['IdTraduccion' => $params['IdTraduccion']]);
        }
        if (isset($params['IdTabla'])) {
            $query->andFilterWhere(['like', 'IdTabla', $params['IdTabla']]);
        } if (isset($params['IdIdioma'])) {
            $query->andFilterWhere(['like', 'IdIdioma', $params['IdIdioma']]);
        }
        if (isset($params['Tabla'])) {
            $query->andFilterWhere(['like', 'Tabla', $params['Tabla']]);
        }
        if (isset($params['Traduccion'])) {
            $query->andFilterWhere(['like', 'Traduccion', $params['Traduccion']]);
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
