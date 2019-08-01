<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "doc_especialidad".
 *
 * @property int $IdEspecialidad
 * @property string $Especialidad
 *
 * @property DocProfesor[] $docProfesors
 */
class DocEspecialidad extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doc_especialidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Especialidad'], 'required'],
            [['Especialidad'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdEspecialidad' => 'Id Especialidad',
            'Especialidad' => 'Especialidad',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocProfesors()
    {
        return $this->hasMany(DocProfesor::className(), ['IdEspecialidad' => 'IdEspecialidad']);
    }

    static public function search($params)
    {        
        $order = Yii::$app->getRequest()->getQueryParam('order');

        $search = Yii::$app->getRequest()->getQueryParam('search');

        if(isset($search)){
            $params=$search;
        }
        
        $query = DocEspecialidad::find()
            ->select(['IdEspecialidad', 'Especialidad'])
            ->asArray(true);            

        if(isset($params['IdEspecialidad'])) {
            $query->andFilterWhere(['IdEspecialidad' => $params['IdEspecialidad']]);
        }        
        if(isset($params['Especialidad'])) {
            $query->andFilterWhere(['like', 'Especialidad', $params['Especialidad']]);
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
