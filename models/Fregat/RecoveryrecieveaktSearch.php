<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Recoveryrecieveakt;
use app\func\Proc;

/**
 * RecoveryrecieveaktSearch represents the model behind the search form about `app\models\Fregat\Recoveryrecieveakt`.
 */
class RecoveryrecieveaktSearch extends Recoveryrecieveakt {

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idOsmotrakt.osmotrakt_id',
            'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_inv',
            'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_name',
            'idOsmotrakt.idTrosnov.idMattraffic.idMol.idbuild.build_name',
            'idOsmotrakt.idTrosnov.tr_osnov_kab',
            'idOsmotrakt.idReason.reason_text',
            'idOsmotrakt.osmotrakt_comment',
            'idOsmotrakt.idMaster.idperson.auth_user_fullname',
            'idOsmotrakt.idMaster.iddolzh.dolzh_name',
            'idOsmotrakt.osmotrakt_date',
            'idRecoverysendakt.recoverysendakt_date',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['recoveryrecieveakt_id', 'id_osmotrakt', 'id_recoverysendakt', 'recoveryrecieveakt_repaired'], 'integer'],
            [['recoveryrecieveakt_result', 'recoveryrecieveakt_date'], 'safe'],
            [[
            'idOsmotrakt.osmotrakt_id',
            'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_inv',
            'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_name',
            'idOsmotrakt.idTrosnov.idMattraffic.idMol.idbuild.build_name',
            'idOsmotrakt.idTrosnov.tr_osnov_kab',
            'idOsmotrakt.idReason.reason_text',
            'idOsmotrakt.osmotrakt_comment',
            'idOsmotrakt.idMaster.idperson.auth_user_fullname',
            'idOsmotrakt.idMaster.iddolzh.dolzh_name',
            'idOsmotrakt.osmotrakt_date',
            'idRecoverysendakt.recoverysendakt_date',
                ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    private function baseRelations(&$query) {
        $query->joinWith([
            'idOsmotrakt' => function($query) {
                $query->from(['idOsmotrakt' => 'osmotrakt']);
                $query->joinWith([
                    'idTrosnov' => function($query) {
                        $query->from(['idTrosnov' => 'tr_osnov']);
                        $query->joinWith([
                            'idMattraffic' => function($query) {
                                $query->from(['idMattraffic' => 'mattraffic']);
                                $query->joinWith([
                                    'idMaterial' => function($query) {
                                        $query->from(['idMaterial' => 'material']);
                                    },
                                            'idMol' => function($query) {
                                        $query->from(['idMol' => 'employee']);
                                        $query->joinWith([
                                            'idbuild' => function($query) {
                                                $query->from(['idbuild' => 'build']);
                                            },
                                                ]);
                                            },
                                                ]);
                                            }]);
                                            },
                                                    'idReason' => function($query) {
                                                $query->from(['idReason' => 'reason']);
                                            },
                                                    'idMaster' => function($query) {
                                                $query->from(['idMaster' => 'employee']);
                                                $query->joinWith([
                                                    'idperson' => function($query) {
                                                        $query->from(['idperson' => 'auth_user']);
                                                    },
                                                            'iddolzh' => function($query) {
                                                        $query->from(['iddolzh' => 'dolzh']);
                                                    },
                                                        ]);
                                                    },
                                                        ]);
                                                    }]);
                                                    }

                                                    private function baseFilter(&$query) {
                                                        $query->andFilterWhere([
                                                            'recoveryrecieveakt_id' => $this->recoveryrecieveakt_id,
                                                            'id_osmotrakt' => $this->id_osmotrakt,
                                                            'id_recoverysendakt' => $this->id_recoverysendakt,
                                                            'recoveryrecieveakt_repaired' => $this->recoveryrecieveakt_repaired,
                                                        ]);

                                                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'idOsmotrakt.osmotrakt_id'));
                                                        $query->andFilterWhere(['like', 'recoveryrecieveakt_result', $this->recoveryrecieveakt_result]);
                                                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'recoveryrecieveakt_date', 'date'));
                                                        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_inv')]);
                                                        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_name')]);
                                                        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idOsmotrakt.idTrosnov.idMattraffic.idMol.idbuild.build_name')]);
                                                        $query->andFilterWhere(['LIKE', 'idTrosnov.tr_osnov_kab', $this->getAttribute('idOsmotrakt.idTrosnov.tr_osnov_kab')]);
                                                        $query->andFilterWhere(['LIKE', 'idReason.reason_text', $this->getAttribute('idOsmotrakt.idReason.reason_text')]);
                                                        $query->andFilterWhere(['LIKE', 'idOsmotrakt.osmotrakt_comment', $this->getAttribute('idOsmotrakt.osmotrakt_comment')]);
                                                        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idOsmotrakt.idMaster.idperson.auth_user_fullname')]);
                                                        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idOsmotrakt.idMaster.iddolzh.dolzh_name')]);
                                                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'idOsmotrakt.osmotrakt_date', 'date'));
                                                    }

                                                    private function baseSort(&$dataProvider) {
                                                        Proc::AssignRelatedAttributes($dataProvider, [
                                                            'idOsmotrakt.osmotrakt_id',
                                                            'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_inv',
                                                            'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_name',
                                                            'idOsmotrakt.idTrosnov.idMattraffic.idMol.idbuild.build_name',
                                                            'idOsmotrakt.idTrosnov.tr_osnov_kab',
                                                            'idOsmotrakt.idReason.reason_text',
                                                            'idOsmotrakt.osmotrakt_comment',
                                                            'idOsmotrakt.idMaster.idperson.auth_user_fullname',
                                                            'idOsmotrakt.idMaster.iddolzh.dolzh_name',
                                                            'idOsmotrakt.osmotrakt_date',
                                                        ]);
                                                    }

                                                    /**
                                                     * Creates data provider instance with search query applied
                                                     *
                                                     * @param array $params
                                                     *
                                                     * @return ActiveDataProvider
                                                     */
                                                    public function search($params) {
                                                        $query = Recoveryrecieveakt::find();

                                                        // add conditions that should always apply here

                                                        $dataProvider = new ActiveDataProvider([
                                                            'query' => $query,
                                                            'sort' => ['defaultOrder' => ['recoveryrecieveakt_id' => SORT_DESC]],
                                                        ]);

                                                        $this->baseRelations($query);

                                                        $this->load($params);

                                                        if (!$this->validate()) {
                                                            // uncomment the following line if you do not want to return any records when validation fails
                                                            // $query->where('0=1');
                                                            return $dataProvider;
                                                        }

                                                        $query->andFilterWhere([
                                                            'id_recoverysendakt' => (string) filter_input(INPUT_GET, 'id'),
                                                        ]);

                                                        $this->baseFilter($query);
                                                        $this->baseSort($dataProvider);

                                                        return $dataProvider;
                                                    }

                                                    public function searchformaterialkarta($params) {
                                                        $query = Recoveryrecieveakt::find();

                                                        // add conditions that should always apply here

                                                        $dataProvider = new ActiveDataProvider([
                                                            'query' => $query,
                                                            'sort' => ['defaultOrder' => ['idRecoverysendakt.recoverysendakt_date' => SORT_DESC, 'recoveryrecieveakt_date' => SORT_DESC]],
                                                        ]);

                                                        $query->joinWith([
                                                            'idRecoverysendakt' => function($query) {
                                                                $query->from(['idRecoverysendakt' => 'recoverysendakt']);
                                                            },
                                                                    'idOsmotrakt' => function($query) {
                                                                $query->from(['idOsmotrakt' => 'osmotrakt']);
                                                                $query->joinWith([
                                                                    'idTrosnov' => function($query) {
                                                                        $query->from(['idTrosnov' => 'tr_osnov']);
                                                                        $query->joinWith([
                                                                            'idMattraffic' => function($query) {
                                                                                $query->from(['idMattraffic' => 'mattraffic']);
                                                                            },
                                                                                ]);
                                                                            },
                                                                                ]);
                                                                            },
                                                                                ]);

                                                                                $this->load($params);

                                                                                if (!$this->validate()) {
                                                                                    // uncomment the following line if you do not want to return any records when validation fails
                                                                                    // $query->where('0=1');
                                                                                    return $dataProvider;
                                                                                }

                                                                                $query->andFilterWhere([
                                                                                    'recoveryrecieveakt_repaired' => $this->recoveryrecieveakt_repaired,
                                                                                    'idMattraffic.id_material' => $params['id'],
                                                                                ]);

                                                                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'id_recoverysendakt'));
                                                                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'idRecoverysendakt.recoverysendakt_date'), 'date');
                                                                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'recoveryrecieveakt_date'), 'date');
                                                                                $query->andFilterWhere(['LIKE', 'recoveryrecieveakt_result', $this->getAttribute('recoveryrecieveakt_result')]);
                                                                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'id_osmotrakt'));

                                                                                Proc::AssignRelatedAttributes($dataProvider, [
                                                                                    'idRecoverysendakt.recoverysendakt_date',
                                                                                ]);

                                                                                return $dataProvider;
                                                                            }

                                                                        }
                                                                        