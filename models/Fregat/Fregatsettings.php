<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "fregatsettings".
 *
 * @property integer $fregatsettings_id
 * @property string $fregatsettings_recoverysend_emailtheme
 * @property string $fregatsettings_recoverysend_emailfrom
 * @property string $fregatsettings_glavvrach_name
 * @property string $fregatsettings_uchrezh_namesokr
 * @property string $fregatsettings_uchrezh_name
 */
class Fregatsettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fregatsettings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fregatsettings_recoverysend_emailtheme', 'fregatsettings_glavvrach_name', 'fregatsettings_uchrezh_namesokr', 'fregatsettings_uchrezh_name'], 'string', 'max' => 255],
            [['fregatsettings_recoverysend_emailfrom'],'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fregatsettings_id' => 'Fregatsettings ID',
            'fregatsettings_recoverysend_emailtheme' => 'Тема электронного письма',
            'fregatsettings_recoverysend_emailfrom' => 'Электронная почта, от которой отправляется письмо',
            'fregatsettings_glavvrach_name' => 'ФИО Главного врача',
            'fregatsettings_uchrezh_namesokr' => 'Сокращенное наименование учреждения',
            'fregatsettings_uchrezh_name' => 'Полное наименование учреждения',
        ];
    }

    public function getShortGlavvrachName()
    {
        return preg_replace('/^(\w+)\s(\w)(\w+)?(\s(\w)(\w+)?)?/iu', '$1 $2. $5.', $this->fregatsettings_glavvrach_name);
    }
}
