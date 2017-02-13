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
                
                $timestamp = $request->post('timestamp') ?? null;
                if (empty($timestamp)) {
                    throw new ErrorException($this->emptyError('timestamp'));
                }
                
                $dateTime = new \DateTime();
                $dateTime->setTimestamp($timestamp);
                
                $calendarWidgetConfig = $this->calendarWidgetConfig($dateTime);
                return CalendarWidget::widget($calendarWidgetConfig);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CalendarWidget
     * @param DateTime $dateTime
     * @return array
     */
    private function calendarWidgetConfig(\DateTime $dateTime): array
    {
        try {
            $dataArray = [];
            
            $dataArray['period'] = new \DateTime(sprintf('%s-%s-1', $dateTime->format('Y'), $dateTime->format('m')));
            $dataArray['template'] = 'calendar.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
