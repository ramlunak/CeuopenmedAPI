<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recurso".
 *
 * @property int $IdRecurso
 * @property int $IdIdioma
 * @property int $IdEntidad
 * @property int $Nivel
 * @property string $URL
 * @property bool $IsImage
 * @property string $Descripcion
 *
 * @property Entidad $entidad
 * @property Idioma $idioma
 */
class Recurso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'recurso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdIdioma', 'IdEntidad', 'Nivel', 'URL'], 'required'],
            [['IdIdioma', 'IdEntidad', 'Nivel'], 'integer'],
            [['IsImage'], 'boolean'],
            [['Descripcion'], 'string'],
            [['URL'], 'string', 'max' => 255],
            [['IdEntidad'], 'exist', 'skipOnError' => true, 'targetClass' => Entidad::className(), 'targetAttribute' => ['IdEntidad' => 'IdEntidad']],
            [['IdIdioma'], 'exist', 'skipOnError' => true, 'targetClass' => Idioma::className(), 'targetAttribute' => ['IdIdioma' => 'IdIdioma']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdRecurso' => 'Id Recurso',
            'IdIdioma' => 'Id Idioma',
            'IdEntidad' => 'Id Entidad',
            'Nivel' => 'Nivel',
            'URL' => 'Url',
            'IsImage' => 'Is Image',
            'Descripcion' => 'Descripcion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntidad()
    {
        return $this->hasOne(Entidad::className(), ['IdEntidad' => 'IdEntidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdioma()
    {
        return $this->hasOne(Idioma::className(), ['IdIdioma' => 'IdIdioma']);
    }

    static public function search($params)
    {
        $order = Yii::$app->getRequest()->getQueryParam('order');

        $search = Yii::$app->getRequest()->getQueryParam('search');

        if (isset($search)) {
            $params = $search;
        }


        $query = Recurso::find()
            ->select(['{{recurso}}.*', 'Idioma'])
            ->leftJoin('idioma', '`recurso`.`IdIdioma` = `idioma`.`IdIdioma`')
            ->asArray(true);


        if (isset($params['IdRecurso'])) {
            $query->andFilterWhere(['IdRecurso' => $params['IdRecurso']]);
        }
        if (isset($params['IdIdioma'])) {
            $query->andFilterWhere(['recurso.IdIdioma' => $params['IdIdioma']]);
        }
        if (isset($params['IdEntidad'])) {
            $query->andFilterWhere(['IdEntidad' => $params['IdEntidad']]);
        }
        if (isset($params['Nivel'])) {
            $query->andFilterWhere(['Nivel' => $params['Nivel']]);
        }        
        if (isset($params['URL'])) {
            $query->andFilterWhere(['like', 'URL', $params['URL']]);
        }
        if (isset($params['IsImage'])) {
            $query->andFilterWhere(['IsImage' => $params['IsImage']]);
        }
        if (isset($params['Descripcion'])) {
            $query->andFilterWhere(['like', 'Descripcion', $params['Descripcion']]);
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
