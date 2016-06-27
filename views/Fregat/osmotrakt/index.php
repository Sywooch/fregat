<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\OsmotraktSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Журнал осмотров материальных ценностей';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="osmotrakt-index">
    <?=
    DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'osmotraktgrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        'osmotrakt_id',
                        'idMattraffic.idMaterial.idMatv.matvid_name',
                        'idMattraffic.idMaterial.material_name',
                        'idMattraffic.idMaterial.material_inv',
                        'idMattraffic.idMaterial.material_serial',
                        // podraz, kabinet
                        'idUser.idperson.auth_user_fullname',
                        'idUser.iddolzh.dolzh_name',
                        'idMattraffic.idMol.idperson.auth_user_fullname',
                        'idMattraffic.idMol.iddolzh.dolzh_name',
                        'idReason.reason_text',
                        'osmotrakt_comment',
                        'idMaster.idperson.auth_user_fullname',
                        'idMaster.iddolzh.dolzh_name',
                    ],
                    'buttons' => array_merge(
                            /* empty($foreign) ? [] : [
                              'chooseajax' => ['Fregat/osmotrakt/assign-to-']], */ /* Yii::$app->user->can('OsmotraktEdit') */ true ? [
                                'update' => ['Fregat/osmotrakt/update', 'osmotrakt_id'],
                                'deleteajax' => ['Fregat/osmotrakt/delete', 'osmotrakt_id'],
                                    ] : []
                    ),
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'panel' => [
                        'heading' => '<i class="glyphicon glyphicon-search"></i> ' . $this->title,
                        'before' => /* Yii::$app->user->can('OsmotraktEdit') */ true ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                    ],
                ]
    ]));
    ?>
</div>
<div class="form-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
        </div>
    </div>
</div>