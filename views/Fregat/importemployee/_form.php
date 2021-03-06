<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Fregat\Build;
use app\models\Fregat\Podraz;
use kartik\select2\Select2;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Importemployee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="importemployee-form">

    <?php
    $form = ActiveForm::begin([
        'id' => 'Importemployeeform',
    ]);
    ?>

    <?= $form->field($model, 'importemployee_combination')->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'autofocus' => true]) ?>

    <?=
    $form->field($model, 'id_podraz')->widget(Select2::classname(), Proc::DGselect2([
        'model' => $model,
        'resultmodel' => new Podraz,
        'fields' => [
            'keyfield' => 'id_podraz',
            'resultfield' => 'podraz_name',
        ],
        'placeholder' => 'Выберете подразделение',
        'fromgridroute' => 'Fregat/podraz/index',
        'resultrequest' => 'Fregat/podraz/selectinput',
        'thisroute' => $this->context->module->requestedRoute,
        'onlyAjax' => false,
    ]));
    ?>

    <?=
    $form->field($model, 'id_build')->widget(Select2::classname(), Proc::DGselect2([
        'model' => $model,
        'resultmodel' => new Build,
        'fields' => [
            'keyfield' => 'id_build',
            'resultfield' => 'build_name',
        ],
        'placeholder' => 'Выберете здание',
        'fromgridroute' => 'Fregat/build/index',
        'resultrequest' => 'Fregat/build/selectinput',
        'thisroute' => $this->context->module->requestedRoute,
        'onlyAjax' => false,
    ]));
    ?>

    <?php ActiveForm::end(); ?>

    <?php
    if (!$model->isNewRecord) {

        echo DynaGrid::widget(Proc::DGopts([
            'options' => ['id' => 'impemployeegrid'],
            'columns' => Proc::DGcols([
                'columns' => [
                    'idemployee.employee_id',
                    'idemployee.idperson.auth_user_fullname',
                    'idemployee.iddolzh.dolzh_name',
                    'idemployee.idpodraz.podraz_name',
                    'idemployee.idbuild.build_name',
                ],
                'buttons' => [
                    'deleteajax' => ['Fregat/impemployee/delete', 'impemployee_id'],
                ],
            ]),
            'gridOptions' => [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> Привязать к сотруднику</h3>',
                    'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить сотрудника', ['Fregat/employee/forimportemployee',
                        'foreignmodel' => 'Impemployee',
                        'url' => $this->context->module->requestedRoute,
                        'field' => 'id_employee',
                        'id' => $model->primaryKey,
                    ], ['class' => 'btn btn-success', 'data-pjax' => '0']),
                ],
            ]
        ]));
    }
    ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">

                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Importemployeeform']) ?>
            </div>
        </div>
    </div>

</div>
