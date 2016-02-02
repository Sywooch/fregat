<?php

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Build */

$this->title = 'Создать здание';
/* $this->params['breadcrumbs'][] = ['label' => 'Builds', 'url' => ['index']];
  $this->params['breadcrumbs'][] = $this->title; */
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="build-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
