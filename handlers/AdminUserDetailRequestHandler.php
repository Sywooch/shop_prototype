<?php

namespace app\handlers;

use yii\base\{ErrorException,
    Model};
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\{MailingsEmailFinder,
    PurchasesIdUserFinder,
    UserIdFinder};
use app\services\GetCurrentCurrencyModelService;
use app\helpers\HashHelper;

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
            $userId = $request->get(\Yii::$app->params['userId']) ?? null;
            if (empty($userId)) {
                throw new ErrorException($this->emptyError('userId'));
            }
            
            if (empty($this->dataArray)) {
                $finder = \Yii::$app->registry->get(UserIdFinder::class, [
                    'id'=>$userId
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
