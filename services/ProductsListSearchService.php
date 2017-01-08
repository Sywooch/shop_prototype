<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\{NotFoundHttpException,
    Request};
use yii\helpers\{ArrayHelper,
    Url};
use app\services\{AbstractBaseService,
    GetCartWidgetConfigService,
    GetCategoriesMenuWidgetConfigService,
    GetCurrencyWidgetConfigService,
    GetEmptyProductsWidgetConfigService,
    GetProductsFiltersModelService,
    GetSearchWidgetConfigService,
    GetUserInfoWidgetConfigService};
use app\finders\{BrandsFilterSphinxFinder,
    ColorsFilterSphinxFinder,
    ProductsSphinxFinder,
    SizesFilterSphinxFinder,
    SortingFieldsFinder,
    SortingTypesFinder,
    SphinxFinder};
use app\forms\FiltersForm;
use app\collections\ProductsCollection;

/**
 * Формирует массив данных для рендеринга страницы каталога товаров
 */
class ProductsListSearchService extends AbstractBaseService
{
    /**
     * @var array данные для SearchBreadcrumbsWidget
     */
    private $breadcrumbsArray = [];
    /**
     * @var array данные, найденные sphinx
     */
    private $sphinxArray = [];
    /**
     * @var array данные для EmptySphinxWidget
     */
    private $emptySphinxArray = [];
    /**
     * @var ProductsCollection коллекция товаров
     */
    private $productsCollection = null;
    /**
     * @var array данные для FiltersWidget
     */
    private $filtersArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы каталога товаров
     * @param array $request
     */
    public function handle($request): array
    {
        try {
            if (empty($request->get(\Yii::$app->params['searchKey']))) {
                throw new ErrorException($this->emptyError('searchKey'));
            }
            
            $dataArray = [];
            
            $service = new GetUserInfoWidgetConfigService();
            $dataArray['userInfoWidgetConfig'] = $service->handle();
            
            $service = new GetCartWidgetConfigService();
            $dataArray['cartWidgetConfig'] = $service->handle();
            
            $service = new GetCurrencyWidgetConfigService();
            $dataArray['currencyWidgetConfig'] = $service->handle();
            
            $service = new GetSearchWidgetConfigService();
            $dataArray['searchWidgetConfig'] = $service->handle($request);
            
            $service = new GetCategoriesMenuWidgetConfigService();
            $dataArray['categoriesMenuWidgetConfig'] = $service->handle();
            
            if (empty($this->getSphinxArray($request))) {
                $dataArray['emptySphinxConfig'] = $this->getEmptySphinxArray();
            } else {
                $productsCollection = $this->getProductsCollection($request);
                if ($productsCollection->isEmpty() === true) {
                    if ($productsCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                    
                    $service = new GetEmptyProductsWidgetConfigService();
                    $dataArray['emptyConfig'] = $service->handle();
                } else {
                    $dataArray['productsConfig'] = $this->getProductsArray($request);
                    $dataArray['paginationConfig'] = $this->getPaginationArray($request);
                }
                $dataArray['filtersConfig'] = $this->getFiltersArray($request);
            }
            
            $dataArray['breadcrumbsConfig'] = $this->getBreadcrumbsArray($request);
                
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CategoriesBreadcrumbsWidget
     * @param Request $request данные запроса
     * @return array
     */
    private function getBreadcrumbsArray(Request $request): array
    {
        try {
            if (empty($this->breadcrumbsArray)) {
                $dataArray = [];
                
                $dataArray['text'] = $request->get(\Yii::$app->params['searchKey']) ?? null;
                
                $this->breadcrumbsArray = $dataArray;
            }
            
            return $this->breadcrumbsArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив ID товаров, найденных sphinx в ответ на поисковый запрос
     * @param Request $request данные запроса
     * @return array
     */
    private function getSphinxArray(Request $request): array
    {
        try {
            if (empty($this->sphinxArray)) {
                $finder = \Yii::$app->registry->get(SphinxFinder::class, ['search'=>$request->get(\Yii::$app->params['searchKey']) ?? null]);
                $sphinxArray = $finder->find();
                
                $this->sphinxArray = $sphinxArray;
            }
            
            return $this->sphinxArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета EmptySphinxWidget
     * @return array
     */
    private function getEmptySphinxArray(): array
    {
        try {
            if (empty($this->emptySphinxArray)) {
                $dataArray = [];
                
                $dataArray['view'] = 'empty-sphinx.twig';
                
                $this->emptySphinxArray = $dataArray;
            }
            
            return $this->emptySphinxArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает коллекцию товаров
     * @param Request $request данные запроса
     * @return ProductsCollection
     */
    private function getProductsCollection(Request $request): ProductsCollection
    {
        try {
            if (empty($this->productsCollection)) {
                $service = new GetProductsFiltersModelService();
                $filtersModel = $service->handle();
                
                $finder = \Yii::$app->registry->get(ProductsSphinxFinder::class, [
                    'sphinx'=>ArrayHelper::getColumn($this->getSphinxArray($request), 'id'),
                    'page'=>$request->get(\Yii::$app->params['pagePointer']) ?? 0,
                    'filters'=>$filtersModel
                ]);
                $this->productsCollection = $finder->find();
            }
            
            return $this->productsCollection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета FiltersWidget
     * @param Request $request данные запроса
     * @return array
     */
    private function getFiltersArray(Request $request): array
    {
        try {
            if (empty($this->filtersArray)) {
                $dataArray = [];
                
                $sphinxArray = $this->getSphinxArray($request);
                
                $finder = \Yii::$app->registry->get(ColorsFilterSphinxFinder::class, ['sphinx'=>ArrayHelper::getColumn($sphinxArray, 'id')]);
                $colorsArray = $finder->find();
                if (empty($colorsArray)) {
                    throw new ErrorException($this->emptyError('colorsArray'));
                }
                ArrayHelper::multisort($colorsArray, 'color');
                $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
                
                $finder = \Yii::$app->registry->get(SizesFilterSphinxFinder::class, ['sphinx'=>ArrayHelper::getColumn($sphinxArray, 'id')]);
                $sizesArray = $finder->find();
                if (empty($sizesArray)) {
                    throw new ErrorException($this->emptyError('sizesArray'));
                }
                ArrayHelper::multisort($sizesArray, 'size');
                $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
                
                $finder = \Yii::$app->registry->get(BrandsFilterSphinxFinder::class, ['sphinx'=>ArrayHelper::getColumn($sphinxArray, 'id')]);
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
                
                $service = new GetProductsFiltersModelService();
                $filtersModel = $service->handle();
                
                $form = new FiltersForm(array_merge(['url'=>Url::current()], array_filter($filtersModel ->toArray())));
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
                
                $this->filtersArray = $dataArray;
            }
            
            return $this->filtersArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
