<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\{MailingsEmailFinder,
    PurchasesIdUserFinder};
use app\models\{CurrencyInterface,
    UsersModel};
use app\services\GetCurrentCurrencyModelService;
use app\helpers\HashHelper;

/**
 * Обрабатывает запрос на получение данных 
 * для рендеринга страницы с настройками аккаунта
 */
class AccountIndexRequestHandler extends AbstractBaseHandler
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
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AccountContactsWidget
     * @param UsersModel $usersModel
     * @return array
     */
    private function accountContactsWidgetConfig(UsersModel $usersModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['user'] = $usersModel;
            $dataArray['header'] = \Yii::t('base', 'Current contact details');
            $dataArray['template'] = 'account-contacts.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AccountCurrentOrdersWidget
     * @param array $purchasesArray
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    private function accountCurrentOrdersWidgetConfig(array $purchasesArray, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Current orders');
            $dataArray['purchases'] = $purchasesArray;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['template'] = 'account-current-orders.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета MailingsWidget
     * @param array $mailingsArray
     * @return array
     */
    private function accountMailingsWidgetConfig(array $mailingsArray): array
    {
        try {
            $dataArray = [];
            
            $dataArray['mailings'] = $mailingsArray;
            $dataArray['header'] = \Yii::t('base', 'Current subscriptions');
            $dataArray['template'] = 'mailings.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
