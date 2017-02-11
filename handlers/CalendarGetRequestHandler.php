<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use app\handlers\AbstractBaseHandler;
use app\widgets\CalendarWidget;

/**
 * Обрабатывает запрос на данные календаря
 */
class CalendarGetRequestHandler extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на создание календаря
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if ($request->isAjax === true) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                
                $timestamp = $request->post('timestamp');
                if (empty($timestamp)) {
                    throw new ErrorException($this->emptyError('timestamp'));
                }
                
                $calendarWidgetConfig = $this->calendarWidgetConfig($timestamp);
                return CalendarWidget::widget($calendarWidgetConfig);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CalendarWidget
     * @param int $timestamp Unix Timestamp
     * @return array
     */
    private function calendarWidgetConfig(int $timestamp): array
    {
        try {
            $dataArray = [];
            
            $dateTime = new \DateTime();
            $dateTime->setTimestamp($timestamp);
            
            $dataArray['period'] = new \DateTime(sprintf('%s-%s-1', $dateTime->format('Y'), $dateTime->format('m')));
            $dataArray['template'] = 'calendar.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
