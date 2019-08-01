<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "doc_profesor".
 *
 * @property int $IdProfesor
 * @property int $IdPersona
 * @property int $IdEspecialidad
 *
 * @property AdmPersona $persona
 * @property DocEspecialidad $especialidad
 * @property DocProfesorHasDocGrupo[] $docProfesorHasDocGrupos
 * @property DocGrupo[] $grupos
 */
class DocProfesor extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doc_profesor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdPersona', 'IdEspecialidad'], 'required'],
            [['IdPersona', 'IdEspecialidad'], 'integer'],
            [['IdPersona'], 'exist', 'skipOnError' => true, 'targetClass' => AdmPersona::className(), 'targetAttribute' => ['IdPersona' => 'IdPersona'], 
                            'message' => 'La persona que seleccionó no existe en la Base de Datos del Sistema.'],
            [['IdEspecialidad'], 'exist', 'skipOnError' => true, 'targetClass' => DocEspecialidad::className(), 'targetAttribute' => ['IdEspecialidad' => 'IdEspecialidad'],
                            'message' => 'La especialidad que seleccionó no existe en la Base de Datos del Sistema.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdProfesor' => 'Id Profesor',
            'IdPersona' => 'Id Persona',
            'IdEspecialidad' => 'Id Especialidad',
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
    public function getEspecialidad()
    {
        return $this->hasOne(DocEspecialidad::className(), ['IdEspecialidad' => 'IdEspecialidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocProfesorHasDocGrupos()
    {
        return $this->hasMany(DocProfesorHasDocGrupo::className(), ['IdProfesor' => 'IdProfesor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupos()
    {
        return $this->hasMany(DocGrupo::className(), ['IdGrupo' => 'IdGrupo'])->viaTable('doc_profesor_has_doc_grupo', ['IdProfesor' => 'IdProfesor']);
    }

    static public function search($params)
    {        
        $order = Yii::$app->getRequest()->getQueryParam('order');

        $search = Yii::$app->getRequest()->getQueryParam('search');

        if(isset($search)){
            $params=$search;
        }
        
        $query = DocProfesor::find()
            ->select(['IdEspecialidad', 'Especialidad'])
            ->asArray(true);            

        if(isset($params['IdProfesor'])) {
            $query->andFilterWhere(['IdProfesor' => $params['IdProfesor']]);
        }
        if(isset($params['IdPersona'])) {
            $query->andFilterWhere(['IdPersona' => $params['IdPersona']]);
        }
        if(isset($params['IdEspecialidad'])) {
            $query->andFilterWhere(['IdEspecialidad' => $params['IdEspecialidad']]);
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
