<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает массив конфигурации для виджета CalendarWidget
 */
class GetCalendarWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета CalendarWidget
     */
    private $calendarWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            $timestamp = $request->post('timestamp') ?? null;
            
            if (empty($timestamp)) {
                throw new ErrorException($this->emptyError('timestamp'));
            }
            
            if (empty($this->calendarWidgetArray)) {
                $dataArray = [];
                
                $dateTime = new \DateTime();
                $dateTime->setTimestamp($timestamp);
                
                $dataArray['period'] = new \DateTime(sprintf('%s-%s-1', $dateTime->format('Y'), $dateTime->format('m')));
                $dataArray['template'] = 'calendar.twig';
                
                $this->calendarWidgetArray = $dataArray;
            }
            
            return $this->calendarWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
