<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\forms\UserLoginForm;
use app\finders\UserEmailFinder;

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
                    
                    return Url::to(['/products-list/index']);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
