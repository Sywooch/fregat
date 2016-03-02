<?php

namespace app\models\Config;

use Yii;
use app\models\Fregat\Employee;

/**
 * This is the model class for table "auth_user".
 *
 * @property integer $auth_user_id
 * @property string $auth_user_fullname
 * @property string $auth_user_login
 * @property string $auth_user_password
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthItem[] $itemNames
 */
class Authuser extends \yii\db\ActiveRecord {

    public $auth_user_password2;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'auth_user';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['auth_user_fullname', 'auth_user_login'], 'required'],
            [['auth_user_password', 'auth_user_password2'], 'required', 'on' => ['Newuser', 'Changepassword']],
            [['auth_user_fullname', 'auth_user_login'], 'string', 'max' => 128],
            ['auth_user_login', 'unique'],
            [['auth_user_password'], 'string', 'max' => 255],
            ['auth_user_password2', 'compare', 'compareAttribute' => 'auth_user_password', 'message' => "Подтверждение пароля не совпадает", 'on' => ['Newuser', 'Changepassword']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'auth_user_id' => 'Код',
            'auth_user_fullname' => 'Фамилия Имя Отчество',
            'auth_user_login' => 'Логин',
            'auth_user_password' => 'Пароль',
            'auth_user_password2' => 'Подтвердите пароль',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getauthassignments() {
        return $this->hasMany(Authassignment::className(), ['user_id' => 'auth_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getitemnames() {
        return $this->hasMany(Authitem::className(), ['name' => 'item_name'])->viaTable('auth_assignment', ['user_id' => 'auth_user_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['id_person' => 'auth_user_id']);
    }

    public function save($runValidation = true, $attributeNames = null) {

        if ($this->getIsNewRecord() || $this->scenario === 'Changepassword') {
            if (!$this->validate())
                return false;

            $this->auth_user_password = Yii::$app->getSecurity()->generatePasswordHash($this->auth_user_password);
            $this->auth_user_password2 = $this->auth_user_password;
        }

        if ($this->getIsNewRecord()) {
            return $this->insert($runValidation, $attributeNames);
        } else {
            return $this->update($runValidation, $attributeNames) !== false;
        }
    }

}
