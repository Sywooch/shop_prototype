<?php

namespace app\services;

use yii\base\{ErrorException,
    Model,
    Object};
use yii\web\NotFoundHttpException;
use app\exceptions\ExceptionsTrait;
use app\models\{ChangeCurrencyFormModel,
    CurrencyModel,
    ProductsFiltersFormModel,
    PurchasesModel};
use app\services\{BrandsFilterSearch,
    CategoryOneSearchService,
    CategoriesMenuSearchService,
    ColorsFilterSearch,
    CurrencyCollectionSearchService,
    ServiceInterface,
    SizesFilterSearch};
use app\widgets\{CategoriesBreadcrumbsWidget,
    CategoriesMenuWidget,
    CartWidget,
    CurrencyWidget,
    FiltersWidget,
    PaginationWidget,
    PriceWidget,
    ProductsListWidget,
    SearchWidget,
    ThumbnailsWidget,
    UserInfoWidget};
use app\repositories\SessionRepository;
use app\finders\{CurrencySessionFinder,
    ProductsFinder,
    PurchasesSessionFinder};
use app\queries\LightPagination;
use app\collections\{Collection,
    ProductsCollection,
    PurchasesCollection};
use app\helpers\HashHelper;

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
    public function handle($request)
    {
        try {
            $dataArray = [];
            
            $productsFinder = new ProductsFinder([
                'collection'=>new ProductsCollection([
                    'pagination'=>new LightPagination()
                ])
            ]);
            if ($productsFinder->load($request) === false) {
                throw new ErrorException(ExceptionsTrait::methodError('productsFinder::load'));
            }
            $productsCollection = $productsFinder->find();
            if ($productsCollection->isEmpty()) {
                throw new NotFoundHttpException(ExceptionsTrait::Error404());
            }
            
            $currencyFinder = new CurrencySessionFinder();
            if ($currencyFinder->load(['key'=>\Yii::$app->params['currencyKey']]) === false) {
                throw new ErrorException(ExceptionsTrait::methodError('currencyFinder::load'));
            }
            $currencyModel = $currencyFinder->find();
            if (empty($currencyModel)) {
                throw new ErrorException(ExceptionsTrait::emptyError('currencyModel'));
            }
            
            $dataArray['collection'] = ProductsListWidget::widget([
                'productsCollection'=>$productsCollection,
                'priceWidget'=>new PriceWidget([
                    'currencyModel'=>$currencyModel, 
                ]),
                'thumbnailsWidget'=>new ThumbnailsWidget([
                    'view'=>'thumbnails.twig'
                ]),
                'paginationWidget'=>new PaginationWidget([
                    'view'=>'pagination.twig'
                ]),
                'view'=>'products-list.twig',
            ]);
            
           $dataArray['user'] = UserInfoWidget::widget([
                'user'=>\Yii::$app->user,
                'view'=>'user-info.twig',
            ]);
            
            $purchasesFinder = new PurchasesSessionFinder([
                'collection'=>new PurchasesCollection()
            ]);
            $key = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
            if ($purchasesFinder->load(['key'=>$key]) === false) {
                throw new ErrorException(ExceptionsTrait::methodError('purchasesFinder::load'));
            }
            $purchasesCollection = $purchasesFinder->find();
            $dataArray['cart'] = CartWidget::widget([
                'purchasesCollection'=>$purchasesCollection, 
                'currencyModel'=>$currencyModel,
                'view'=>'short-cart.twig'
            ]);
            
            $dataArray['currency'] = CurrencyWidget::widget([
                'service'=>new CurrencyCollectionSearchService([
                    'collection'=>new Collection(),
                ]),
                'form'=>new ChangeCurrencyFormModel(),
                'view'=>'currency-form.twig'
            ]);
            
            $dataArray['search'] = SearchWidget::widget([
                'view'=>'search.twig'
            ]);
            
            $dataArray['menu'] = CategoriesMenuWidget::widget([
                'service'=>new CategoriesMenuSearchService([
                    'collection'=>new Collection(),
                ]),
            ]);
            
            $dataArray['breadcrumbs'] = CategoriesBreadcrumbsWidget::widget([
                'service'=>new CategoryOneSearchService(),
                'category'=>$request[\Yii::$app->params['categoryKey']],
                'subcategory'=>$request[\Yii::$app->params['subcategoryKey']],
            ]);
            
            $dataArray['filters'] = FiltersWidget::widget([
                'colorsService'=>new ColorsFilterSearch([
                    'collection'=>new Collection(),
                ]),
                'sizesService'=>new SizesFilterSearch([
                    'collection'=>new Collection(),
                ]),
                'brandsService'=>new BrandsFilterSearch([
                    'collection'=>new Collection(),
                ]),
                'form'=>new ProductsFiltersFormModel(),
                'view'=>'products-filters.twig'
            ]);
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
