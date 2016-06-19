<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="patientglaukfilter-form">
    <?php $form = ActiveForm::begin(['options' => ['id' => $model->formName() . '-form', 'data-pjax' => true]]); ?>
    <div class="insideforms">
        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
            <div class="panel-heading"><?= Html::encode('Паспорт пациента') ?></div>
            <div class="panel-body">
                <?= $form->field($model, 'patient_fam')->textInput(['maxlength' => true, 'class' => 'form-control inputuppercase', 'autofocus' => true]) ?>

                <?= $form->field($model, 'patient_im')->textInput(['maxlength' => true, 'class' => 'form-control inputuppercase']) ?>

                <?= $form->field($model, 'patient_ot')->textInput(['maxlength' => true, 'class' => 'form-control inputuppercase']) ?>

                <?=
                $form->field($model, 'patient_dr')->widget(DateControl::classname(), [
                    'type' => DateControl::FORMAT_DATE,
                    'options' => [
                        'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                    ],
                    'saveOptions' => ['class' => 'form-control'],
                ])
                ?>

                <div class="form-group">     
                    <label class="control-label" for="patientfilter-patient_vozrast">Возраст пациента</label>
                    <div class="row"> 
                        <div class="col-xs-5">
                            <?=
                            $form->field($model, 'patient_vozrast_znak')->widget(Select2::classname(), [
                                'hideSearch' => true,
                                'data' => ['>=' => 'Больше или равно', '<=' => 'Меньше или равно', '=' => 'Равно'],
                                'options' => ['placeholder' => 'Выберете знак равенства', 'class' => 'form-control', 'style' => 'width; 215px;'],
                                'theme' => Select2::THEME_BOOTSTRAP,
                            ])->label(false);
                            ?>
                        </div>
                        <div class="col-xs-7">
                            <?=
                            $form->field($model, 'patient_vozrast')->widget(kartik\touchspin\TouchSpin::classname(), [
                                'options' => ['class' => 'form-control'],
                                'pluginOptions' => [
                                    'verticalbuttons' => true,
                                    'min' => 1,
                                    'max' => 120,
                                    'step' => 1,
                                    'decimals' => 0,
                                    'forcestepdivisibility' => 'none',
                                ]
                            ])->label(false);
                            ?>
                        </div>
                    </div>
                </div>

                <?=
                $form->field($model, 'patient_pol')->widget(Select2::classname(), [
                    'hideSearch' => true,
                    'data' => app\models\Base\Patient::VariablesValues('patient_pol'),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options' => ['placeholder' => 'Выберете пол пациента', 'class' => 'form-control'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                ]);
                ?>

                <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
                    <div class="panel-heading"><?= Html::encode('Адрес пациента') ?></div>
                    <div class="panel-body">
                        <?=
                        $form->field($model, 'fias_city')->widget(Select2::classname(), array_merge(Proc::DGselect2([
                                            'model' => $model,
                                            'resultmodel' => new \app\models\Base\Fias,
                                            'fields' => [
                                                'keyfield' => 'fias_city',
                                            ],
                                            'placeholder' => 'Введите населенный пункт',
                                            'resultrequest' => 'Base/fias/selectinputforcity',
                                            'thisroute' => $this->context->module->requestedRoute,
                                            'methodquery' => 'selectinputforcity',
                                        ]), [
                            'pluginEvents' => [
                                "select2:select" => "function() { FillCity(); }",
                                "select2:unselect" => "function() { ClearCity(); }"
                            ],
                        ]))->label('Населенный пункт');
                        ?>

                        <?=
                        $form->field($model, 'fias_street')->widget(Select2::classname(), Proc::DGselect2([
                                    'model' => $model,
                                    'resultmodel' => new \app\models\Base\Fias,
                                    'fields' => [
                                        'keyfield' => 'fias_street',
                                    ],
                                    'placeholder' => 'Введите улицу',
                                    'resultrequest' => 'Base/fias/selectinputforstreet',
                                    'thisroute' => $this->context->module->requestedRoute,
                                    'methodquery' => 'selectinputforstreet',
                                    'ajaxparams' => ['fias_city' => '$(\'select[name="PatientFilter[fias_city]"]\').val()'],
                                    'minimuminputlength' => 2,
                        ]))
                        ?>

                        <?= $form->field($model, 'patient_dom')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

                        <?= $form->field($model, 'patient_korp')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

                        <?= $form->field($model, 'patient_kvartira')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::Button('<i class="glyphicon glyphicon-ok"></i> Применить', ['class' => 'btn btn-primary', 'id' => $model->formName() . '_apply']) ?>
                <?= Html::Button('<i class="glyphicon glyphicon-remove"></i> Отмена', ['class' => 'btn btn-danger', 'id' => $model->formName() . '_close']) ?>
            </div>
        </div> 
    </div>

    <?php ActiveForm::end(); ?>
</div>
