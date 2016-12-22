<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\{ArrayHelper,
    Url};
use app\services\AbstarctFrontendService;
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
class ProductsListSearchService extends AbstarctFrontendService
{
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
            
            # Общие для всех frontend сервисов данные
            
            $dataArray = [];
            
            $dataArray['userConfig'] = $this->user();
            $dataArray['cartConfig'] = $this->cart();
            $dataArray['currencyConfig'] = $this->currency();
            $dataArray['searchConfig'] = $this->search();
            $dataArray['menuConfig'] = $this->categories();
            
            # Товарные фильтры
            
            $finder = new FiltersSessionFinder([
                'key'=>HashHelper::createFiltersKey(Url::current())
            ]);
            $filtersModel = $finder->find();
            if (empty($filtersModel)) {
                throw new ErrorException($this->emptyError('filtersModel'));
            }
            
            # Данные для вывода breadcrumbs
            
            $dataArray['breadcrumbsConfig']['text'] = $request[\Yii::$app->params['searchKey']] ?? null;
            
            # Данные Sphinxsearch
            
            $finder = new SphinxFinder([
                'search'=>$request[\Yii::$app->params['searchKey']] ?? null,
            ]);
            $sphinxArray = $finder->find();
            
            if (!empty($sphinxArray)) {
                
                # Данные для вывода списка товаров
                
                $finder = new ProductsSphinxFinder([
                    'sphinx'=>ArrayHelper::getColumn($sphinxArray, 'id'),
                    'page'=>$request[\Yii::$app->params['pagePointer']] ?? 0,
                    'filters'=>$filtersModel
                ]);
                $productsCollection = $finder->find();
                
                if ($productsCollection->isEmpty() === true) {
                    if ($productsCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                    $dataArray['emptyConfig']['view'] = 'empty-products.twig';
                } else {
                    $dataArray['productsConfig']['products'] = $productsCollection;
                    $dataArray['productsConfig']['currency'] = $this->currentCurrency();
                    $dataArray['productsConfig']['view'] = 'products-list.twig';
                }
                
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
                $dataArray['emptySphinxConfig']['view'] = 'empty-sphinx.twig';
            }
                
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
