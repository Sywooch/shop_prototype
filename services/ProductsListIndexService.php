<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use yii\web\NotFoundHttpException;
use app\exceptions\ExceptionsTrait;
use app\models\{ChangeCurrencyFormModel,
    ProductsFiltersFormModel};
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
use app\finders\{BrandsFilterFinder,
    CategoriesFinder,
    CategorySeocodeFinder,
    ColorsFilterFinder,
    CurrencyFinder,
    CurrencySessionFinder,
    ProductsFinder,
    PurchasesSessionFinder,
    SizesFilterFinder,
    SubcategorySeocodeFinder};
use app\collections\{Collection,
    LightPagination,
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
            $productsFinder->load($request);
            $productsCollection = $productsFinder->find();
            if ($productsCollection->isEmpty()) {
                throw new NotFoundHttpException(ExceptionsTrait::Error404());
            }
            
            $currencyFinder = new CurrencySessionFinder();
            $currencyFinder->load(['key'=>\Yii::$app->params['currencyKey']]);
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
            $purchasesFinder->load(['key'=>$key]);
            $purchasesCollection = $purchasesFinder->find();
            $dataArray['cart'] = CartWidget::widget([
                'purchasesCollection'=>$purchasesCollection, 
                'priceWidget'=>new PriceWidget([
                    'currencyModel'=>$currencyModel, 
                ]),
                'view'=>'short-cart.twig'
            ]);
            
            $currencyFinder = new CurrencyFinder([
                'collection'=>new Collection()
            ]);
            $currencyCollection = $currencyFinder->find();
            if ($currencyCollection->isEmpty()) {
                throw new ErrorException(ExceptionsTrait::emptyError('currencyCollection'));
            }
            $dataArray['currency'] = CurrencyWidget::widget([
                'currencyCollection'=>$currencyCollection,
                'form'=>new ChangeCurrencyFormModel(),
                'view'=>'currency-form.twig'
            ]);
            
            $dataArray['search'] = SearchWidget::widget([
                'view'=>'search.twig'
            ]);
            
            $categoriesFinder = new CategoriesFinder([
                'collection'=>new Collection()
            ]);
            $categoriesCollection = $categoriesFinder->find();
            if ($categoriesCollection->isEmpty()) {
                throw new ErrorException(ExceptionsTrait::emptyError('categoriesCollection'));
            }
            $dataArray['menu'] = CategoriesMenuWidget::widget([
                'categoriesCollection'=>$categoriesCollection
            ]);
            
            $categoriesBreadcrumbsConfig = [];
            if (!empty($category = $request[\Yii::$app->params['categoryKey']])) {
                $categorySeocodeFinder = new CategorySeocodeFinder();
                $categorySeocodeFinder->load(['seocode'=>$category]);
                $categoryModel = $categorySeocodeFinder->find();
                if (empty($categoryModel)) {
                    throw new ErrorException(ExceptionsTrait::emptyError('categoryModel'));
                }
                $categoriesBreadcrumbsConfig['category'] = $categoryModel;
                if (!empty($subcategory = $request[\Yii::$app->params['subcategoryKey']])) {
                    $subcategorySeocodeFinder = new SubcategorySeocodeFinder();
                    $subcategorySeocodeFinder->load(['seocode'=>$subcategory]);
                    $subcategoryModel = $subcategorySeocodeFinder->find();
                    if (empty($subcategoryModel)) {
                        throw new ErrorException(ExceptionsTrait::emptyError('subcategoryModel'));
                    }
                    $categoriesBreadcrumbsConfig['subcategory'] = $subcategoryModel;
                }
            }
            $dataArray['breadcrumbs'] = CategoriesBreadcrumbsWidget::widget($categoriesBreadcrumbsConfig);
            
            $colorsFilterFinder = new ColorsFilterFinder([
                'collection'=>new Collection()
            ]);
            $colorsFilterFinder->load($request);
            $colorsCollection = $colorsFilterFinder->find();
            if ($colorsCollection->isEmpty()) {
                throw new ErrorException(ExceptionsTrait::emptyError('colorsCollection'));
            }
            
            $sizesFilterFinder = new SizesFilterFinder([
                'collection'=>new Collection()
            ]);
            $sizesFilterFinder->load($request);
            $sizesCollection = $sizesFilterFinder->find();
            if ($sizesCollection->isEmpty()) {
                throw new ErrorException(ExceptionsTrait::emptyError('sizesCollection'));
            }
            
            $brandsFilterFinder = new BrandsFilterFinder([
                'collection'=>new Collection()
            ]);
            $brandsFilterFinder->load($request);
            $brandsCollection = $brandsFilterFinder->find();
            if ($brandsCollection->isEmpty()) {
                throw new ErrorException(ExceptionsTrait::emptyError('brandsCollection'));
            }
            
            $dataArray['filters'] = FiltersWidget::widget([
                'colorsCollection'=>$colorsCollection,
                'sizesCollection'=>$sizesCollection,
                'brandsCollection'=>$brandsCollection,
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
