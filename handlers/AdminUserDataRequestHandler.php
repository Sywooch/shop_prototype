<?php

namespace app\handlers;

use yii\base\{ErrorException,
    Model};
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\{AbstractBaseForm,
    UserUpdateForm};
use app\finders\UserEmailFinder;
use app\validators\StripTagsValidator;

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
            
            $validate = new StripTagsValidator();
            $userEmail = $validate->validate($userEmail);
            
            $userEmail = filter_var($userEmail, FILTER_VALIDATE_EMAIL);
            if ($userEmail === false) {
                throw new ErrorException($this->invalidError('userEmail'));
            }
            
            if (empty($this->dataArray)) {
                $finder = \Yii::$app->registry->get(UserEmailFinder::class, [
                    'email'=>$userEmail
                ]);
                $usersModel = $finder->find();
                if (empty($usersModel)) {
                    throw new ErrorException($this->emptyError('usersModel'));
                }
                
                $userUpdateForm = new UserUpdateForm(['id'=>$usersModel->id]);
                
                $dataArray = [];
                
                $dataArray['adminChangeUserDataWidgetConfig'] = $this->adminChangeUserDataWidgetConfig($userUpdateForm, $usersModel);
                $dataArray['adminUserDetailBreadcrumbsWidgetConfig'] = $this->adminUserDetailBreadcrumbsWidgetConfig($usersModel);
                $dataArray['adminUserMenuWidgetConfig'] = $this->adminUserMenuWidgetConfig($usersModel);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminChangeUserDataWidget
     * @param AbstractBaseForm $userUpdateForm
     * @param Model $usersModel
     * @return array
     */
    private function adminChangeUserDataWidgetConfig(AbstractBaseForm $userUpdateForm, Model $usersModel): array
    {
        try {
            $dataArray = $this->accountChangeDataWidgetConfig($userUpdateForm, $usersModel);
            $dataArray['template'] = 'change-user-data-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
