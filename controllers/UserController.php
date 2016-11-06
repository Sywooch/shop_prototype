<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\controllers\{AbstractBaseController,
    UserControllerHelper};

/**
 * Управляет работой с пользователями
 */
class UserController extends AbstractBaseController
{
    /**
     * Управляет процессом аутентификации
     */
    public function actionLogin()
    {
        try {
            if (\Yii::$app->request->isPost) {
                if (UserControllerHelper::loginPost()) {
                    return $this->redirect(Url::to(['/products-list/index']));
                }
            }
            
            $renderArray = UserControllerHelper::loginGet();
            
            return $this->render('login.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом logout
     */
    public function actionLogout()
    {
        try {
            if (\Yii::$app->request->isPost) {
                UserControllerHelper::logoutPost();
            }
            
            return $this->redirect(Url::to(['/products-list/index']));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом создания учетной записи
     */
    public function actionRegistration()
    {
        try {
            if (\Yii::$app->request->isPost) {
                if (UserControllerHelper::registrationPost()) {
                    return $this->redirect(Url::to(['/user/login']));
                }
            }
            
            $renderArray = UserControllerHelper::registrationGet();
            
            return $this->render('registration.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом смены пароля
     */
    public function actionForgot()
    {
        try {
            if (\Yii::$app->request->isPost) {
                UserControllerHelper::forgotPost();
            }
            
            $renderArray = UserControllerHelper::forgotGet();
            
            return $this->render('forgot.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function behaviors()
    {
        return [
            [
                'class'=>'app\filters\CurrencyFilter',
                'only'=>['login', 'registration'],
            ],
            [
                'class'=>'app\filters\CartFilter',
                'only'=>['login', 'registration'],
            ],
        ];
    }
}
