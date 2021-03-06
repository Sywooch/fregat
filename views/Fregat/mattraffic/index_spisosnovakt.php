<?php

use yii\helpers\Html;
use app\func\Proc;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Url;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\ButtonGroup;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\Material;
use app\models\Fregat\Employee;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\MattrafficSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Движение материальных ценностей';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="mattraffic-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    $mattraffic_tip = Mattraffic::VariablesValues('mattraffic_tip');
    $material_tip = Material::VariablesValues('material_tip');
    $material_writeoff = Material::VariablesValues('material_writeoff');
    $material_importdo = Material::VariablesValues('material_importdo');
    $employee_importdo = Employee::VariablesValues('employee_importdo');

    echo DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'mattrafficgrid'],
        'columns' => Proc::DGcols([
            'buttonsfirst' => true,
            'buttons' => empty($foreign) ? [] : [
                'chooseajax' => ['Fregat/mattraffic/assign-to-select2']
            ],
            'columns' => [
                [
                    'attribute' => 'mattraffic_date',
                    'format' => 'date',
                ],
                [
                    'attribute' => 'mattraffic_tip',
                    'filter' => $mattraffic_tip,
                    'value' => function ($model) use ($mattraffic_tip) {
                        return isset($mattraffic_tip[$model->mattraffic_tip]) ? $mattraffic_tip[$model->mattraffic_tip] : '';
                    },
                ],
                'mattraffic_number',
                [
                    'attribute' => 'idMaterial.material_tip',
                    'filter' => $material_tip,
                    'value' => function ($model) use ($material_tip) {
                        return $material_tip[$model->idMaterial->material_tip] ?: '';
                    },
                ],
                [
                    'attribute' => 'idMaterial.idMatv.matvid_name',
                    'visible' => false,
                ],
                [
                    'attribute' => 'idMaterial.material_name',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '<a data-pjax="0" href="' . Url::to(['Fregat/material/update', 'id' => $model->id_material]) . '">' . $model->idMaterial->material_name . '</a>';
                    }
                ],
                'idMaterial.idSchetuchet.schetuchet_kod',
                'idMaterial.material_inv',
                [
                    'attribute' => 'idMaterial.material_serial',
                    'visible' => false,
                ],
                [
                    'attribute' => 'idMaterial.material_release',
                    'format' => 'date',
                    'visible' => false,
                ],
                'idMaterial.material_number',
                'idMaterial.idIzmer.izmer_name',
                'idMaterial.material_price',
                [
                    'attribute' => 'idMol.employee_id',
                    'visible' => false,
                ],
                'idMol.idperson.auth_user_fullname',
                'idMol.iddolzh.dolzh_name',
                'idMol.idpodraz.podraz_name',
                'idMol.idbuild.build_name',
                [
                    'attribute' => 'idMol.employee_dateinactive',
                    'format' => 'date',
                    'visible' => false,
                ],
                [
                    'attribute' => 'idMaterial.material_writeoff',
                    'filter' => $material_writeoff,
                    'value' => function ($model) use ($material_writeoff) {
                        return isset($material_writeoff[$model->idMaterial->material_writeoff]) ? $material_writeoff[$model->idMaterial->material_writeoff] : '';
                    },
                    'visible' => false,
                ],
                [
                    'attribute' => 'idMaterial.material_username',
                    'visible' => false,
                ],
                [
                    'attribute' => 'idMaterial.material_lastchange',
                    'format' => 'datetime',
                    'visible' => false,
                ],
                [
                    'attribute' => 'idMaterial.material_importdo',
                    'filter' => $material_importdo,
                    'value' => function ($model) use ($material_importdo) {
                        return isset($material_importdo[$model->idMaterial->material_importdo]) ? $material_importdo[$model->idMaterial->material_importdo] : '';
                    },
                    'visible' => false,
                ],
                [
                    'attribute' => 'idMol.employee_username',
                    'visible' => false,
                ],
                [
                    'attribute' => 'idMol.employee_lastchange',
                    'format' => 'datetime',
                    'visible' => false,
                ],
                [
                    'attribute' => 'idMol.employee_importdo',
                    'filter' => $employee_importdo,
                    'value' => function ($model) use ($employee_importdo) {
                        return isset($employee_importdo[$model->idMol->employee_importdo]) ? $employee_importdo[$model->idMol->employee_importdo] : '';
                    },
                    'visible' => false,
                ],
                [
                    'attribute' => 'mattraffic_username',
                    'visible' => false,
                ],
                [
                    'attribute' => 'mattraffic_lastchange',
                    'format' => 'datetime',
                    'visible' => false,
                ],
            ],
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-th-large"></i> ' . $this->title,
            ],
        ],
    ]));
    ?>

</div>
        
