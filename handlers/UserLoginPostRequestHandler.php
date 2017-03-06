<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\forms\UserLoginForm;
use app\finders\UserEmailFinder;
use app\helpers\HashHelper;
use app\models\UserIpModel;
use app\savers\SessionModelSaver;

/**
 * Обрабатывает запрос на обработку данных для аутентификации
 */
class UserLoginPostRequestHandler extends AbstractBaseHandler
{
    /**
     * Аутентифицирует пользователя
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $form = new UserLoginForm(['scenario'=>UserLoginForm::LOGIN]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $finder = \Yii::$app->registry->get(UserEmailFinder::class, [
                        'email'=>$form->email
                    ]);
                    $usersModel = $finder->find();
                    if (empty($usersModel)) {
                        throw new ErrorException($this->emptyError('usersModel'));
                    }
                    
                    \Yii::$app->user->login($usersModel);
                    
                    $userIpModel = new UserIpModel(['scenario'=>UserIpModel::SESSION]);
                    $userIpModel->ip = $request->getUserIP();
                    if ($userIpModel->validate() === false) {
                        throw new ErrorException($this->modelError($userIpModel->errors));
                    }
                    
                    $saver = new SessionModelSaver([
                        'key'=>HashHelper::createSessionIpKey(),
                        'model'=>$userIpModel
                    ]);
                    $saver->save();
                    
                    /*$session = \Yii::$app->session;
                    $session->open();
                    $session->set(HashHelper::createSessionIpKey(), $request->getUserIP());
                    $session->close();*/
                    
                    return Url::to(['/products-list/index']);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
