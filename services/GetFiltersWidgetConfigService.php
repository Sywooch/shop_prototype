<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\services\{AbstractBaseService,
    GetProductsFiltersModelService};
use app\finders\{BrandsFilterFinder,
    ColorsFilterFinder,
    SizesFilterFinder,
    SortingFieldsFinder,
    SortingTypesFinder};
use app\forms\FiltersForm;

/**
 * Возвращает массив конфигурации для виджета FiltersWidget
 */
class GetFiltersWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета FiltersWidget
     */
    private $filtersWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета FiltersWidget
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            if (empty($this->filtersWidgetArray)) {
                $dataArray = [];
                
                $category = $request->get(\Yii::$app->params['categoryKey']) ?? null;
                $subcategory = $request->get(\Yii::$app->params['subcategoryKey']) ?? null;
                
                $finder = \Yii::$app->registry->get(ColorsFilterFinder::class, ['category'=>$category, 'subcategory'=>$subcategory]);
                $colorsArray = $finder->find();
                if (empty($colorsArray)) {
                    throw new ErrorException($this->emptyError('colorsArray'));
                }
                ArrayHelper::multisort($colorsArray, 'color');
                $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
                
                $finder = \Yii::$app->registry->get(SizesFilterFinder::class, ['category'=>$category, 'subcategory'=>$subcategory]);
                $sizesArray = $finder->find();
                if (empty($sizesArray)) {
                    throw new ErrorException($this->emptyError('sizesArray'));
                }
                ArrayHelper::multisort($sizesArray, 'size');
                $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
                
                $finder = \Yii::$app->registry->get(BrandsFilterFinder::class, ['category'=>$category, 'subcategory'=>$subcategory]);
                $brandsArray = $finder->find();
                if (empty($brandsArray)) {
                    throw new ErrorException($this->emptyError('brandsArray'));
                }
                ArrayHelper::multisort($brandsArray, 'brand');
                $dataArray['brands'] = ArrayHelper::map($brandsArray, 'id', 'brand');
                
                $finder = \Yii::$app->registry->get(SortingFieldsFinder::class);
                $sortingFieldsArray = $finder->find();
                if (empty($sortingFieldsArray)) {
                    throw new ErrorException($this->emptyError('sortingFieldsArray'));
                }
                ArrayHelper::multisort($sortingFieldsArray, 'value');
                $dataArray['sortingFields'] = ArrayHelper::map($sortingFieldsArray, 'name', 'value');
                
                $finder = \Yii::$app->registry->get(SortingTypesFinder::class);
                $sortingTypesArray = $finder->find();
                if (empty($sortingTypesArray)) {
                    throw new ErrorException($this->emptyError('sortingTypesArray'));
                }
                ArrayHelper::multisort($sortingTypesArray, 'value');
                $dataArray['sortingTypes'] = ArrayHelper::map($sortingTypesArray, 'name', 'value');
                
                $service = \Yii::$app->registry->get(GetProductsFiltersModelService::class);
                $filtersModel = $service->handle();
                
                $form = new FiltersForm(array_merge(['url'=>Url::current()], array_filter($filtersModel->toArray())));
                
                if (empty($form->sortingField)) {
                    foreach ($sortingFieldsArray as $item) {
                        if ($item['name'] === \Yii::$app->params['sortingField']) {
                            $form->sortingField = $item;
                        }
                    }
                }
                if (empty($form->sortingType)) {
                    foreach ($sortingTypesArray as $item) {
                        if ($item['name'] === \Yii::$app->params['sortingType']) {
                            $form->sortingType = $item;
                        }
                    }
                }
                $dataArray['form'] = $form;
                $dataArray['view'] = 'products-filters.twig';
                
                $this->filtersWidgetArray = $dataArray;
            }
            
            return $this->filtersWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
