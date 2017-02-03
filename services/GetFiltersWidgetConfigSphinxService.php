<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\services\{AbstractBaseService,
    GetProductsFiltersModelService,
    GetSphinxArrayService};
use app\finders\{BrandsFilterSphinxFinder,
    ColorsFilterSphinxFinder,
    SizesFilterSphinxFinder,
    SortingFieldsFinder,
    SortingTypesFinder};
use app\forms\FiltersForm;

/**
 * Возвращает массив конфигурации для виджета FiltersWidget
 */
class GetFiltersWidgetConfigSphinxService extends AbstractBaseService
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
                
                $service = \Yii::$app->registry->get(GetSphinxArrayService::class);
                $sphinxArray = $service->handle($request);
                
                $finder = \Yii::$app->registry->get(ColorsFilterSphinxFinder::class, ['sphinx'=>$sphinxArray]);
                $colorsArray = $finder->find();
                if (empty($colorsArray)) {
                    throw new ErrorException($this->emptyError('colorsArray'));
                }
                ArrayHelper::multisort($colorsArray, 'color');
                $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
                
                $finder = \Yii::$app->registry->get(SizesFilterSphinxFinder::class, ['sphinx'=>$sphinxArray]);
                $sizesArray = $finder->find();
                if (empty($sizesArray)) {
                    throw new ErrorException($this->emptyError('sizesArray'));
                }
                ArrayHelper::multisort($sizesArray, 'size');
                $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
                
                $finder = \Yii::$app->registry->get(BrandsFilterSphinxFinder::class, ['sphinx'=>$sphinxArray]);
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
                asort($sortingFieldsArray, SORT_STRING);
                $dataArray['sortingFields'] = $sortingFieldsArray;
                
                $finder = \Yii::$app->registry->get(SortingTypesFinder::class);
                $sortingTypesArray = $finder->find();
                if (empty($sortingTypesArray)) {
                    throw new ErrorException($this->emptyError('sortingTypesArray'));
                }
                asort($sortingTypesArray, SORT_STRING);
                $dataArray['sortingTypes'] = $sortingTypesArray;
                
                $service = \Yii::$app->registry->get(GetProductsFiltersModelService::class);
                $filtersModel = $service->handle();
                
                $form = new FiltersForm(array_merge(['url'=>Url::current()], array_filter($filtersModel ->toArray())));
                
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
                
                $dataArray['form'] = $form;
                $dataArray['header'] = \Yii::t('base', 'Filters');
                $dataArray['template'] = 'products-filters.twig';
                
                $this->filtersWidgetArray = $dataArray;
            }
            
            return $this->filtersWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
