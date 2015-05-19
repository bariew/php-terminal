<?php

namespace app\controllers;

use app\models\Terminal;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\Response;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new Terminal();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->evaluate();
        }
        return $this->render('index', compact('model'));
    }

    public function actionSearch($term)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Terminal::search($term);
    }
}
