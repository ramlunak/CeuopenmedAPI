<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "seg_usuario".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $IdRol
 * @property int $IdPersona
 *
 * @property AdmPersona $persona
 * @property SegRol $rol
 */
class SegUsuario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'seg_usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'created_at', 'updated_at', 'IdRol', 'IdPersona'], 'required'],
            [['status', 'created_at', 'updated_at', 'IdRol', 'IdPersona'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['IdPersona'], 'exist', 'skipOnError' => true, 'targetClass' => AdmPersona::className(), 'targetAttribute' => ['IdPersona' => 'IdPersona']],
            [['IdRol'], 'exist', 'skipOnError' => true, 'targetClass' => SegRol::className(), 'targetAttribute' => ['IdRol' => 'IdRol']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'IdRol' => 'Id Rol',
            'IdPersona' => 'Id Persona',
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
    public function getRol()
    {
        return $this->hasOne(SegRol::className(), ['IdRol' => 'IdRol']);
    }

    static public function search($params)
    {
        $order = Yii::$app->getRequest()->getQueryParam('order');

        $search = Yii::$app->getRequest()->getQueryParam('search');

        if (isset($search)) {
            $params = $search;
        }


        $query = SegUsuario::find()
            ->select([
                'seg_usuario.id', 'seg_usuario.username', 'seg_usuario.email', 'seg_usuario.status',
                'seg_usuario.created_at', 'seg_usuario.updated_at', 'seg_usuario.IdRol', 'seg_usuario.IdPersona',
                'Rol', "CONCAT(
                PrimerNombre, ' ', IFNULL(SegundoNombre, ''), ' ', 
                ApellidoPaterno, ' ', ApellidoMaterno) AS NombreCompleto"
            ])
            ->leftJoin('seg_rol', '`seg_usuario`.`IdRol` = `seg_rol`.`IdRol`')
            ->leftJoin('adm_persona', '`seg_usuario`.`IdPersona` = `adm_persona`.`IdPersona`')
            ->asArray(true);


        if (isset($params['id'])) {
            $query->andFilterWhere(['id' => $params['id']]);
        }
        if (isset($params['username'])) {
            $query->andFilterWhere(['like', 'username', $params['username']]);
        }
        if (isset($params['email'])) {
            $query->andFilterWhere(['like', 'email', $params['email']]);
        }
        if (isset($params['status'])) {
            $query->andFilterWhere(['status' => $params['status']]);
        }
        if (isset($params['created_at'])) {
            $query->andFilterWhere(['created_at' => $params['created_at']]);
        }
        if (isset($params['updated_at'])) {
            $query->andFilterWhere(['updated_at' => $params['updated_at']]);
        }
        if (isset($params['IdRol'])) {
            $query->andFilterWhere(['IdRol' => $params['IdRol']]);
        }
        if (isset($params['IdPersona'])) {
            $query->andFilterWhere(['IdPersona' => $params['IdPersona']]);
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
