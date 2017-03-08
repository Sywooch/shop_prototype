<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\{AbstractBaseForm,
    UserChangePasswordForm};
use app\finders\UserEmailFinder;
use app\validators\StripTagsValidator;

/**
 * Обрабатывает запрос данных
 * для рендеринга страницы с формой смены пароля
 */
class AdminUserPasswordRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных
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
            if (filter_var($userEmail, FILTER_VALIDATE_EMAIL) === false) {
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
                
                $dataArray = [];
                
                $userChangePasswordForm = new UserChangePasswordForm(['id'=>$usersModel->id]);
                
                $dataArray['adminChangeUserPasswordWidgetConfig'] = $this->adminChangeUserPasswordWidgetConfig($userChangePasswordForm);
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
     * Возвращает массив конфигурации для виджета AccountChangePasswordWidget
     * @param AbstractBaseForm $userChangePasswordForm
     * @return array
     */
    private function adminChangeUserPasswordWidgetConfig(AbstractBaseForm $userChangePasswordForm)
    {
        try {
            $dataArray = $this->accountChangePasswordWidgetConfig($userChangePasswordForm);
            $dataArray['template'] = 'admin-user-password-change-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
