<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "impemployee".
 *
 * @property string $impemployee_id
 * @property integer $id_importemployee
 * @property integer $id_employee
 *
 * @property Employee $idEmployee
 * @property Importemployee $idImportemployee
 */
class Impemployee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'impemployee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_importemployee', 'id_employee'], 'required'],
            [['id_importemployee', 'id_employee'], 'integer'],
            ['id_importemployee', 'unique', 'targetAttribute' => ['id_importemployee', 'id_employee'], 'message' => 'Этот сотрудник уже ассоциирован с данным словосочетанием'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'impemployee_id' => 'Impemployee ID',
            'id_importemployee' => 'Id Importemployee',
            'id_employee' => 'Сотрудник',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdemployee()
    {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_employee'])->from(['idemployee' => Employee::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdimportemployee()
    {
        return $this->hasOne(Importemployee::className(), ['importemployee_id' => 'id_importemployee'])->from(['idimportemployee' => Importemployee::tableName()]);
    }
}
