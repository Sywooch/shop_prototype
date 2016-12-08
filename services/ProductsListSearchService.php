<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use yii\web\NotFoundHttpException;
use app\exceptions\ExceptionsTrait;
use app\finders\{BrandsFilterFinder,
    CategoriesFinder,
    CategorySeocodeFinder,
    ColorsFilterFinder,
    CurrencyFinder,
    GroupSessionFinder,
    OneSessionFinder,
    ProductsFinder,
    SizesFilterFinder,
    SubcategorySeocodeFinder};
use app\collections\{BaseCollection,
    BaseSessionCollection,
    LightPagination,
    ProductsCollection,
    PurchasesSessionCollection};
use app\helpers\HashHelper;
use app\forms\{ChangeCurrencyForm,
    FiltersForm};
use app\models\{CurrencyModel,
    PurchasesModel};
use app\widgets\{PaginationWidget,
    PriceWidget,
    ThumbnailsWidget};

/**
 * Формирует массив данных для рендеринга страницы каталога товаров
 */
class ProductsListSearchService extends Object implements ServiceInterface
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
            $dataArray = [];
            
            # Данные, найденные в ответ на запрос
            
            $finder = new SphinxFinder([
                'collection'=>new SphinxCollection()
            ]);
            $finder->load($request);
            $sphinxCollection = $finder->find()->getArrays();
            if ($sphinxCollection->isEmpty()) {
                throw new NotFoundHttpException($this->error404());
            }
            
            # Данные для вывода списка товаров
            
            /*$finder = new ProductsFinder([
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
            
            $finder = new OneSessionFinder([
                'collection'=>new BaseSessionCollection()
            ]);
            $finder->load(['key'=>\Yii::$app->params['currencyKey']]);
            $currencyModel = $finder->find()->getModel(CurrencyModel::class);
            if (empty($currencyModel)) {
                throw new ErrorException($this->emptyError('currencyModel'));
            }
            
            $dataArray['productsConfig']['priceWidget'] = new PriceWidget(['currencyModel'=>$currencyModel]);
            $dataArray['productsConfig']['thumbnailsWidget'] = new ThumbnailsWidget(['view'=>'thumbnails.twig']);
            $dataArray['productsConfig']['paginationWidget'] = new PaginationWidget(['view'=>'pagination.twig']);
            $dataArray['productsConfig']['view'] = 'products-list.twig';
            
            # Данные для вывода информации о текущем пользователе
            
            $dataArray['userConfig']['user'] = \Yii::$app->user;
            $dataArray['userConfig']['view'] = 'user-info.twig';
            
            # Данные для вывода информации о состоянии корзины
            
            $finder = new GroupSessionFinder([
                'collection'=>new PurchasesSessionCollection()
            ]);
            $finder->load(['key'=>HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? ''])]);
            $collection = $finder->find()->getModels(PurchasesModel::class);
            
            $dataArray['cartConfig']['purchasesCollection'] = $collection;
            $dataArray['cartConfig']['priceWidget'] = new PriceWidget(['currencyModel'=>$currencyModel]);
            $dataArray['cartConfig']['view'] = 'short-cart.twig';
            
            # Данные для вывода списка доступных валют
            
            $finder = new CurrencyFinder([
                'collection'=>new BaseCollection()
            ]);
            $collection = $finder->find()->getModels();
            if ($collection->isEmpty()) {
                throw new ErrorException($this->emptyError('currencyCollection'));
            }
            
            $dataArray['currencyConfig']['currencyCollection'] = $collection;
            $dataArray['currencyConfig']['form'] = new ChangeCurrencyForm();
            $dataArray['currencyConfig']['view'] = 'currency-form.twig';
            
            # Данные для вывода строки поиска
            
            $dataArray['searchConfig']['text'] = $request[\Yii::$app->params['searchKey']];
            $dataArray['searchConfig']['view'] = 'search.twig';
            
            # Данные для вывода меню категорий
            
            $finder = new CategoriesFinder([
                'collection'=>new BaseCollection()
            ]);
            $collection = $finder->find()->getModels();
            if ($collection->isEmpty()) {
                throw new ErrorException($this->emptyError('categoriesCollection'));
            }
            
            $dataArray['menuConfig']['categoriesCollection'] = $collection;
            
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
            
            $dataArray['filtersConfig']['form'] = new FiltersForm();
            $dataArray['filtersConfig']['view'] = 'products-filters.twig';*/
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
