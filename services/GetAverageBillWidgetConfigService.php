<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetCurrentCurrencyModelService};
use app\finders\PurchasesTodayFinder;

/**
 * Возвращает массив конфигурации для виджета AverageBillWidget
 */
class GetAverageBillWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AverageBillWidget
     */
    private $averageBillWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->averageBillWidgetArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(PurchasesTodayFinder::class);
                $dataArray['purchases'] = $finder->find();
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
               
                $dataArray['header'] = \Yii::t('base', 'Average bill');
               
                $dataArray['template'] = 'average-bill.twig';
                
                $this->averageBillWidgetArray = $dataArray;
            }
            
            return $this->averageBillWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
