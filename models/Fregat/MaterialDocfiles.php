<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "material_docfiles".
 *
 * @property string $material_docfiles_id
 * @property string $id_docfiles
 * @property string $id_material
 *
 * @property Docfiles $idDocfiles
 * @property Material $idMaterial
 */
class MaterialDocfiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'material_docfiles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_docfiles', 'id_material'], 'required'],
            [['id_docfiles', 'id_material'], 'integer'],
            [['id_docfiles'], 'exist', 'skipOnError' => true, 'targetClass' => Docfiles::className(), 'targetAttribute' => ['id_docfiles' => 'docfiles_id']],
            [['id_material'], 'exist', 'skipOnError' => true, 'targetClass' => Material::className(), 'targetAttribute' => ['id_material' => 'material_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'material_docfiles_id' => 'Material Docfiles ID',
            'id_docfiles' => 'Id Docfiles',
            'id_material' => 'Id Material',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdDocfiles()
    {
        return $this->hasOne(Docfiles::className(), ['docfiles_id' => 'id_docfiles']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMaterial()
    {
        return $this->hasOne(Material::className(), ['material_id' => 'id_material']);
    }
}
