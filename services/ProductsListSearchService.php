<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\{ArrayHelper,
    Url};
use app\services\{AbstractBaseService,
    FrontendTrait};
use app\finders\{BrandsFilterSphinxFinder,
    CategorySeocodeFinder,
    ColorsFilterSphinxFinder,
    FiltersSessionFinder,
    ProductsSphinxFinder,
    SizesFilterSphinxFinder,
    SortingFieldsFinder,
    SortingTypesFinder,
    SphinxFinder,
    SubcategorySeocodeFinder};
use app\helpers\HashHelper;
use app\forms\FiltersForm;

/**
 * Формирует массив данных для рендеринга страницы каталога товаров
 */
class ProductsListSearchService extends AbstractBaseService
{
    use FrontendTrait;
    
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
     * Возвращает массив конфигурации для виджета CategoriesBreadcrumbsWidget
     * @param array $request массив данных запроса
     * @return array
     */
    private function getBreadcrumbsArray(array $request): array
    {
        try {
            if (empty($this->breadcrumbsArray)) {
                $dataArray = [];
                
                $dataArray['text'] = $request[\Yii::$app->params['searchKey']] ?? null;
                
                $this->breadcrumbsArray = $dataArray;
            }
            
            return $this->breadcrumbsArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив ID товаров, найденных sphinx в ответ на поисковый запрос
     * @param array $request массив данных запроса
     * @return array
     */
    private function getSphinxArray(array $request): array
    {
        try {
            if (empty($this->sphinxArray)) {
                $finder = new SphinxFinder([
                    'search'=>$request[\Yii::$app->params['searchKey']] ?? null,
                ]);
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
     * @param array $request массив данных запроса
     * @return ProductsCollection
     */
    private function getProductsCollection(array $request): ProductsCollection
    {
        try {
            if (empty($this->productsCollection)) {
                $finder = new ProductsSphinxFinder([
                    'sphinx'=>ArrayHelper::getColumn($this->getSphinxArray($request), 'id'),
                    'page'=>$request[\Yii::$app->params['pagePointer']] ?? 0,
                    'filters'=>$this->getFiltersModel()
                ]);
                $this->productsCollection = $finder->find();
            }
            
            return $this->productsCollection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы каталога товаров
     * @param array $request
     */
    public function handle($request): array
    {
        try {
            if (empty($request[\Yii::$app->params['searchKey']])) {
                throw new ErrorException($this->emptyError('searchKey'));
            }
            
            $dataArray = [];
            
            $dataArray['userConfig'] = $this->getUserArray();
            $dataArray['cartConfig'] = $this->getCartArray();
            $dataArray['currencyConfig'] = $this->getCurrencyArray();
            $dataArray['searchConfig'] = $this->getSearchArray();
            $dataArray['menuConfig'] = $this->getCategoriesArray();
            
            $dataArray['breadcrumbsConfig'] = $this->getBreadcrumbsArray($request);
            
            if (empty($this->getSphinxArray($request))) {
                $dataArray['emptySphinxConfig'] = $this->getEmptySphinxArray();
            } else {
                $productsCollection = $this->getProductsCollection($request);
                
                if ($productsCollection->isEmpty() === true) {
                    if ($productsCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                    $dataArray['emptyConfig'] = $this->getEmptyProductsArray();
                } else {
                    $dataArray['productsConfig'] = $this->getProductsArray($request);
                }
            }
            
            # Данные для вывода breadcrumbs
            
            //$dataArray['breadcrumbsConfig']['text'] = $request[\Yii::$app->params['searchKey']] ?? null;
            
            # Данные Sphinxsearch
            
            /*$finder = new SphinxFinder([
                'search'=>$request[\Yii::$app->params['searchKey']] ?? null,
            ]);
            $sphinxArray = $finder->find();*/
            
            if (!empty($sphinxArray)) {
                
                # Данные для вывода списка товаров
                
                /*$finder = new ProductsSphinxFinder([
                    'sphinx'=>ArrayHelper::getColumn($sphinxArray, 'id'),
                    'page'=>$request[\Yii::$app->params['pagePointer']] ?? 0,
                    'filters'=>$filtersModel
                ]);
                $productsCollection = $finder->find();*/
                
                /*if ($productsCollection->isEmpty() === true) {
                    if ($productsCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                    $dataArray['emptyConfig']['view'] = 'empty-products.twig';
                } else {
                    $dataArray['productsConfig']['products'] = $productsCollection;
                    $dataArray['productsConfig']['currency'] = $this->currentCurrency();
                    $dataArray['productsConfig']['view'] = 'products-list.twig';
                }*/
                
                # Пагинатор
                
                if (empty($productsCollection->pagination)) {
                    throw new ErrorException($this->emptyError('pagination'));
                }
                $dataArray['paginationConfig']['pagination'] = $productsCollection->pagination;
                $dataArray['paginationConfig']['view'] = 'pagination.twig';
                
                # Данные для вывода фильтров каталога
                
                $finder = new ColorsFilterSphinxFinder([
                    'sphinx'=>ArrayHelper::getColumn($sphinxArray, 'id'),
                ]);
                $colorsArray = $finder->find();
                if (empty($colorsArray)) {
                    throw new ErrorException($this->emptyError('colorsArray'));
                }
                ArrayHelper::multisort($colorsArray, 'color');
                $dataArray['filtersConfig']['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
                
                $finder = new SizesFilterSphinxFinder([
                    'sphinx'=>ArrayHelper::getColumn($sphinxArray, 'id'),
                ]);
                $sizesArray = $finder->find();
                if (empty($sizesArray)) {
                    throw new ErrorException($this->emptyError('sizesArray'));
                }
                ArrayHelper::multisort($sizesArray, 'size');
                $dataArray['filtersConfig']['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
                
                $finder = new BrandsFilterSphinxFinder([
                    'sphinx'=>ArrayHelper::getColumn($sphinxArray, 'id'),
                ]);
                $brandsArray = $finder->find();
                if (empty($brandsArray)) {
                    throw new ErrorException($this->emptyError('brandsArray'));
                }
                ArrayHelper::multisort($brandsArray, 'brand');
                $dataArray['filtersConfig']['brands'] = ArrayHelper::map($brandsArray, 'id', 'brand');
                
                $finder = new SortingFieldsFinder();
                $sortingFieldsArray = $finder->find();
                if (empty($sortingFieldsArray)) {
                    throw new ErrorException($this->emptyError('sortingFieldsArray'));
                }
                ArrayHelper::multisort($sortingFieldsArray, 'value');
                $dataArray['filtersConfig']['sortingFields'] = ArrayHelper::map($sortingFieldsArray, 'name', 'value');
                
                $finder = new SortingTypesFinder();
                $sortingTypesArray = $finder->find();
                if (empty($sortingTypesArray)) {
                    throw new ErrorException($this->emptyError('sortingTypesArray'));
                }
                ArrayHelper::multisort($sortingTypesArray, 'value');
                $dataArray['filtersConfig']['sortingTypes'] = ArrayHelper::map($sortingTypesArray, 'name', 'value');
                
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
                $dataArray['filtersConfig']['form'] = $form;
                
                $dataArray['filtersConfig']['view'] = 'products-filters.twig';
            } else {
                //$dataArray['emptySphinxConfig']['view'] = 'empty-sphinx.twig';
            }
                
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
