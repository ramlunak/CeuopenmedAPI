<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "doc_estudiante".
 *
 * @property int $IdEstudiante
 * @property int $IdPersona
 * @property int $IdGrupo
 *
 * @property AdmPersona $persona
 * @property DocGrupo $grupo
 */
class DocEstudiante extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doc_estudiante';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdPersona', 'IdGrupo'], 'required'],
            [['IdPersona', 'IdGrupo'], 'integer'],
            [['IdPersona'], 'exist', 'skipOnError' => true, 'targetClass' => AdmPersona::className(), 'targetAttribute' => ['IdPersona' => 'IdPersona']],
            [['IdGrupo'], 'exist', 'skipOnError' => true, 'targetClass' => DocGrupo::className(), 'targetAttribute' => ['IdGrupo' => 'IdGrupo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdEstudiante' => 'Id Estudiante',
            'IdPersona' => 'Id Persona',
            'IdGrupo' => 'Id Grupo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersona()
    {
        return $this->hasOne(AdmPersona::className(), ['IdPersona' => 'IdPersona']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(DocGrupo::className(), ['IdGrupo' => 'IdGrupo']);
    }

    static public function search($params)
    {        
        $order = Yii::$app->getRequest()->getQueryParam('order');

        $search = Yii::$app->getRequest()->getQueryParam('search');

        if(isset($search)){
            $params=$search;
        }
        
        $query = DocEstudiante::find()
            ->select(['IdEstudiante', 'IdPersona', 'IdGrupo'])
            ->asArray(true);            

        if(isset($params['IdEstudiante'])) {
            $query->andFilterWhere(['IdEstudiante' => $params['IdEstudiante']]);
        }
        if(isset($params['IdPersona'])) {
            $query->andFilterWhere(['IdPersona' => $params['IdPersona']]);
        }
        if(isset($params['IdGrupo'])) {
            $query->andFilterWhere(['IdGrupo' => $params['IdGrupo']]);
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
