<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\UserUpdateForm;
use app\finders\UserEmailFinder;

/**
 * Обрабатывает запрос на получение 
 * формы редактирования данных клиента
 */
class AdminUserDataRequestHandler extends AbstractBaseHandler
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
            $userEmail = $request->get(\Yii::$app->params['userEmail']) ?? null;
            if (empty($userEmail)) {
                throw new ErrorException($this->emptyError('userEmail'));
            }
            
            if (empty($this->dataArray)) {
                $finder = \Yii::$app->registry->get(UserEmailFinder::class, [
                    'email'=>$userEmail
                ]);
                $usersModel = $finder->find();
                if (empty($usersModel)) {
                    throw new ErrorException($this->emptyError('usersModel'));
                }
                
                $userUpdateForm = new UserUpdateForm(['scenario'=>UserUpdateForm::UPDATE]);
                
                $dataArray = [];
                
                $dataArray['accountChangeDataWidgetConfig'] = $this->accountChangeDataWidgetConfig($userUpdateForm, $usersModel);
                $dataArray['adminUserDetailBreadcrumbsWidgetConfig'] = $this->adminUserDetailBreadcrumbsWidgetConfig($usersModel);
                $dataArray['adminUserMenuWidgetConfig'] = $this->adminUserMenuWidgetConfig($usersModel);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
