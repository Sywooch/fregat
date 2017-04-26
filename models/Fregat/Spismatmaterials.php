<?php

namespace app\models\Fregat;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "spismatmaterials".
 *
 * @property string $spismatmaterials_id
 * @property string $id_spismat
 * @property string $id_mattraffic
 *
 * @property Mattraffic $idMattraffic
 * @property Spismat $idSpismat
 */
class Spismatmaterials extends \yii\db\ActiveRecord
{
    public $vsum;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spismatmaterials';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_spismat', 'id_mattraffic'], 'required'],
            [['id_spismat', 'id_mattraffic'], 'integer'],
            [['id_mattraffic'], 'exist', 'skipOnError' => true, 'targetClass' => Mattraffic::className(), 'targetAttribute' => ['id_mattraffic' => 'mattraffic_id']],
            [['id_spismat'], 'exist', 'skipOnError' => true, 'targetClass' => Spismat::className(), 'targetAttribute' => ['id_spismat' => 'spismat_id']],
            [['id_mattraffic'], 'unique', 'targetAttribute' => ['id_spismat', 'id_mattraffic'], 'message' => 'Данная материальная ценность уже имеется в ведомости списания.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'spismatmaterials_id' => 'Spismatmaterials ID',
            'id_spismat' => 'Id Spismat',
            'id_mattraffic' => 'Материал',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMattraffic()
    {
        return $this->hasOne(Mattraffic::className(), ['mattraffic_id' => 'id_mattraffic'])->from(['idMattraffic' => Mattraffic::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdSpismat()
    {
        return $this->hasOne(Spismat::className(), ['spismat_id' => 'id_spismat'])->from(['idSpismat' => Spismat::tableName()]);
    }

    public static function getVedomostArray($spismat_id)
    {
        $materials = self::find()
            ->select(['idMattraffic.id_material', 'idMaterial.material_name', 'idMaterial.material_inv', 'idMaterial.material_price', 'idIzmer.izmer_name'])
            ->joinWith([
                'idMattraffic.idMaterial.idIzmer',
                'idMattraffic.trMats.idInstallakt.idInstaller',
            ])
            ->andWhere(['spismatmaterials.id_spismat' => $spismat_id])
            ->groupBy(['idMattraffic.id_material'])
            ->asArray()
            ->all();

        $installers = self::find()
            ->select(['idInstaller.id_person', 'idperson.auth_user_fullname'])
            ->joinWith([
                'idMattraffic.idMaterial',
                'idMattraffic.trMats.idInstallakt.idInstaller.idperson',
            ])
            ->andWhere(['spismatmaterials.id_spismat' => $spismat_id])
            ->groupBy(['idInstaller.id_person'])
            ->asArray()
            ->all();

        $material_sheets_count = ceil(count($materials) / 1/*10*/);
        $installer_sheets_count = ceil(count($installers) / 1/*14*/);

        $rows = [
            'material_sheets_count' => $material_sheets_count,
            'installer_sheets_count' => $installer_sheets_count,
        ];

        foreach ($materials as $key_m => $material) {
            $rows['materials'][$material['id_material']] = [
                'material_name' => $material['material_name'],
                'material_inv' => $material['material_inv'],
                'material_price' => $material['material_price'],
                'izmer_name' => $material['izmer_name'],
                'installers' => [],
            ];

            foreach ($installers as $key_i => $installer) {
                $rows['materials'][$material['id_material']]['installers'][$installer['id_person']] = [
                    'auth_user_fullname' => $installer['auth_user_fullname'],
                    'vsum' => 0,
                ];
            }
        }

        $query = self::find()
            ->select([
                'idMattraffic.id_material',
                'idMaterial.material_name',
                'idMaterial.material_inv',
                'idMaterial.material_price',
                'idIzmer.izmer_name',
                'idInstaller.id_person',
                'personmaster.auth_user_fullname',
                new Expression('sum(idMattraffic.mattraffic_number) as vsum'),
            ])
            ->joinWith([
                'idMattraffic.idMaterial.idIzmer',
                'idMattraffic.trMats.idInstallakt.idInstaller.idperson personmaster',
            ])
            ->andWhere(['spismatmaterials.id_spismat' => $spismat_id])
            ->groupBy(['idMattraffic.id_material', 'idInstaller.id_person'])
            ->orderBy(['idMattraffic.id_material' => SORT_ASC, 'personmaster.auth_user_fullname' => SORT_ASC])
            ->asArray()
            ->all();


        if ($query !== false) {
            foreach ($query as $ar) {
                $rows['materials'][$ar['id_material']]['installers'] = array_replace_recursive($rows['materials'][$ar['id_material']]['installers'], [$ar['id_person'] => ['vsum' => $ar['vsum']]]);
            }

            return $rows;
        }
    }

    public static function getVedomostArrayTest($spismat_id)
    {
        $materials = self::find()
            ->select(['idMattraffic.id_material', 'idMaterial.material_name', 'idMaterial.material_inv', 'idMaterial.material_price', 'idIzmer.izmer_name'])
            ->joinWith([
                'idMattraffic.idMaterial.idIzmer',
                'idMattraffic.trMats.idInstallakt.idInstaller',
            ])
            ->andWhere(['spismatmaterials.id_spismat' => $spismat_id])
            ->groupBy(['idMattraffic.id_material'])
            ->asArray()
            ->all();

        $installers = self::find()
            ->select(['idInstaller.id_person', 'idperson.auth_user_fullname'])
            ->joinWith([
                'idMattraffic.idMaterial',
                'idMattraffic.trMats.idInstallakt.idInstaller.idperson',
            ])
            ->andWhere(['spismatmaterials.id_spismat' => $spismat_id])
            ->groupBy(['idInstaller.id_person'])
            ->asArray()
            ->all();

        $material_sheets_count = ceil(count($materials) / 1/*10*/);
        $installer_sheets_count = ceil(count($installers) / 1/*14*/);

        $rows = [];

        foreach ($materials as $key_m => $material) {
            $rows[$material['id_material']] = [
                'material_name' => $material['material_name'],
                'installers' => [],
            ];

            foreach ($installers as $key_i => $installer) {
                $rows[$material['id_material']]['installers'][$installer['id_person']] = [
                    'auth_user_fullname' => $installer['auth_user_fullname'],
                    'vsum' => 0,
                ];
            }
        }

        $query = self::find()
            ->select([
                'idMattraffic.id_material',
                'idMaterial.material_name',
                'idMaterial.material_inv',
                'idMaterial.material_price',
                'idIzmer.izmer_name',
                'idInstaller.id_person',
                'personmaster.auth_user_fullname',
                new Expression('sum(idMattraffic.mattraffic_number) as vsum'),
            ])
            ->joinWith([
                'idMattraffic.idMaterial.idIzmer',
                'idMattraffic.trMats.idInstallakt.idInstaller.idperson personmaster',
            ])
            ->andWhere(['spismatmaterials.id_spismat' => $spismat_id])
            ->groupBy(['idMattraffic.id_material', 'idInstaller.id_person'])
            ->orderBy(['idMattraffic.id_material' => SORT_ASC, 'personmaster.auth_user_fullname' => SORT_ASC])
            ->asArray()
            ->all();


        if ($query !== false) {
            foreach ($query as $ar) {
                $rows[$ar['id_material']]['installers'] = array_replace_recursive($rows[$ar['id_material']]['installers'], [$ar['id_person'] => ['vsum' => $ar['vsum']]]);
            }

            return $rows;
        }
    }
}
