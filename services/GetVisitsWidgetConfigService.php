<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\VisitorsCounterFinder;

/**
 * Возвращает массив конфигурации для виджета VisitsWidget
 */
class GetVisitsWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета VisitsWidget
     */
    private $visitsWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->visitsWidgetArray)) {
                $dataArray = [];
                
                $dataArray['header'] = \Yii::t('base', 'Visits');
                
                $finder = \Yii::$app->registry->get(VisitorsCounterFinder::class);
                $dataArray['visitors'] = $finder->find();
               
                $dataArray['view'] = 'visits.twig';
                
                $this->visitsWidgetArray = $dataArray;
            }
            
            return $this->visitsWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
