<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\services\AbstractBaseService;
use app\finders\{AdminProductsFiltersSessionFinder,
    BrandsFinder,
    ColorsFinder,
    SizesFinder,
    SortingFieldsFinder,
    SortingTypesFinder};
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
                $dataArray['categories'] = ArrayHelper::map($categoriesArray, 'id', 'name');
                
                $finder = \Yii::$app->registry->get(AdminProductsFiltersSessionFinder::class, [
                    'key'=>HashHelper::createFiltersKey(Url::current())
                ]);
                $filtersModel = $finder->find();
                
                $form = new AdminProductsFiltersForm(array_filter($filtersModel->toArray()));
                
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
                
                $form->url = Url::current();
                
                $dataArray['form'] = $form;
                $dataArray['header'] = \Yii::t('base', 'Filters');
                $dataArray['template'] = 'products-filters.twig';
                
                $this->adminProductsFiltersWidgetArray = $dataArray;
            }
            
            return $this->adminProductsFiltersWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
