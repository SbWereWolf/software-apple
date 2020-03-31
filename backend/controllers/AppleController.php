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
    public function actionIndex()
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
        ]);
    }

    /**
     * @return Response
     * @throws \yii\db\Exception
     */
    public function actionNextTick()
    {
        Manager::nextTick();

        return $this->redirect(Url::to(['/apple/index']));
    }

    /**
     * @return Response
     * @throws \yii\db\Exception
     */
    public function actionGenerate()
    {

        $connection = Yii::$app->getDb();
        $trans = $connection->beginTransaction();
        Apple::deleteAll();
        for ($iteration = 0; $iteration < mt_rand(4, 9); $iteration++) {
            Manager::generate();
        }
        $trans->commit();

        return $this->redirect(Url::to(['/apple/index']));
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     */
    public function actionFall($id)
    {
        $apple = $this->findModel($id);
        try {
            (new Manager($apple))->fall();
        } catch (Exception $e) {
            return $this->render(
                'error', ['message' => $e->getMessage()]);
        }

        return $this->redirect(Url::to(['/apple/index']));
    }

    /**
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionEat()
    {
        $param = Yii::$app->getRequest()->getBodyParams();

        $apple = $this->findModel($param['id']);
        (new Manager($apple))->eat($param['piece']);

        return Json::encode('OK');
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
