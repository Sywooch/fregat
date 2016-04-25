<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\func\Proc;
use kartik\touchspin\TouchSpin;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrOsnov */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tr-osnov-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Материальная ценность') ?></div>
        <div class="panel-body">
            <?=
            $form->field($model, 'id_mattraffic')->widget(Select2::classname(), array_merge(Proc::DGselect2([
                                'model' => $model,
                                'resultmodel' => new app\models\Fregat\Mattraffic,
                                'fields' => [
                                    'keyfield' => 'id_mattraffic',
                                ],
                                'placeholder' => 'Введите инвентарный номер материальной ценности',
                                'fromgridroute' => 'Fregat/mattraffic/forinstallakt',
                                'resultrequest' => 'Fregat/tr-osnov/selectinputfortrosnov',
                                'thisroute' => $this->context->module->requestedRoute,
                                'methodquery' => 'selectinputfortrosnov',
                                'dopparams' => [
                                    'foreigndo' => '1',
                                ],
                            ]), [
                'pluginEvents' => [
                    "select2:select" => "function() { FillTrOsnov(); }",
                    "select2:unselect" => "function() { ClearTrOsnov(); }"
                ],
            ]));
            ?>

            <?= $form->field($Material, 'material_tip', ['enableClientValidation' => false])->dropDownList([0 => '', 1 => 'Основное средство', 2 => 'Материал'], ['class' => 'form-control setsession', 'disabled' => true]) ?>

            <?= $form->field($Material, 'material_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => true]) ?>

            <?= $form->field($Material, 'material_writeoff', ['enableClientValidation' => false])->dropDownList([0 => 'Нет', 1 => 'Да'], ['class' => 'form-control setsession', 'disabled' => true]) ?>

        </div>
    </div>
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Материально-ответственное лицо') ?></div>
        <div class="panel-body">

            <?= $form->field($Employee, 'id_person', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => true]) ?>

            <?= $form->field($Employee, 'id_dolzh', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => true]) ?>

            <?= $form->field($Employee, 'id_podraz', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => true]) ?>

            <?= $form->field($Employee, 'id_build', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => true]) ?>

            <?= $form->field($Employee->iddolzh, 'dolzh_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => true]) ?>

        </div>
    </div>
    <?=
    $form->field($Mattraffic, 'mattraffic_number', [
        'inputTemplate' => '<div class="input-group">{input}<span id="mattraffic_number_max" class="input-group-addon"></span></div>'
    ])->widget(TouchSpin::classname(), [
        'options' => ['class' => 'form-control setsession'],
        'pluginOptions' => [
            'verticalbuttons' => true,
            'min' => 1,
            'max' => 10000000000,
            'step' => 1,
            'decimals' => 3,
            'forcestepdivisibility' => 'none',
        ]
    ]);
    ?>

    <?= $form->field($model, 'tr_osnov_kab')->textInput(['maxlength' => true]) ?>    

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::submitButton('<i class="glyphicon glyphicon-plus"></i> Добавить', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
