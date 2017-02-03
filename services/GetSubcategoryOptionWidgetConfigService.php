<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\services\AbstractBaseService;
use app\finders\SubcategoryIdCategoryFinder;

/**
 * Возвращает массив конфигурации для виджета SubcategoryOptionWidget
 */
class GetSubcategoryOptionWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета SubcategoryOptionWidget
     */
    private $subcategoryOptionWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            if (empty($this->subcategoryOptionWidgetArray)) {
                $id_category = $request->post(\Yii::$app->params['categoryKey']);
                
                $dataArray = [];
                
                $dataArray['subcategoryArray'] = [\Yii::$app->params['formFiller']];
                $dataArray['template'] = 'subcategory-option.twig';
                
                if (!empty($id_category)) {
                    $finder = \Yii::$app->registry->get(SubcategoryIdCategoryFinder::class, ['id_category'=>$id_category]);
                    $subcategoryArray = $finder->find();
                    $subcategoryArray = ArrayHelper::map($subcategoryArray, 'id', 'name');
                    $dataArray['subcategoryArray'] = ArrayHelper::merge($dataArray['subcategoryArray'], $subcategoryArray);
                }
                $this->subcategoryOptionWidgetArray = $dataArray;
            }
            
            return $this->subcategoryOptionWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
