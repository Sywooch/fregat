<?php

namespace app\models\Fregat;

use app\models\Config\Authuser;
use Yii;
use yii\base\Model;

class SpismatFilter extends Model
{
    public $mat_id_material;
    public $mol_id_person;

    public function rules()
    {
        return [
            [[
                'mat_id_material',
                'mol_id_person',
            ], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mat_id_material' => 'Материал',
            'mol_id_person' => 'Материально-ответственное лицо',
        ];
    }

    public static function VariablesValues($attribute, $value = NULL)
    {
        $values = [
            'mat_id_material' => [$value => Material::getMaterialByID($value)],
            'mol_id_person' => [$value => Authuser::getAuthuserByID($value)],
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
