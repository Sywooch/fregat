<?php

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Import\Importconfig */

$this->title = 'Общие настройки импорта данных из 1С';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this,[
    'model' => $model,
]);
?>
<div class="importconfig-update">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading base-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
            ])
            ?>
        </div>
    </div>
</div>
