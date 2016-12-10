<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use app\services\ServiceInterface;
use app\exceptions\ExceptionsTrait;
use app\finders\{BrandsSphinxFilterFinder,
    ColorsSphinxFilterFinder,
    OneSessionFinder,
    ProductsSphinxFinder,
    SizesSphinxFilterFinder,
    SphinxFinder};
use app\collections\{BaseCollection,
    BaseSessionCollection,
    LightPagination,
    ProductsCollection,
    SphinxCollection};
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
class ProductsListSearchService extends Object implements ServiceInterface
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
            
            # Данные, найденные в ответ на поисковый запрос
            
            $finder = new SphinxFinder([
                'collection'=>new SphinxCollection()
            ]);
            $finder->load($request);
            $sphinxCollection = $finder->find()->getArrays();
            
            if ($sphinxCollection->isEmpty() === false) {
                
                # Данные для вывода списка товаров
                
                $finder = new ProductsSphinxFinder([
                    'collection'=>new ProductsCollection([
                        'pagination'=>new LightPagination()
                    ]),
                    'filters'=>$filters
                ]);
                $finder->load(array_merge($request, ['found'=>$sphinxCollection->column('id')]));
                $collection = $finder->find()->getModels();
                if ($collection->isEmpty()) {
                    throw new NotFoundHttpException($this->error404());
                }
                
                $dataArray['productsConfig']['productsCollection'] = $collection;
                
                $dataArray['productsConfig']['priceWidget'] = new PriceWidget(['currencyModel'=>$dataArray['currencyModel']]);
                $dataArray['productsConfig']['thumbnailsWidget'] = new ThumbnailsWidget(['view'=>'thumbnails.twig']);
                $dataArray['productsConfig']['paginationWidget'] = new PaginationWidget(['view'=>'pagination.twig']);
                $dataArray['productsConfig']['view'] = 'products-list.twig';
                
                # Данные для вывода фильтров каталога
                
                $finder = new ColorsSphinxFilterFinder([
                    'collection'=>new BaseCollection()
                ]);
                $finder->load(array_merge($request, ['found'=>$sphinxCollection->column('id')]));
                $collection = $finder->find()->getModels();
                if ($collection->isEmpty()) {
                    throw new ErrorException($this->emptyError('colorsCollection'));
                }
                $dataArray['filtersConfig']['colorsCollection'] = $collection;
                
                $finder = new SizesSphinxFilterFinder([
                    'collection'=>new BaseCollection()
                ]);
                $finder->load(array_merge($request, ['found'=>$sphinxCollection->column('id')]));
                $collection = $finder->find()->getModels();
                if ($collection->isEmpty()) {
                    throw new ErrorException($this->emptyError('sizesCollection'));
                }
                $dataArray['filtersConfig']['sizesCollection'] = $collection;
                
                $finder = new BrandsSphinxFilterFinder([
                    'collection'=>new BaseCollection()
                ]);
                $finder->load(array_merge($request, ['found'=>$sphinxCollection->column('id')]));
                $collection = $finder->find()->getModels();
                if ($collection->isEmpty()) {
                    throw new ErrorException($this->emptyError('brandsCollection'));
                }
                $dataArray['filtersConfig']['brandsCollection'] = $collection;
                
                $dataArray['filtersConfig']['form'] = new FiltersForm(array_merge(['url'=>Url::current()], !empty($filtersArray) ? $filtersArray : []));
                $dataArray['filtersConfig']['view'] = 'products-filters.twig';
            } else {
                $dataArray['emptySphinxConfig']['text'] = $request[\Yii::$app->params['searchKey']];
                $dataArray['emptySphinxConfig']['view'] = 'empty-sphinx.twig';
            }
            
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
