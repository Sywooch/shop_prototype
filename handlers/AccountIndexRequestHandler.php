<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\{AbstractBaseHandler,
    BaseHandlerTrait};
use app\finders\{MailingsEmailFinder,
    PurchasesIdUserFinder};
use app\models\{CurrencyInterface,
    UsersModel};

/**
 * Обрабатывает запрос на получение данных 
 * для рендеринга страницы с настройками аккаунта
 */
class AccountIndexRequestHandler extends AbstractBaseHandler
{
    use BaseHandlerTrait;
    
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
                $currentCurrencyModel = $this->getCurrentCurrency();
                
                $dataArray = [];
                
                $dataArray['accountContactsWidgetConfig'] = $this->accountContactsWidgetConfig($usersModel);
                $dataArray['accountCurrentOrdersWidgetConfig'] = $this->accountCurrentOrdersWidgetConfig($usersModel->id, $currentCurrencyModel);
                $dataArray['accountMailingsWidgetConfig'] = $this->accountMailingsWidgetConfig($usersModel->email->email);
                
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
     * @param int $id пользователя
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    private function accountCurrentOrdersWidgetConfig(int $id, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Current orders');
            
            $finder = \Yii::$app->registry->get(PurchasesIdUserFinder::class, [
                'id_user'=>$id
            ]);
            $dataArray['purchases'] = $finder->find();
            
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['template'] = 'account-current-orders.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета MailingsWidget
     * @param string $email пользователя
     * @return array
     */
    private function accountMailingsWidgetConfig(string $email): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(MailingsEmailFinder::class, [
                'email'=>$email
            ]);
            $dataArray['mailings'] = $finder->find();
            
            $dataArray['header'] = \Yii::t('base', 'Current subscriptions');
            $dataArray['template'] = 'mailings.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
