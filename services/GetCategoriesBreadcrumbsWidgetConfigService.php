<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\{CategorySeocodeFinder,
    SubcategorySeocodeFinder};

/**
 * Возвращает массив конфигурации для виджета CategoriesBreadcrumbsWidget
 */
class GetCategoriesBreadcrumbsWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета CategoriesBreadcrumbsWidget
     */
    private $categoriesBreadcrumbsWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета CategoriesBreadcrumbsWidget
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            if (empty($this->categoriesBreadcrumbsWidgetArray)) {
                $dataArray = [];
                
                $category = $request->get(\Yii::$app->params['categoryKey']) ?? null;
                $subcategory = $request->get(\Yii::$app->params['subcategoryKey']) ?? null;
                
                if (!empty($category)) {
                    $finder = \Yii::$app->registry->get(CategorySeocodeFinder::class, ['seocode'=>$category]);
                    $categoryModel = $finder->find();
                    if (empty($categoryModel)) {
                        throw new ErrorException($this->emptyError('categoryModel'));
                    }
                    $dataArray['category'] = $categoryModel;
                    
                    if (!empty($subcategory)) {
                        $finder = \Yii::$app->registry->get(SubcategorySeocodeFinder::class, ['seocode'=>$subcategory]);
                        $subcategoryModel = $finder->find();
                        if (empty($subcategoryModel)) {
                            throw new ErrorException($this->emptyError('subcategoryModel'));
                        }
                        $dataArray['subcategory'] = $subcategoryModel;
                    }
                }
                
                $this->categoriesBreadcrumbsWidgetArray = $dataArray;
            }
            
            return $this->categoriesBreadcrumbsWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
