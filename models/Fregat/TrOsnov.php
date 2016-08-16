<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "tr_osnov".
 *
 * @property string $tr_osnov_id
 * @property string $tr_osnov_kab
 * @property string $id_installakt
 * @property string $id_mattraffic
 *
 * @property Installakt $idInstallakt
 * @property Mattraffic $idMattraffic
 */
class TrOsnov extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tr_osnov';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tr_osnov_kab', 'id_installakt', 'id_mattraffic'], 'required', 'except' => 'forosmotrakt'],
            [['id_installakt', 'id_mattraffic'], 'integer'],
            [['tr_osnov_kab'], 'string', 'max' => 255],
            [['id_installakt'], 'exist', 'skipOnError' => true, 'targetClass' => Installakt::className(), 'targetAttribute' => ['id_installakt' => 'installakt_id']],
            [['id_mattraffic'], 'exist', 'skipOnError' => true, 'targetClass' => Mattraffic::className(), 'targetAttribute' => ['id_mattraffic' => 'mattraffic_id']],
            [['id_mattraffic'], 'CheckBuild'],
        ];
    }

    // Проверка на заполненность Здания у МОЛ
    public function CheckBuild($attribute) {
        if (empty($this->idMattraffic->idMol->id_build))
            $this->addError($attribute, 'У материально ответственного лица "' . $this->idMattraffic->idMol->idperson->auth_user_fullname . '" не заполнено "Здание", в которое устанавливается материальная ценность');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tr_osnov_id' => 'Tr Osnov ID',
            'tr_osnov_kab' => 'Кабинет',
            'id_installakt' => 'Акт установки',
            'id_mattraffic' => 'Инвентарный номер',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdInstallakt() {
        return $this->hasOne(Installakt::className(), ['installakt_id' => 'id_installakt'])->inverseOf('trOsnovs');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMattraffic() {
        return $this->hasOne(Mattraffic::className(), ['mattraffic_id' => 'id_mattraffic'])->inverseOf('trOsnovs');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOsmotrakts() {
        return $this->hasOne(Osmotrakt::className(), ['id_tr_osnov' => 'tr_osnov_id']);
    }

    public function selectinputforosmotrakt($params) {

        $method = isset($params['init']) ? 'one' : 'all';

        $query = self::find()
                ->select(array_merge(isset($params['init']) ? [] : ['tr_osnov_id AS id'], ['CONCAT(idMaterial.material_inv, ", каб. ", tr_osnov_kab, ", ", IF(idbuild.build_name IS NULL, "Здание отсутствует", idbuild.build_name), ", ", idMaterial.material_name) AS text']))
                ->joinWith([
                    'idMattraffic' => function($query) {
                        $query->from(['idMattraffic' => 'mattraffic']);
                        $query->joinWith([
                            'idMol' => function($query) {
                                $query->from(['idMol' => 'employee']);
                                $query->joinWith([
                                    'idperson' => function($query) {
                                        $query->from(['idperson' => 'auth_user']);
                                    },
                                            'iddolzh' => function($query) {
                                        $query->from(['iddolzh' => 'dolzh']);
                                    },
                                            'idpodraz' => function($query) {
                                        $query->from(['idpodraz' => 'podraz']);
                                    },
                                            'idbuild' => function($query) {
                                        $query->from(['idbuild' => 'build']);
                                    },
                                        ]);
                                    },
                                        ]);
                                    },
                                            'idInstallakt' => function($query) {
                                        $query->from(['idInstallakt' => 'installakt']);
                                    },
                                        ])
                                        ->join('LEFT JOIN', 'material idMaterial', 'id_material = idMaterial.material_id')
                                        ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'idMattraffic.id_material = m2.id_material_m2 and idMattraffic.id_mol = m2.id_mol_m2 and idMattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
                                        // Последнее перемещение
                                        ->join('LEFT JOIN', '(select mt.id_material, inst.installakt_date from installakt inst RIGHT JOIN tr_osnov ts ON inst.installakt_id = ts.id_installakt LEFT JOIN mattraffic mt ON ts.id_mattraffic = mt.mattraffic_id) lastinst', 'lastinst.id_material = idMattraffic.id_material and idInstallakt.installakt_date < lastinst.installakt_date')
                                        ->where(['like', isset($params['init']) ? 'tr_osnov_id' : 'idMaterial.material_inv', $params['q'], isset($params['init']) ? false : null])
                                        ->andWhere('idMattraffic.mattraffic_number > 0')
                                        ->andWhere(['in', 'idMattraffic.mattraffic_tip', [3]])
                                        ->andWhere(['m2.mattraffic_date_m2' => NULL])
                                        ->andWhere(['lastinst.installakt_date' => NULL])  // Последнее перемещение
                                        ->limit(20)
                                        ->asArray()
                                        ->$method();

                                return $query;
                            }

                        }
                        