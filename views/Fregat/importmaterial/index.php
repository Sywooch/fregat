<?php

use yii\helpers\Html;
use app\func\Proc;
use kartik\dynagrid\DynaGrid;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\ImportmaterialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Импорт материальных ценностей';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="importmaterial-index">
    <?=
    DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'importmaterialgrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        'importmaterial_combination',
                        'idmatvid.matvid_name',
                    ],
                    'buttons' => [
                        'update' => ['Fregat/importmaterial/update', 'importmaterial_id'],
                        'deleteajax' => ['Fregat/importmaterial/delete', 'importmaterial_id'],
                    ],
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'panel' => [
                        'heading' => '<i class="glyphicon glyphicon-gift"></i> ' . $this->title,
                        'before' => Yii::$app->user->can('FregatImport') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                    ],
                ]
    ]));
    ?>

</div>
