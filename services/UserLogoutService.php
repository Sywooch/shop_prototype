<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\services\AbstractBaseService;
use app\forms\UserLoginForm;

/**
 * Очищает данные аутентификации пользователя
 */
class UserLogoutService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на обнуление данных аутентификации пользователя
     * @param array $request
     * @return string URL
     */
    public function handle($request): string
    {
        try {
            $form = new UserLoginForm(['scenario'=>UserLoginForm::LOGOUT]);
            
            if ($form->load($request->post()) === false) {
                throw new ErrorException($this->emptyError('request'));
            }
            
            if ($form->validate() === false) {
                throw new ErrorException($this->modelError($form->errors));
            }
            
            if ((int) $form->id === (int) \Yii::$app->user->id) {
                \Yii::$app->user->logout();
            }
            
            return Url::to(['/products-list/index']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
