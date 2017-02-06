<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\services\AbstractBaseService;
use app\finders\{ActiveStatusesFinder,
    AdminProductsFiltersSessionFinder,
    BrandsFinder,
    CategoriesFinder,
    ColorsFinder,
    SizesFinder,
    SortingFieldsFinder,
    SortingTypesFinder,
    SubcategoryIdCategoryFinder};
use app\helpers\HashHelper;
use app\forms\AdminProductsFiltersForm;

/**
 * Возвращает массив конфигурации для виджета AdminProductsFiltersWidget
 */
class GetAdminProductsFiltersWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AdminProductsFiltersWidget
     */
    private $adminProductsFiltersWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета AdminProductsFiltersWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->adminProductsFiltersWidgetArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(AdminProductsFiltersSessionFinder::class, [
                    'key'=>HashHelper::createHash([\Yii::$app->params['adminProductsFilters']])
                ]);
                $filtersModel = $finder->find();
                
                $finder = \Yii::$app->registry->get(SortingFieldsFinder::class);
                $sortingFieldsArray = $finder->find();
                if (empty($sortingFieldsArray)) {
                    throw new ErrorException($this->emptyError('sortingFieldsArray'));
                }
                asort($sortingFieldsArray, SORT_STRING);
                $dataArray['sortingFields'] = $sortingFieldsArray;
                
                $finder = \Yii::$app->registry->get(SortingTypesFinder::class);
                $sortingTypesArray = $finder->find();
                if (empty($sortingTypesArray)) {
                    throw new ErrorException($this->emptyError('sortingTypesArray'));
                }
                asort($sortingTypesArray, SORT_STRING);
                $dataArray['sortingTypes'] = $sortingTypesArray;
                
                $finder = \Yii::$app->registry->get(ColorsFinder::class);
                $colorsArray = $finder->find();
                if (empty($colorsArray)) {
                    throw new ErrorException($this->emptyError('colorsArray'));
                }
                ArrayHelper::multisort($colorsArray, 'color');
                $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
                
                $finder = \Yii::$app->registry->get(SizesFinder::class);
                $sizesArray = $finder->find();
                if (empty($sizesArray)) {
                    throw new ErrorException($this->emptyError('sizesArray'));
                }
                ArrayHelper::multisort($sizesArray, 'size');
                $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
                
                $finder = \Yii::$app->registry->get(BrandsFinder::class);
                $brandsArray = $finder->find();
                if (empty($brandsArray)) {
                    throw new ErrorException($this->emptyError('brandsArray'));
                }
                ArrayHelper::multisort($brandsArray, 'brand');
                $dataArray['brands'] = ArrayHelper::map($brandsArray, 'id', 'brand');
                
                $finder = \Yii::$app->registry->get(CategoriesFinder::class);
                $categoriesArray = $finder->find();
                if (empty($categoriesArray)) {
                    throw new ErrorException($this->emptyError('categoriesArray'));
                }
                ArrayHelper::multisort($categoriesArray, 'name');
                $categoriesArray = ArrayHelper::map($categoriesArray, 'id', 'name');
                $dataArray['categories'] = ArrayHelper::merge([\Yii::$app->params['formFiller']], $categoriesArray);
                
                $dataArray['subcategory'] = [\Yii::$app->params['formFiller']];
                if (!empty($filtersModel->category)) {
                    $finder = \Yii::$app->registry->get(SubcategoryIdCategoryFinder::class, ['id_category'=>$filtersModel->category]);
                    $subcategoryArray = $finder->find();
                    $subcategoryArray = ArrayHelper::map($subcategoryArray, 'id', 'name');
                    $dataArray['subcategory'] = ArrayHelper::merge($dataArray['subcategory'], $subcategoryArray);
                }
                
                $finder = \Yii::$app->registry->get(ActiveStatusesFinder::class);
                $activeStatusesArray = $finder->find();
                if (empty($activeStatusesArray)) {
                    throw new ErrorException($this->emptyError('activeStatusesArray'));
                }
                asort($activeStatusesArray, SORT_STRING);
                $dataArray['activeStatuses'] = $activeStatusesArray;
                
                $form = new AdminProductsFiltersForm($filtersModel->toArray());
                
                if (empty($form->sortingField)) {
                    foreach ($sortingFieldsArray as $key=>$val) {
                        if ($key === \Yii::$app->params['sortingField']) {
                            $form->sortingField = $key;
                        }
                    }
                }
                
                if (empty($form->sortingType)) {
                    foreach ($sortingTypesArray as $key=>$val) {
                        if ($key === \Yii::$app->params['sortingType']) {
                            $form->sortingType = $key;
                        }
                    }
                }
                
                $form->url = Url::current();
                
                $dataArray['form'] = $form;
                $dataArray['header'] = \Yii::t('base', 'Filters');
                $dataArray['template'] = 'admin-products-filters.twig';
                
                $this->adminProductsFiltersWidgetArray = $dataArray;
            }
            
            return $this->adminProductsFiltersWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
