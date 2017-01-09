<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\AbstractBaseService;
use app\forms\UserLoginForm;
use app\finders\UserEmailFinder;

/**
 * Аутентифицирует пользователя
 */
class UserLoginService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на обработку данных для аутентификации
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $form = new UserLoginForm(['scenario'=>UserLoginForm::LOGIN]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($form);
                }
            }
            
            if ($request->isPost === true) {
                if ($form->load($request->post()) === true) {
                    if ($form->validate() === true) {
                        $finder = \Yii::$app->registry->get(UserEmailFinder::class, ['email'=>$form->email]);
                        $usersModel = $finder->find();
                        
                        if (empty($usersModel)) {
                            throw new ErrorException($this->emptyError('usersModel'));
                        }
                        
                        \Yii::$app->user->login($usersModel);
                        
                        return Url::to(['/products-list/index']);
                    }
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
