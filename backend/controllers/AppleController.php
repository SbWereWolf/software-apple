<?php

namespace backend\controllers;

use app\Domain\Manager;
use app\Domain\Presenter;
use app\models\Apple;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AppleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'eat' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Apple models.
     * @return mixed
     */
    public function actionIndex($message = null)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Apple::find(),
        ]);

        $apples = $dataProvider->getModels();
        $data = [];
        foreach ($apples as $apple) {
            $data[] = Presenter::make($apple);
        }

        return $this->render('index', [
            'data' => $data,
            'message' => $message,
        ]);
    }

    /**
     * @return Response
     * @throws \yii\db\Exception
     */
    public function actionNextTick()
    {
        $message = '';
        try {
            Manager::nextTick();
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return $this->redirect(Url::to(['/apple/index',
            'message' => $message]));
    }

    /**
     * @return Response
     * @throws \yii\db\Exception
     */
    public function actionGenerate()
    {
        $message = '';
        try {
            $connection = Yii::$app->getDb();
            $trans = $connection->beginTransaction();
            Apple::deleteAll();
            $rounds = mt_rand(4, 9);
            for ($iteration = 0; $iteration < $rounds; $iteration++) {
                Manager::generate();
            }
            $trans->commit();
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return $this->redirect(Url::to(['/apple/index',
            'message' => $message]));
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     */
    public function actionFall($id)
    {
        $apple = $this->findModel($id);
        $message = '';
        try {
            (new Manager($apple))->fall();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return $this->redirect(Url::to(['/apple/index',
            'message' => $message]));
    }

    /**
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionEat()
    {
        $message = '';
        try {
            $param = Yii::$app->getRequest()->getBodyParams();

            $apple = $this->findModel($param['id']);
            (new Manager($apple))->eat($param['piece']);
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return Json::encode(['message' => $message]);
    }

    /**
     * @param $id
     * @return Apple|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Apple::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
