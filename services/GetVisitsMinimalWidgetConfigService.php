<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\VisitorsCounterDateFinder;
use app\helpers\DateHelper;

/**
 * Возвращает массив конфигурации для виджета VisitsMinimalWidget
 */
class GetVisitsMinimalWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета VisitsMinimalWidget
     */
    private $visitsMinimalWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->visitsMinimalWidgetArray)) {
                $dataArray = [];
                
                $dataArray['header'] = \Yii::t('base', 'Visits');
                
                $finder = \Yii::$app->registry->get(VisitorsCounterDateFinder::class, ['date'=>DateHelper::getToday00()]);
                $visitors = $finder->find();
                
                if (!empty($visitors)) {
                    $dataArray['visitors'] = $visitors->counter;
                }
               
                $dataArray['template'] = 'visits-minimal.twig';
                
                $this->visitsMinimalWidgetArray = $dataArray;
            }
            
            return $this->visitsMinimalWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
