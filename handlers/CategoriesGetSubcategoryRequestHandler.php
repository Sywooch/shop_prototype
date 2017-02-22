<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use app\handlers\AbstractBaseHandler;
use app\widgets\SubcategoryOptionWidget;
use app\finders\SubcategoryIdCategoryFinder;

/**
 * Обрабатывает запрос на получение строки с подкатегориями для категории
 */
class CategoriesGetSubcategoryRequestHandler extends AbstractBaseHandler
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
                
                $id_category = $request->post(\Yii::$app->params['categoryKey']) ?? null;
                
                if (!empty($id_category)) {
                    $finder = \Yii::$app->registry->get(SubcategoryIdCategoryFinder::class, [
                        'id_category'=>$id_category
                    ]);
                    $subcategoryArray = $finder->find();
                    if (empty($subcategoryArray)) {
                        throw new ErrorException($this->emptyError('subcategoryArray'));
                    }
                }
                
                $subcategoryOptionWidgetConfig = $this->subcategoryOptionWidgetConfig($subcategoryArray ?? []);
                $response = SubcategoryOptionWidget::widget($subcategoryOptionWidgetConfig);
                
                return $response;
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета SubcategoryOptionWidget
     * @param array $subcategoryArray
     * @return array
     */
    private function subcategoryOptionWidgetConfig(array $subcategoryArray)
    {
        try {
            $dataArray = [];
            
            $dataArray['subcategoryArray'] = $subcategoryArray;
            $dataArray['template'] = 'subcategory-option.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
