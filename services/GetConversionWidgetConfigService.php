<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\{PurchasesTodayFinder,
    VisitorsCounterDateFinder};
use app\helpers\DateHelper;

/**
 * Возвращает массив конфигурации для виджета ConversionWidget
 */
class GetConversionWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета ConversionWidget
     */
    private $conversionWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->conversionWidgetArray)) {
                $dataArray = [];
                
                $dataArray['header'] = \Yii::t('base', 'Conversion');
                
                $finder = \Yii::$app->registry->get(PurchasesTodayFinder::class);
                $purchases = $finder->find();
                $dataArray['purchases'] = $purchases->count();
                
                $finder = \Yii::$app->registry->get(VisitorsCounterDateFinder::class, ['date'=>DateHelper::getToday00()]);
                $visitorsCounterModel = $finder->find();
                $dataArray['visits'] = $visitorsCounterModel->counter ?? 0;
                
                $dataArray['template'] = 'conversion.twig';
                
                $this->conversionWidgetArray = $dataArray;
            }
            
            return $this->conversionWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
