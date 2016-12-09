<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use app\services\ServiceInterface;
use app\exceptions\ExceptionsTrait;
use app\finders\{BrandsFilterFinder,
    CategorySeocodeFinder,
    ColorsFilterFinder,
    ProductsFinder,
    SizesFilterFinder,
    SubcategorySeocodeFinder};
use app\collections\{BaseCollection,
    LightPagination,
    ProductsCollection};
use app\forms\FiltersForm;
use app\widgets\{PaginationWidget,
    PriceWidget,
    ThumbnailsWidget};

/**
 * Формирует массив данных для рендеринга страницы каталога товаров
 */
class ProductsListIndexService extends Object implements ServiceInterface
{
    use ExceptionsTrait;
    
    /**
     * @var object ServiceInterface данные, общие для всех frontend сервисов
     */
    private $commonService;
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы каталога товаров
     * @param array $request
     */
    public function handle($request): array
    {
        try {
            $dataArray = $this->commonService->handle($request);
            
            # Данные для вывода списка товаров
            
            $finder = new ProductsFinder([
                'collection'=>new ProductsCollection([
                    'pagination'=>new LightPagination()
                ])
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
            
            $dataArray['filtersConfig']['form'] = new FiltersForm(['url'=>Url::current()]);
            $dataArray['filtersConfig']['view'] = 'products-filters.twig';
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ServiceInterface свойствуProductsListSearchService::commonService
     * @param ServiceInterface $service
     */
    public function setCommonService(ServiceInterface $service)
    {
        try {
            $this->commonService = $service;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
