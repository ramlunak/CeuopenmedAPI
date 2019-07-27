<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;
use \common\models\User;

/**
 * This is the model class for table "adm_persona".
 *
 * @property int $IdPersona
 * @property string $PrimerNombre
 * @property string $SegundoNombre
 * @property string $ApellidoPaterno
 * @property string $ApellidoMaterno
 *
 * @property SegUsuario[] $segUsuarios
 */
class AdmPersona extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'adm_persona';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['PrimerNombre', 'ApellidoPaterno', 'ApellidoMaterno'], 'required'],
            [['PrimerNombre', 'SegundoNombre', 'ApellidoPaterno', 'ApellidoMaterno'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdPersona' => 'Id Persona',
            'PrimerNombre' => 'Primer Nombre',
            'SegundoNombre' => 'Segundo Nombre',
            'ApellidoPaterno' => 'Apellido Paterno',
            'ApellidoMaterno' => 'Apellido Materno',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSegUsuarios()
    {
        return $this->hasMany(User::className(), ['IdPersona' => 'IdPersona']);
    }

    static public function search($params)
    {

        //$page = Yii::$app->getRequest()->getQueryParam('page');
        //$limit = Yii::$app->getRequest()->getQueryParam('limit');
        $order = Yii::$app->getRequest()->getQueryParam('order');

        $search = Yii::$app->getRequest()->getQueryParam('search');

        if(isset($search)){
            $params=$search;
        }



        //$limit = isset($limit) ? $limit : 10;
        //$page = isset($page) ? $page : 1;


        //$offset = ($page - 1) * $limit;

        $query = AdmPersona::find()
            ->select(['IdPersona', 'PrimerNombre', 'SegundoNombre', 'ApellidoPaterno', 'ApellidoMaterno'])
            ->asArray(true);
            //->limit($limit)
            //->offset($offset);

        if(isset($params['IdPersona'])) {
            $query->andFilterWhere(['IdPersona' => $params['IdPersona']]);
        }        
        if(isset($params['PrimerNombre'])) {
            $query->andFilterWhere(['like', 'PrimerNombre', $params['PrimerNombre']]);
        }
        if(isset($params['SegundoNombre'])) {
            $query->andFilterWhere(['like', 'SegundoNombre', $params['SegundoNombre']]);
        }
        if(isset($params['ApellidoPaterno'])) {
            $query->andFilterWhere(['like', 'ApellidoPaterno', $params['ApellidoPaterno']]);
        }
        if(isset($params['ApellidoMaterno'])) {
            $query->andFilterWhere(['like', 'ApellidoMaterno', $params['ApellidoMaterno']]);
        }

        
        if(isset($order)){
            $query->orderBy($order);
        }


        $additional_info = [
            'page' => 'No Define',
            'size' => 'No Define',
            'totalCount' => (int)$query->count()
        ];

        return [
            'data' => $query->all(),
            'info' => $additional_info
        ];
    }
}
