<?php

namespace backend\controllers;

use backend\models\search\UserSearch;
use backend\models\UserForm;
use common\models\User;
use common\models\UserToken;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use backend\modules\rbac\models\RbacAuthAssignment;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \yii\base\Exception
     * @throws NotFoundHttpException
     */
    public function actionLogin($id)
    {
        $model = $this->findModel($id);
        $tokenModel = UserToken::create(
            $model->getId(),
            UserToken::TYPE_LOGIN_PASS,
            60
        );

        return $this->redirect(
            Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/sign-in/login-by-pass', 'token' => $tokenModel->token])
        );
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserForm();
        $model->setScenario('create');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        $roles = Yii::$app->params['roles'];
        $profileSelector = User::findByRole($roles['profileSelector']);
        $successManager = User::findByRole($roles['successManager']);
        $agent = User::findByRole($roles['agent']);
        $attorney = User::findByRole($roles['attorney']);
        $mortgageLender = User::findByRole($roles['mortgageLender']);

        return $this->render('create', [
            'model' => $model,
            'roles' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name'),
            'profileSelector'=>$profileSelector,
            'successManager'=>$successManager,
            'agent'=>$agent,
            'attorney'=>$attorney,
            'mortgageLender'=>$mortgageLender
        ]);
    }

    /**
     * Updates an existing User model.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new UserForm();
        $model->setModel($this->findModel($id));
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        $roles = Yii::$app->params['roles'];
        $profileSelector = User::findByRole($roles['profileSelector']);
        $successManager = User::findByRole($roles['successManager']);
        $agent = User::findByRole($roles['agent']);
        $attorney = User::findByRole($roles['attorney']);
        $mortgageLender = User::findByRole($roles['mortgageLender']);
        //print_r($successManager);die;
        return $this->render('update', [
            'model' => $model,
            'roles' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name'),
            'profileSelector'=>$profileSelector,
            'successManager'=>$successManager,
            'agent'=>$agent,
            'attorney'=>$attorney,
            'mortgageLender'=>$mortgageLender
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Yii::$app->authManager->revokeAll($id);
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


public function actionAjaxgettype() {
      $list = RbacAuthAssignment::find()->select(['user_id'])->where(['item_name'=>'Profile Selector'])->all();
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $out = ['results' => ['id' => '', 'text' => '']];
      foreach ($list as $key => $value) {
           //echo $value['user_id'];
      $query = new \yii\db\Query;
        $query->select('id, username AS text')
            ->from('user')
             ->where('id in ('.$value['user_id'].')')
             ->andwhere(['status'=>'2'])
            ->limit(20);
        $command = $query->createCommand();
        $data = $command->queryAll();
      }
      $out['results'] = array_values($data);
       return $out;
    }


public function actionSuccessmanager() {
      $list = RbacAuthAssignment::find()->select(['user_id'])->where(['item_name'=>''])->all();
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $out = ['results' => ['id' => '', 'text' => '']];
      foreach ($list as $key => $value) {
           //echo $value['user_id'];
      $query = new \yii\db\Query;
        $query->select('id, username AS text')
            ->from('user')
             ->where([ 'like', 'id', $value['user_id']])
             ->andwhere(['status'=>'2'])
            ->limit(20);
        $command = $query->createCommand();
        $data = $command->queryAll();
      }
      $out['results'] = array_values($data);
       return $out;
    }


public function actionEstateagent() {
      $list = RbacAuthAssignment::find()->select(['user_id'])->where(['item_name'=>''])->all();
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $out = ['results' => ['id' => '', 'text' => '']];
      foreach ($list as $key => $value) {
           //echo $value['user_id'];
      $query = new \yii\db\Query;
        $query->select('id, username AS text')
            ->from('user')
             ->where([ 'like', 'id', $value['user_id']])
             ->andwhere(['status'=>'2'])
            ->limit(20);
        $command = $query->createCommand();
        $data = $command->queryAll();
      }
      $out['results'] = array_values($data);
       return $out;
    }
    /**
     * Lists all User Role models.
     * @return mixed
     */
    public function actionRole()
    {
        print_r(Yii::$app->authManager);die;
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $role = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');
        $role = Yii::$app->authManager->getRoles();
        print_r($role);die;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
