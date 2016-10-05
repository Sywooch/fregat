<?php

namespace app\controllers\Fregat;

use app\func\Proc;
use app\models\Fregat\SpisosnovmaterialsSearch;
use Yii;
use app\models\Fregat\Spisosnovakt;
use app\models\Fregat\SpisosnovaktSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SpisosnovaktController implements the CRUD actions for Spisosnovakt model.
 */
class SpisosnovaktController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'spisosnovakt-report'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['SpisosnovaktEdit'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Spisosnovakt models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SpisosnovaktSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Spisosnovakt model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Spisosnovakt();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)
            return $this->redirect(['update', 'id' => $model->spisosnovakt_id]);
        } else {
            $model->spisosnovakt_date = empty($model->spisosnovakt_date) ? date('Y-m-d') : $model->spisosnovakt_date;

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Spisosnovakt model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            $searchModel = new SpisosnovmaterialsSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
            return $this->render('update', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Deletes an existing Spisosnovakt model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Spisosnovakt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Spisosnovakt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Spisosnovakt::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
