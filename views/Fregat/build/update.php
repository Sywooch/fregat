<?php

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Build */
/* @var $searchModel app\models\Fregat\CabinetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Обновить здание';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
    'model' => $model,
]);
?>
<div class="build-update">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading base-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ])
            ?>
        </div>
    </div>
</div>
