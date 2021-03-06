<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Podraz;

/**
 * PodrazSearch represents the model behind the search form about `app\models\Fregat\Podraz`.
 */
class PodrazSearch extends Podraz {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['podraz_id'], 'integer'],
            [['podraz_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = Podraz::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['podraz_name' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'podraz_id' => $this->podraz_id,
        ]);

        $query->andFilterWhere(['like', 'podraz_name', $this->podraz_name]);
    
        return $dataProvider;
    }

}
