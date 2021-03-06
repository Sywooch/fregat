<?php

namespace app\controllers\Fregat;

use app\func\ReportsTemplate\RecoverysendaktmatReport;
use app\func\ReportTemplates;
use app\models\Config\Generalsettings;
use app\models\Fregat\Fregatsettings;
use app\models\Fregat\Recoveryrecieveaktmat;
use app\models\Fregat\RecoverysendaktFilter;
use Exception;
use Yii;
use app\models\Fregat\Recoverysendakt;
use app\models\Fregat\RecoverysendaktSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;
use app\models\Fregat\Recoveryrecieveakt;
use app\models\Fregat\RecoveryrecieveaktSearch;
use app\func\ReportsTemplate\RecoverysendaktReport;
use app\models\Fregat\RecoveryrecieveaktmatSearch;

/**
 * RecoverysendaktController implements the CRUD actions for Recoverysendakt model.
 */
class RecoverysendaktController extends Controller
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
                        'actions' => ['index', 'recoverysendakt-report', 'recoverysendaktmat-report', 'toexcel', 'recoverysendakt-reportsend', 'recoverysendaktmat-reportsend', 'recoverysendaktfilter'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['RecoveryEdit'],
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

    public function actionIndex()
    {
        $searchModel = new RecoverysendaktSearch();
        $filter = Proc::SetFilter($searchModel->formName(), new RecoverysendaktFilter);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ]);
    }

    public function actionCreate()
    {
        $model = new Recoverysendakt();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)
            return $this->redirect(['update', 'id' => $model->recoverysendakt_id]);
        } else {
            $model->recoverysendakt_date = empty($model->recoverysendakt_date) ? date('Y-m-d') : $model->recoverysendakt_date;

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            $searchModel = new RecoveryrecieveaktSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $searchModelmat = new RecoveryrecieveaktmatSearch();
            $dataProvidermat = $searchModelmat->search(Yii::$app->request->queryParams);

            $generalsettings = Fregatsettings::findOne(1);

            return $this->render('update', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'searchModelmat' => $searchModelmat,
                'dataProvidermat' => $dataProvidermat,
                'emailfrom' => $generalsettings->fregatsettings_recoverysend_emailfrom,
                'emailtheme' => $generalsettings->fregatsettings_recoverysend_emailtheme,
            ]);
        }
    }

    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                Recoveryrecieveakt::deleteAll(['id_recoverysendakt' => $id]);
                Recoveryrecieveaktmat::deleteAll(['id_recoverysendakt' => $id]);

                $Recoverysendakt = $this->findModel($id)->delete();

                if ($Recoverysendakt === false)
                    throw new Exception('Удаление невозможно.');

                echo $Recoverysendakt;

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage() . ' Удаление невозможно.');
            }
        }
    }

    // Печать акта осмотра
    public function actionRecoverysendaktReport()
    {
        $Report = new RecoverysendaktReport();
        echo $Report->Execute();
    }

    // Печать акта отправки материалов сторонней организации
    public function actionRecoverysendaktmatReport()
    {
        $Report = new RecoverysendaktmatReport();
        echo $Report->Execute();
    }

    public function actionRecoverysendaktReportsend()
    {
        Proc::SendReportAkt(1);
    }

    public function actionRecoverysendaktmatReportsend()
    {
        Proc::SendReportAkt(2);
    }

    public function actionToexcel()
    {
        //   $searchModel = new PatientSearch();
        $params = Yii::$app->request->queryParams;
        $inputdata = json_decode($params['inputdata']);
        //   $modelname = $searchModel->formName();
        //   $dataProvider = $searchModel->search(Proc::GetArrayValuesByKeyName($modelname, $inputdata));
        $selectvalues = json_decode($params['selectvalues']);
        $labelvalues = isset($params['labelvalues']) ? json_decode($params['labelvalues']) : NULL;

        ReportTemplates::Recoverysendakt_ExportExcel();
        //  Proc::Grid2Excel($dataProvider, $modelname, 'Список пациентов', $selectvalues, new PatientFilter, $labelvalues);
    }

    public function actionRecoverysendaktfilter()
    {
        $model = new RecoverysendaktFilter;

        if (Yii::$app->request->isAjax && Yii::$app->request->isGet) {
            Proc::PopulateFilterForm('RecoverysendaktSearch', $model);

            return $this->renderAjax('_recoverysendaktfilter', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Recoverysendakt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Recoverysendakt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Recoverysendakt::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
