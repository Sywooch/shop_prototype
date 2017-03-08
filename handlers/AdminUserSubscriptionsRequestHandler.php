<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\{MailingsEmailFinder,
    MailingsNotEmailFinder,
    UserEmailFinder};
use app\forms\AdminUserMailingForm;
use app\validators\StripTagsValidator;

/**
 * Обрабатывает запрос на данные 
 * для рендеринга страницы с данными о подписках
 */
class AdminUserSubscriptionsRequestHandler extends AbstractBaseHandler
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
                
                $finder = \Yii::$app->registry->get(MailingsEmailFinder::class, [
                    'email'=>$userEmail
                ]);
                $mailingsArray = $finder->find();
                
                $finder = \Yii::$app->registry->get(MailingsNotEmailFinder::class, [
                    'email'=>$userEmail
                ]);
                $notMailingsArray = $finder->find();
                
                $mailingForm = new AdminUserMailingForm(['id_user'=>$usersModel->id]);
                $notAdminUserMailingForm = new AdminUserMailingForm(['id_user'=>$usersModel->id]);
                
                $dataArray = [];
                
                $dataArray['adminUserMailingsUnsubscribeWidgetConfig'] = $this->adminUserMailingsUnsubscribeWidgetConfig($mailingsArray, $mailingForm);
                $dataArray['adminUserMailingsFormWidgetConfig'] = $this->adminUserMailingsFormWidgetConfig($notMailingsArray, $notAdminUserMailingForm);
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
