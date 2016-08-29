<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
?>

<div class="panel panel-info">
    <div class="panel-heading">Главное меню</div>
    <div class="panel-body">     
        <ul class="nav nav-pills nav-stacked">
            <?php if (Yii::$app->user->can('FregatUserPermission')): ?>
                <li>
                    <?php echo Html::a('<i class="glyphicon glyphicon-list-alt"></i> Система "Фрегат"', ['Fregat/fregat/mainmenu'], ['class' => 'btn btn-default']); ?>
                </li>
            <?php endif; ?>            
            <?php if (Yii::$app->user->can('GlaukUserPermission')): ?>
                <li>
                    <?php echo Html::a('<i class="glyphicon glyphicon-search"></i> Регистр глаукомных пациентов', ['Base/patient/glaukindex'], ['class' => 'btn btn-default']); ?>
                </li>
            <?php endif; ?> 
            <?php if (Yii::$app->user->can('UserEdit') || Yii::$app->user->can('RoleEdit')): ?>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-wrench"></i> Настройки портала', ['Config/config/index'], ['class' => 'btn btn-default']); ?>
                </li>
            <?php endif; ?>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-lock"></i> Сменить пароль', ['Config/authuser/change-self-password'], ['class' => 'btn btn-default']); ?>
                </li>
        </ul>
    </div>
</div>

