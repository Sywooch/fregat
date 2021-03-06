<?php
use app\models\Config\Profile;
use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

\Yii::$app->getView()->registerJsFile('@web/js/authuserfilter.js' . Proc::appendTimestampUrlParam(Yii::$app->basePath . '/web/js/authuserfilter.js'));

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\BuildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $emp ? 'Сотрудники' : 'Пользователи';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
    <div class="authuser-index">
        <?php
        $result = Proc::GetLastBreadcrumbsFromSession();
        $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

        $profile_pol = Profile::VariablesValues('profile_pol');

        echo DynaGrid::widget(Proc::DGopts([
            'options' => ['id' => 'authusergrid'],
            'columns' => Proc::DGcols([
                'columns' => array_merge([
                    'auth_user_id',
                    'auth_user_fullname',
                ], $emp ? [] : ['auth_user_login'], Yii::$app->user->can('Administrator') ? [
                    [
                        'attribute' => 'profile.profile_pol',
                        'filter' => $profile_pol,
                        'value' => function ($model) use ($profile_pol) {
                            return isset($profile_pol[$model->profile->profile_pol]) ? $profile_pol[$model->profile->profile_pol] : '';
                        },
                        'visible' => false,
                    ],
                    [
                        'attribute' => 'profile.profile_dr',
                        'format' => 'date',
                        'visible' => false,
                    ],
                    [
                        'attribute' => 'profile.profile_address',
                        'visible' => false,
                    ],
                    [
                        'attribute' => 'profile.profile_inn',
                        'visible' => false,
                    ],
                    [
                        'attribute' => 'profile.profile_snils',
                        'visible' => false,
                    ],
                ] : []),
                'buttons' => array_merge(Yii::$app->user->can('UserEdit') && !$emp ? [
                    'changepassword' => function ($url, $model, $key) {
                        $customurl = Url::to(['Config/authuser/changepassword', 'id' => $model['auth_user_id']]);
                        return \yii\helpers\Html::a('<i class="glyphicon glyphicon-lock"></i>', $customurl, ['title' => 'Изменить пароль', 'class' => 'btn btn-xs btn-info', 'data-pjax' => '0']);
                    },
                ] : [], Yii::$app->user->can('UserEdit') || Yii::$app->user->can('EmployeeEdit') || Yii::$app->user->can('EmployeeSpecEdit') ? [
                    'update' => function ($url, $model) use ($emp) {
                        $customurl = Yii::$app->getUrlManager()->createUrl(['Config/authuser/update', 'id' => $model['auth_user_id'], 'emp' => $emp]);
                        return \yii\helpers\Html::a('<i class="glyphicon glyphicon-pencil"></i>', $customurl, ['title' => 'Обновить', 'class' => 'btn btn-xs btn-warning', 'data-pjax' => '0']);
                    },
                ] : [], Yii::$app->user->can('UserEdit') ? [
                    'deleteajax' => ['Config/authuser/delete', 'auth_user_id'],
                ] : []
                ),
            ]),
            'gridOptions' => [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'heading' => '<i class="glyphicon glyphicon-user"></i> ' . $this->title,
                    'before' => Yii::$app->user->can('UserEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                ],
                'toolbar' => [
                    'base' => ['content' => \yii\bootstrap\Html::a('<i class="glyphicon glyphicon-filter"></i>', ['authuserfilter'], [
                            'title' => 'Дополнительный фильтр',
                            'class' => 'btn btn-default filter_button'
                        ]) . '{export}{dynagrid}',
                    ],
                ],
                'afterHeader' => $filter,
            ]
        ]));
        ?>

    </div>
<?php
yii\bootstrap\Modal::begin([
    'header' => 'Дополнительный фильтр',
    'id' => 'AuthuserFilter',
    'options' => [
        'class' => 'modal_filter',
        'tabindex' => false, // чтобы работал select2 в модальном окне
    ],
]);
yii\bootstrap\Modal::end();
?>