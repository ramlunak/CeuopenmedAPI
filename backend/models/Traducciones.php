<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "AdmTraducciones".
 *
 * @property int $IdTraduccion
 * @property string $Tabla
 * @property string $IdTabla
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
            [['Traduccion'], 'required'],
            [['Traduccion'], 'string'],           
        ]; 
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdTraduccion' => 'Id Traduccion'           
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
            ->select(['IdTraduccion', 'Tabla', 'IdTabla', 'Traduccion'])
            ->asArray(true);

        if (isset($params['IdTraduccion'])) {
            $query->andFilterWhere(['IdTraduccion' => $params['IdTraduccion']]);
        }
        if (isset($params['Tabla'])) {
            $query->andFilterWhere(['like', 'Tabla', $params['Tabla']]);
        }
        if (isset($params['IdTabla'])) {
            $query->andFilterWhere(['like', 'IdTabla', $params['IdTabla']]);
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
