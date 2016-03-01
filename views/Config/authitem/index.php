<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\BuildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Авторизационные единицы';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="authitem-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
                'columns' => Proc::DGcols([
                    'columns' => array_merge(
                            ['description'], !isset($foreign['model']) || (isset($foreign['model']) && $foreign['model'] !== 'Authassignment') ? [
                                [
                                    'attribute' => 'type',
                                    'filter' => [1 => 'Роль', 2 => 'Операция'],
                                    'value' => function ($model) {
                                return $model->type == 1 ? 'Роль' : 'Операция';
                            },
                                ],
                                'name'
                                    ] : []
                    ),
                    'buttons' => array_merge(
                            empty($foreign) ? [] : [
                                'choose' => function ($url, $model, $key) use ($foreign) {
                                    $customurl = Url::to([$foreign['url'], 'id' => $foreign['id'], $foreign['model'] => [$foreign['field'] => $model['name']]]);
                                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-ok-sign"></i>', $customurl, ['title' => 'Выбрать', 'class' => 'btn btn-xs btn-success', 'data-pjax' => '0']);
                                }], Yii::$app->user->can('RoleEdit') ? [
                                        'update' => ['Config/authitem/update', 'name'],
                                        'delete' => ['Config/authitem/delete', 'name'],
                                            ] : []
                            ),
                        ]),
                        'gridOptions' => [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'options' => ['id' => 'authitemgrid'],
                            'panel' => [
                                'heading' => '<i class="glyphicon glyphicon-align-justify"></i> ' . $this->title,
                                'before' => Yii::$app->user->can('RoleEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                            ],
                            'toolbar' => [
                                'base' => ['content' => \yii\bootstrap\Html::button('<i class="glyphicon glyphicon-filter"></i>', [
                                        'type' => 'button',
                                        'title' => 'Дополнительный фильтр',
                                        'class' => 'btn btn-default'
                                    ]) . '{export}{dynagrid}',
                                ],
                            ],
                        ]
            ]));
            ?>

</div>