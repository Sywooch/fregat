<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use app\models\Fregat\Osmotraktmat;
use app\models\Fregat\Material;
use app\models\Fregat\Build;
use app\models\Fregat\Dolzh;
use app\models\Fregat\TrOsnov;
use app\models\Config\Authuser;
use app\models\Fregat\Reason;
use app\models\Fregat\TrMatOsmotr;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Recoveryrecieveaktmat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recoveryrecieveaktmat-form">

    <?php
    $form = ActiveForm::begin([
        'id' => 'Recoveryrecieveaktmatform',
    ]);
    ?>

    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Акт осмотра материала') ?></div>
        <div class="panel-body">
            <?= $form->field(Proc::RelatModelValue($model, 'idTrMatOsmotr.idOsmotraktmat', new Osmotraktmat), 'osmotraktmat_id', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>
            <?= $form->field(Proc::RelatModelValue($model, 'idTrMatOsmotr.idTrMat.idMattraffic.idMaterial', new Material), 'material_inv', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>
            <?= $form->field(Proc::RelatModelValue($model, 'idTrMatOsmotr.idTrMat.idMattraffic.idMaterial', new Material), 'material_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>
            <?= $form->field(Proc::RelatModelValue($model, 'idTrMatOsmotr', new TrMatOsmotr), 'tr_mat_osmotr_number', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>
            <?= $form->field(Proc::RelatModelValue($model, 'idTrMatOsmotr.idTrMat.idMattraffic.idMol.idperson', new Authuser), 'auth_user_fullname', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true])->label('ФИО материально-ответственонго лица') ?>
            <?= $form->field(Proc::RelatModelValue($model, 'idTrMatOsmotr.idTrMat.idMattraffic.idMol.iddolzh', new Dolzh), 'dolzh_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true])->label('Должность материально-ответственного лица') ?>
            <?= $form->field(Proc::RelatModelValue($model, 'idTrMatOsmotr.idTrMat.idParent.idMaterial', new Material), 'material_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true])->label('Материальная ценность, в которую укомплектовано') ?>
            <?= $form->field(Proc::RelatModelValue($model, 'idTrMatOsmotr.idTrMat.idParent.idMaterial', new Material), 'material_inv', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true])->label('Инвентарный номер материальной ценности, в которую укомплектовано') ?>
            <?=
            $form->field(Proc::RelatModelValue($model, 'idTrMatOsmotr', new TrMatOsmotr), 'tr_mat_osmotr_comment', ['enableClientValidation' => false])->textarea([
                'class' => 'form-control',
                'maxlength' => 512,
                'rows' => 10,
                'disabled' => true,
                'style' => 'resize: none',
            ])
            ?>
            <?= $form->field(Proc::RelatModelValue($model, 'idTrMatOsmotr.idReason', new Reason), 'reason_text', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>
            <?= $form->field(Proc::RelatModelValue($model, 'idTrMatOsmotr.idOsmotraktmat.idMaster.idperson', new Authuser), 'auth_user_fullname', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true])->label('Составитель акта осмотра материала') ?>
            <?= $form->field(Proc::RelatModelValue($model, 'idTrMatOsmotr.idOsmotraktmat.idMaster.iddolzh', new Dolzh), 'dolzh_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true])->label('Должность составителя акта осмотра материала') ?>
        </div>
    </div>

    <?=
    $form->field($model, 'recoveryrecieveaktmat_result')->textarea([
        'class' => 'form-control setsession',
        'maxlength' => 512,
        'rows' => 10,
        'style' => 'resize: none',
    ])
    ?>

    <?=
    $form->field($model, 'recoveryrecieveaktmat_repaired')->widget(Select2::classname(), [
        'hideSearch' => true,
        'data' => $model::VariablesValues('recoveryrecieveaktmat_repaired'),
        'pluginOptions' => [
            'allowClear' => true
        ],
        'options' => ['placeholder' => 'Выберете статус восстановления', 'class' => 'form-control setsession'],
        'theme' => Select2::THEME_BOOTSTRAP,
    ]);
    ?>

    <?=
    $form->field($model, 'recoveryrecieveaktmat_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
        'options' => [
            'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
        ],
    ])
    ?>

    <?php ActiveForm::end(); ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Recoveryrecieveaktmatform']) ?>
            </div>
        </div>
    </div>
</div>
