<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\UserUpdateForm;

/**
 * Обрабатывает запрос на получение 
 * формы редактирования данных клиента
 */
class AccountChangeDataRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы с настройками аккаунта
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if (empty($this->dataArray)) {
                $usersModel = \Yii::$app->user->identity;
                
                $userUpdateForm = new UserUpdateForm(['scenario'=>UserUpdateForm::UPDATE]);
                
                $dataArray = [];
                
                $dataArray['accountChangeDataWidgetConfig'] = $this->accountChangeDataWidgetConfig($userUpdateForm, $usersModel);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
