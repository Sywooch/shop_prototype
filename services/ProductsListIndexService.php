<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use app\services\{CommonFrontendService,
    ServiceInterface};
use app\exceptions\ExceptionsTrait;
use app\finders\{BrandsFilterFinder,
    CategorySeocodeFinder,
    ColorsFilterFinder,
    OneSessionFinder,
    ProductsFinder,
    SizesFilterFinder,
    SortingFieldsFinder,
    SortingTypesFinder,
    SubcategorySeocodeFinder};
use app\collections\{BaseCollection,
    BaseSessionCollection,
    LightPagination,
    ProductsCollection,
    SortingFieldsCollection,
    SortingTypesCollection};
use app\forms\FiltersForm;
use app\widgets\{PaginationWidget,
    PriceWidget,
    ThumbnailsWidget};
use app\helpers\{HashHelper,
    StringHelper};
use app\filters\ProductsFilters;

/**
 * Формирует массив данных для рендеринга страницы каталога товаров
 */
class ProductsListIndexService extends Object implements ServiceInterface
{
    use ExceptionsTrait;
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы каталога товаров
     * @param array $request
     */
    public function handle($request): array
    {
        try {
            # Общие для всех frontend сервисов данные
            
            $common = new CommonFrontendService();
            $dataArray = $common->handle($request);
            
            # Товарные фильтры
            
            $finder = new OneSessionFinder([
                'collection'=>new BaseSessionCollection()
            ]);
            $finder->load(['key'=>HashHelper::createHash([StringHelper::cutPage(Url::current()), \Yii::$app->user->id ?? ''])]);
            $collection = $finder->find();
            if ($collection->isEmpty() === false) {
                $filtersArray = $collection->getArray();
            }
            
            $filters = new ProductsFilters();
            if (!empty($filtersArray)) {
                $filters->attributes = $filtersArray;
            }
            
            # Данные для вывода списка товаров
            
            $finder = new ProductsFinder([
                'collection'=>new ProductsCollection([
                    'pagination'=>new LightPagination()
                ]),
                'filters'=>$filters
            ]);
            $finder->load($request);
            $collection = $finder->find()->getModels();
            if ($collection->isEmpty()) {
                throw new NotFoundHttpException($this->error404());
            }
            
            $dataArray['productsConfig']['productsCollection'] = $collection;
            $dataArray['productsConfig']['priceWidget'] = new PriceWidget(['currencyModel'=>$dataArray['currencyModel']]);
            $dataArray['productsConfig']['thumbnailsWidget'] = new ThumbnailsWidget(['view'=>'thumbnails.twig']);
            $dataArray['productsConfig']['paginationWidget'] = new PaginationWidget(['view'=>'pagination.twig']);
            $dataArray['productsConfig']['view'] = 'products-list.twig';
            
            # Данные для вывода breadcrumbs
            
            if (!empty($category = $request[\Yii::$app->params['categoryKey']])) {
                $finder = new CategorySeocodeFinder([
                    'collection'=>new BaseCollection()
                ]);
                $finder->load(['seocode'=>$category]);
                $categoryModel = $finder->find()->getModel();
                if (empty($categoryModel)) {
                    throw new ErrorException($this->emptyError('categoryModel'));
                }
                $dataArray['breadcrumbsConfig']['category'] = $categoryModel;
                if (!empty($subcategory = $request[\Yii::$app->params['subcategoryKey']])) {
                    $finder = new SubcategorySeocodeFinder([
                        'collection'=>new BaseCollection()
                    ]);
                    $finder->load(['seocode'=>$subcategory]);
                    $subcategoryModel = $finder->find()->getModel();
                    if (empty($subcategoryModel)) {
                        throw new ErrorException($this->emptyError('subcategoryModel'));
                    }
                    $dataArray['breadcrumbsConfig']['subcategory'] = $subcategoryModel;
                }
            }
            
            # Данные для вывода фильтров каталога
            
            $finder = new ColorsFilterFinder([
                'collection'=>new BaseCollection()
            ]);
            $finder->load($request);
            $collection = $finder->find()->getModels();
            if ($collection->isEmpty()) {
                throw new ErrorException($this->emptyError('colorsCollection'));
            }
            $dataArray['filtersConfig']['colorsCollection'] = $collection;
            
            $finder = new SizesFilterFinder([
                'collection'=>new BaseCollection()
            ]);
            $finder->load($request);
            $collection = $finder->find()->getModels();
            if ($collection->isEmpty()) {
                throw new ErrorException($this->emptyError('sizesCollection'));
            }
            $dataArray['filtersConfig']['sizesCollection'] = $collection;
            
            $finder = new BrandsFilterFinder([
                'collection'=>new BaseCollection()
            ]);
            $finder->load($request);
            $collection = $finder->find()->getModels();
            if ($collection->isEmpty()) {
                throw new ErrorException($this->emptyError('brandsCollection'));
            }
            $dataArray['filtersConfig']['brandsCollection'] = $collection;
            
            $finder = new SortingFieldsFinder([
                'collection'=>new SortingFieldsCollection()
            ]);
            $sortingFieldsCollection = $finder->find();
            if ($sortingFieldsCollection->isEmpty()) {
                throw new ErrorException($this->emptyError('sortingFieldsCollection'));
            }
            $dataArray['filtersConfig']['sortingFieldsCollection'] = $sortingFieldsCollection;
            
            $finder = new SortingTypesFinder([
                'collection'=>new SortingTypesCollection()
            ]);
            $sortingTypesCollection = $finder->find();
            if ($sortingTypesCollection->isEmpty()) {
                throw new ErrorException($this->emptyError('sortingTypesCollection'));
            }
            $dataArray['filtersConfig']['sortingTypesCollection'] = $sortingTypesCollection;
            
            $form = new FiltersForm(array_merge(['url'=>Url::current()], !empty($filtersArray) ? $filtersArray : []));
            if (empty($form->sortingField)) {
                $form->sortingField = $sortingFieldsCollection->getDefault();
            }
            if (empty($form->sortingType)) {
                $form->sortingType = $sortingTypesCollection->getDefault();
            }
            $dataArray['filtersConfig']['form'] = $form;
            
            $dataArray['filtersConfig']['view'] = 'products-filters.twig';
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
