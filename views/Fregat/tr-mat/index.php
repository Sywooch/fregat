<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\TrMatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tr Mats';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-mat-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tr Mat', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tr_mat_id',
            'id_installakt',
            'id_mattraffic',
            'id_parent',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>