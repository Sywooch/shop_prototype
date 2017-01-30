<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use app\services\{AbstractBaseService,
    GetCalendarWidgetConfigService};
use app\widgets\CalendarWidget;

/**
 * Возвращает календарь
 */
class CalendarGetService extends AbstractBaseService
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
                
                $service = \Yii::$app->registry->get(GetCalendarWidgetConfigService::class);
                $calendarWidgetConfig = $service->handle($request);
                
                return CalendarWidget::widget($calendarWidgetConfig);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
