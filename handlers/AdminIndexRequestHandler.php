<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\services\GetCurrentCurrencyModelService;
use app\finders\{PopularProductsFinder,
    PurchasesTodayFinder,
    VisitorsCounterDateFinder};
use app\helpers\{DateHelper,
    HashHelper};
use app\collections\PurchasesCollectionInterface;
use app\models\CurrencyInterface;

/**
 * Обрабатывает запрос на получение данных 
 * с основными данными админ раздела
 */
class AdminIndexRequestHandler extends AbstractBaseHandler
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if (empty($this->dataArray)) {
                $finder = \Yii::$app->registry->get(PurchasesTodayFinder::class);
                $ordersCollection = $finder->find();
                
                $numberOrders = $ordersCollection->count();
                
                $finder = \Yii::$app->registry->get(VisitorsCounterDateFinder::class, [
                    'date'=>DateHelper::getToday00()
                ]);
                $visitorsCounterModel = $finder->find();
                $numberVisits = !empty($visitorsCounterModel) ? $visitorsCounterModel->counter : 0;
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                    'key'=>HashHelper::createCurrencyKey()
                ]);
                $currentCurrencyModel = $service->get();
                if (empty($currentCurrencyModel)) {
                    throw new ErrorException($this->emptyError('currentCurrencyModel'));
                }
                
                $finder = \Yii::$app->registry->get(PopularProductsFinder::class);
                $popularProductsArray = $finder->find();
                if (empty($popularProductsArray)) {
                    throw new ErrorException($this->emptyError('popularProductsArray'));
                }
                
                $dataArray = [];
                
                $dataArray['adminTodayOrdersMinimalWidgetConfig'] = $this->adminTodayOrdersMinimalWidgetConfig($numberOrders);
                $dataArray['visitsMinimalWidgetConfig'] = $this->visitsMinimalWidgetConfig($numberVisits);
                $dataArray['conversionWidgetConfig'] = $this->conversionWidgetConfig($numberOrders, $numberVisits);
                $dataArray['averageBillWidgetConfig'] = $this->averageBillWidgetConfig($ordersCollection, $currentCurrencyModel);
                $dataArray['popularGoodsWidgetConfig'] = $this->popularProductsWidgetConfig($popularProductsArray);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminTodayOrdersMinimalWidget
     * @param int $numberOrders количество заказов
     * @return array
     */
    private function adminTodayOrdersMinimalWidgetConfig(int $numberOrders):array
    {
        try {
            $dataArray = [];
            
            $dataArray['orders'] = $numberOrders;
            $dataArray['header'] = \Yii::t('base', 'Orders');
            $dataArray['template'] = 'admin-today-orders-minimal.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета VisitsMinimalWidget
     * @param int $numberVisits количество посещений
     * @return array
     */
    private function visitsMinimalWidgetConfig(int $numberVisits)
    {
        try {
            $dataArray = [];
            
            $dataArray['visits'] = $numberVisits;
            $dataArray['template'] = 'visits-minimal.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ConversionWidget
     * @param int $numberOrders количество заказов
     * @param int $numberVisits количество посещений
     * @return array
     */
    private function conversionWidgetConfig(int $numberOrders, int $numberVisits): array
    {
        try {
            $dataArray = [];
            
            $dataArray['purchases'] = $numberOrders;
            $dataArray['visits'] = $numberVisits;
            $dataArray['template'] = 'conversion.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AverageBillWidget
     * @param PurchasesCollectionInterface $ordersCollection коллекция заказов
     * @param CurrencyInterface $currentCurrencyModel
     * @return array
     */
    private function averageBillWidgetConfig(PurchasesCollectionInterface $ordersCollection, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['purchases'] = $ordersCollection;
            
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['header'] = \Yii::t('base', 'Average bill');
            $dataArray['template'] = 'average-bill.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета PopularGoodsWidget
     * @param array $popularProductsArray
     * @return array
     */
    private function popularProductsWidgetConfig(array $popularProductsArray): array
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Popular goods');
            $dataArray['goods'] = $popularProductsArray;
            $dataArray['template'] = 'popular-goods.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
