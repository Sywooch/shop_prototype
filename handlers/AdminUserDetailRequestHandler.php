<?php

namespace app\handlers;

use yii\base\{ErrorException,
    Model};
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\{MailingsEmailFinder,
    PurchasesIdUserFinder,
    UserEmailFinder};
use app\services\GetCurrentCurrencyModelService;
use app\helpers\HashHelper;
use app\validators\StripTagsValidator;

/**
 * Обрабатывает запрос на получение данных 
 * для рендеринга страницы с настройками аккаунта
 */
class AdminUserDetailRequestHandler extends AbstractBaseHandler
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
            $validator = new StripTagsValidator();
            $userEmail = $validator->validate($userEmail);
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
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                    'key'=>HashHelper::createCurrencyKey()
                ]);
                $currentCurrencyModel = $service->get();
                if (empty($currentCurrencyModel)) {
                    throw new ErrorException($this->emptyError('currentCurrencyModel'));
                }
                
                $finder = \Yii::$app->registry->get(PurchasesIdUserFinder::class, [
                    'id_user'=>$usersModel->id
                ]);
                $purchasesArray = $finder->find();
                
                $finder = \Yii::$app->registry->get(MailingsEmailFinder::class, [
                    'email'=>$usersModel->email->email
                ]);
                $mailingsArray = $finder->find();
                
                $dataArray = [];
                
                $dataArray['accountContactsWidgetConfig'] = $this->accountContactsWidgetConfig($usersModel);
                $dataArray['accountCurrentOrdersWidgetConfig'] = $this->accountCurrentOrdersWidgetConfig($purchasesArray, $currentCurrencyModel);
                $dataArray['accountMailingsWidgetConfig'] = $this->accountMailingsWidgetConfig($mailingsArray);
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
