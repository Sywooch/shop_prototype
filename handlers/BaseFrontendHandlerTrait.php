<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\services\GetCurrentCurrencyModelService;
use app\helpers\HashHelper;
use app\models\CurrencyInterface;
use app\finders\{CategoriesFinder,
    CurrencyFinder,
    PurchasesSessionFinder};
use app\forms\ChangeCurrencyForm;

/**
 * Коллекция базовых методов
 */
trait BaseFrontendHandlerTrait
{
    /**
     * Возвращает CurrencyInterface объект текущей валюты
     * @return CurrencyInterface
     */
    private function getCurrentCurrency(): CurrencyInterface
    {
        try {
            $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                'key'=>HashHelper::createCurrencyKey()
            ]);
            $currentCurrencyModel = $service->get();
            
            if (empty($currentCurrencyModel)) {
                throw new ErrorException($this->emptyError('currentCurrencyModel'));
            }
            
            return $currentCurrencyModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета UserInfoWidget
     * @return array
     */
    private function userInfoWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $dataArray['user'] = \Yii::$app->user;
            $dataArray['template'] = 'user-info.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ShortCartWidget
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    private function shortCartWidgetConfig(CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, [
                'key'=>HashHelper::createCartKey()
            ]);
            $ordersCollection = $finder->find();
            
            $dataArray['purchases'] = $ordersCollection;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['template'] = 'short-cart.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CurrencyWidget
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    private function currencyWidgetConfig(CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(CurrencyFinder::class);
            $currencyArray = $finder->find();
            if (empty($currencyArray)) {
                throw new ErrorException($this->emptyError('currencyArray'));
            }
            ArrayHelper::multisort($currencyArray, 'code');
            $dataArray['currency'] = ArrayHelper::map($currencyArray, 'id', 'code');
            
            $dataArray['form'] = new ChangeCurrencyForm([
                'scenario'=>ChangeCurrencyForm::SET,
                'id'=>$currentCurrencyModel->id,
                'url'=>Url::current()
            ]);
            
            $dataArray['header'] = \Yii::t('base', 'Currency');
            $dataArray['template'] = 'currency-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета SearchWidget
     * @param string $searchKey искомая фраза
     * @return array
     */
    private function searchWidgetConfig(string $searchKey=''): array
    {
        try {
            $dataArray = [];
            
            $dataArray['text'] = $searchKey;
            $dataArray['template'] = 'search.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CategoriesMenuWidget
     * @return array
     */
    private function categoriesMenuWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(CategoriesFinder::class);
            $categoriesArray = $finder->find();
            
            if (empty($categoriesArray)) {
                throw new ErrorException($this->emptyError('categoriesArray'));
            }
            
            $dataArray['categories'] = $categoriesArray;
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
