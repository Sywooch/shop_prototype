<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use app\services\{AbstractBaseService,
    GetSubcategoryOptionWidgetConfigService};
use app\widgets\SubcategoryOptionWidget;

/**
 * Возвращает календарь
 */
class CategoriesGetSubcategoryService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос подкатегорий для категории
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if ($request->isAjax === true) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                
                $service = \Yii::$app->registry->get(GetSubcategoryOptionWidgetConfigService::class);
                $subcategoryOptionWidgetConfig = $service->handle($request);
                
                return SubcategoryOptionWidget::widget($subcategoryOptionWidgetConfig);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
