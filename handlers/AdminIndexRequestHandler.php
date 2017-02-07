<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\services\{GetAdminTodayOrdersWidgetConfigService,
    GetAverageBillWidgetConfigService,
    GetConversionWidgetConfigService,
    GetCurrentCurrencyModelService,
    GetPopularGoodsWidgetConfigService,
    GetVisitsMinimalWidgetConfigService,
    GetVisitsWidgetConfigService};
use app\finders\{PopularProductsFinder,
    PurchasesTodayFinder,
    VisitorsCounterDateFinder};
use app\helpers\DateHelper;
use app\collections\PurchasesCollection;
use app\models\VisitorsCounterInterface;

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
    public function handle($request=null)
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
                $numberVisits = $visitorsCounterModel->getVisits();
                
                $dataArray = [];
                
                $dataArray['adminTodayOrdersMinimalWidgetConfig'] = $this->adminTodayOrdersMinimalWidgetConfig($numberOrders);
                
                /*$service = \Yii::$app->registry->get(GetVisitsMinimalWidgetConfigService::class);
                $dataArray['visitsMinimalWidgetConfig'] = $service->handle();*/
                $dataArray['visitsMinimalWidgetConfig'] = $this->visitsMinimalWidgetConfig($numberVisits);
                
                /*$service = \Yii::$app->registry->get(GetConversionWidgetConfigService::class);
                $dataArray['conversionWidgetConfig'] = $service->handle();*/
                $dataArray['conversionWidgetConfig'] = $this->conversionWidgetConfig($numberOrders, $numberVisits);
                
                /*$service = \Yii::$app->registry->get(GetAverageBillWidgetConfigService::class);
                $dataArray['averageBillWidgetConfig'] = $service->handle();*/
                $dataArray['averageBillWidgetConfig'] = $this->averageBillWidgetConfig($ordersCollection);
                
                /*$service = \Yii::$app->registry->get(GetPopularGoodsWidgetConfigService::class);
                $dataArray['popularGoodsWidgetConfig'] = $service->handle();*/
                $dataArray['popularGoodsWidgetConfig'] = $this->popularGoodsWidgetConfig();
                
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
     * @param PurchasesCollection $ordersCollection коллекция заказов
     * @return array
     */
    private function averageBillWidgetConfig(PurchasesCollection $ordersCollection): array
    {
        try {
            $dataArray = [];
            
            $dataArray['purchases'] = $ordersCollection;
            
            $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
            $dataArray['currency'] = $service->get();
               
            $dataArray['header'] = \Yii::t('base', 'Average bill');
            $dataArray['template'] = 'average-bill.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета PopularGoodsWidget
     * @return array
     */
    private function popularGoodsWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Popular goods');
            
            $finder = \Yii::$app->registry->get(PopularProductsFinder::class);
            $dataArray['goods'] = $finder->find();
            
            $dataArray['template'] = 'popular-goods.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
