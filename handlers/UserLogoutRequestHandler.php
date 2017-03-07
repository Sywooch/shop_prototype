<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\forms\UserLoginForm;
use app\removers\SessionRemover;
use app\helpers\HashHelper;

/**
 * Обрабатывает запрос на обнуление данных аутентификации пользователя
 */
class UserLogoutRequestHandler extends AbstractBaseHandler
{
    /**
     * Очищает данные аутентификации пользователя
     * @param array $request
     * @return string URL
     */
    public function handle($request): string
    {
        try {
            $form = new UserLoginForm(['scenario'=>UserLoginForm::LOGOUT]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    if ((int) $form->id === (int) \Yii::$app->user->id) {
                        \Yii::$app->user->logout();
                    }
                    
                    $remover = new SessionRemover([
                        'keys'=>[HashHelper::createSessionIpKey()]
                    ]);
                    $remover->remove();
                    
                    return Url::to(['/products-list/index']);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
