<?php

namespace app\controllers\Config;

use Yii;
use app\models\Config\Authassignment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * AuthassignmentController implements the CRUD actions for Authassignment model.
 */
class AuthassignmentController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['UserEdit'],
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

    public function actionDelete($item_name, $user_id) {
        if (Yii::$app->request->isAjax)
            echo $this->findModel($item_name, $user_id)->delete();
    }

    /**
     * Finds the Authassignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $item_name
     * @param integer $user_id
     * @return Authassignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($item_name, $user_id) {
        if (($model = Authassignment::findOne(['item_name' => $item_name, 'user_id' => $user_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
