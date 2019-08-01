<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "doc_profesor_has_doc_grupo".
 *
 * @property int $IdProfesor
 * @property int $IdGrupo
 *
 * @property DocGrupo $grupo
 * @property DocProfesor $profesor
 */
class DocProfesorHasDocGrupo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doc_profesor_has_doc_grupo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdProfesor', 'IdGrupo'], 'required'],
            [['IdProfesor', 'IdGrupo'], 'integer'],
            [['IdProfesor', 'IdGrupo'], 'unique', 'targetAttribute' => ['IdProfesor', 'IdGrupo']],
            [['IdGrupo'], 'exist', 'skipOnError' => true, 'targetClass' => DocGrupo::className(), 'targetAttribute' => ['IdGrupo' => 'IdGrupo']],
            [['IdProfesor'], 'exist', 'skipOnError' => true, 'targetClass' => DocProfesor::className(), 'targetAttribute' => ['IdProfesor' => 'IdProfesor']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdProfesor' => 'Id Profesor',
            'IdGrupo' => 'Id Grupo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(DocGrupo::className(), ['IdGrupo' => 'IdGrupo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesor()
    {
        return $this->hasOne(DocProfesor::className(), ['IdProfesor' => 'IdProfesor']);
    }
}
