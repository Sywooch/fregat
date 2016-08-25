<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "grupa".
 *
 * @property integer $grupa_id
 * @property string $grupa_name
 *
 * @property Grupavid[] $grupavs
 */
class Grupa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grupa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grupa_name'], 'required'],
            [['grupa_name'], 'string', 'max' => 255],
            [['grupa_name'], 'unique', 'message' => '{attribute} = {value} уже существует'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'grupa_id' => 'Grupa ID',
            'grupa_name' => 'Группа материальной ценности',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdgrupavid()
    {
        return $this->hasMany(Grupavid::className(), ['id_grupa' => 'grupa_id'])->from(['idgrupavid' => Grupavid::tableName()]);
    }
}
