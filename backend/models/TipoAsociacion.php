<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_asociacion".
 *
 * @property int $IdTipoAsociacion
 * @property int $IdEntidad1
 * @property int $IdEntidad2
 * @property string $TipoAsociacion
 *
 * @property Asociacion[] $asociacions
 * @property Entidad $entidad1
 * @property Entidad $entidad2
 */
class TipoAsociacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_asociacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdEntidad1', 'IdEntidad2', 'TipoAsociacion'], 'required'],
            [['IdEntidad1', 'IdEntidad2'], 'integer'],
            [['TipoAsociacion'], 'string', 'max' => 45],
            [
                ['IdEntidad1'], 'exist', 'skipOnError' => true, 'targetClass' => Entidad::className(),
                'targetAttribute' => ['IdEntidad1' => 'IdEntidad'], 'message' => 'La entidad que seleccionó no existe en la Base de Datos del Sistema.'
            ],
            [
                ['IdEntidad2'], 'exist', 'skipOnError' => true, 'targetClass' => Entidad::className(),
                'targetAttribute' => ['IdEntidad2' => 'IdEntidad'], 'message' => 'La entidad que seleccionó no existe en la Base de Datos del Sistema.'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdTipoAsociacion' => 'Id Tipo Asociacion',
            'IdEntidad1' => 'Id Entidad1',
            'IdEntidad2' => 'Id Entidad2',
            'TipoAsociacion' => 'Tipo Asociacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsociacions()
    {
        return $this->hasMany(Asociacion::className(), ['IdTipoAsociacion' => 'IdTipoAsociacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntidad1()
    {
        return $this->hasOne(Entidad::className(), ['IdEntidad' => 'IdEntidad1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntidad2()
    {
        return $this->hasOne(Entidad::className(), ['IdEntidad' => 'IdEntidad2']);
    }

    static public function search($params)
    {
        $order = Yii::$app->getRequest()->getQueryParam('order');

        $search = Yii::$app->getRequest()->getQueryParam('search');

        if (isset($search)) {
            $params = $search;
        }


        $query = TipoAsociacion::find()
            ->select([
                '{{tipo_asociacion}}.*',
                'ent1.Entidad AS Entidad1',
                'ent2.Entidad AS Entidad2'
            ])
            ->leftJoin('entidad AS ent1', '`tipo_asociacion`.`IdEntidad1` = `ent1`.`IdEntidad`')
            ->leftJoin('entidad AS ent2', '`tipo_asociacion`.`IdEntidad2` = `ent2`.`IdEntidad`')
            ->asArray(true);


        if (isset($params['IdTipoAsociacion'])) {
            $query->andFilterWhere(['IdTipoAsociacion' => $params['IdTipoAsociacion']]);
        }
        if (isset($params['IdEntidad1'])) {
            $query->andFilterWhere(['IdEntidad1' => $params['IdEntidad1']]);
        }
        if (isset($params['IdEntidad2'])) {
            $query->andFilterWhere(['IdEntidad2' => $params['IdEntidad2']]);
        }
        if (isset($params['TipoAsociacion'])) {
            $query->andFilterWhere(['like', 'TipoAsociacion', $params['TipoAsociacion']]);
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
