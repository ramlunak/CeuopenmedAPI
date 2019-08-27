<?php

namespace backend\models;

use yii\base\Model;
use common\models\User;
use Yii;


class SegUsuario extends Model
{
    public $id;
    public $password;
    public $email;

    private $_user;


    /**
     * @inheritdoc
     */
   /*public function rules()
    {
        return [
            [['id'], 'required'],

            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            //['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Esta Dirección de Email ya está en uso.'],
            
            ['password', 'string', 'min' => 6],
            ['password', 'string', 'max' => 255],
        ];
    }*/

     /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [            
            'id' => 'Id',
            'password' => 'Password',
            'email' => 'Email'
        ];
    }
    
    public function viewUser()
    {
        if ($this->validate()) {
            return $this->getUser();
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne($this->id);
        }

        return $this->_user;
    }
}
