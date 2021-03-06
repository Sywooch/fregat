<?php

use app\func\Proc;
use yii\helpers\Html;

\Yii::$app->getView()->registerJsFile('@web/js/nakladmaterialsform.js' . Proc::appendTimestampUrlParam(Yii::$app->basePath . '/web/js/nakladmaterialsform.js'));

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Nakladmaterials */

$this->title = 'Обновить материальную ценность в требовании-накладной';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
    'model' => $model,
]);
?>
<div class="nakladmaterials-update">
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
