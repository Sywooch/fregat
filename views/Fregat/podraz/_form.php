<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Podraz */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="podraz-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'podraz_name')->textInput(['maxlength' => true, 'class' => 'form-control setsession']) ?>

    <div class="form-group">
        <div class="form-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div> 
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>