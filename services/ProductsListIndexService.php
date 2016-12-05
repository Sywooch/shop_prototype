<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use yii\web\NotFoundHttpException;
use app\exceptions\ExceptionsTrait;
use app\models\ProductsFiltersFormModel;
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
use app\finders\{CategoriesFinder,
    CurrencyFinder,
    GroupSessionFinder,
    OneSessionFinder,
    ProductsFinder};
use app\collections\{BaseCollection,
    CurrencySessionCollection,
    LightPagination,
    ProductsCollection,
    PurchasesSessionCollection};
use app\helpers\HashHelper;
use app\forms\ChangeCurrencyForm;

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
            $dataArray = [];
            
            $productsFinder = new ProductsFinder([
                'collection'=>new ProductsCollection([
                    'pagination'=>new LightPagination()
                ])
            ]);
            $productsFinder->load($request);
            $productsCollection = $productsFinder->find()->getModels();
            if ($productsCollection->isEmpty()) {
                throw new NotFoundHttpException($this->error404());
            }
            
            $currencyFinder = new OneSessionFinder([
                'collection'=>new CurrencySessionCollection()
            ]);
            $currencyFinder->load(['key'=>\Yii::$app->params['currencyKey']]);
            $currencyModel = $currencyFinder->find()->getModel();
            if (empty($currencyModel)) {
                throw new ErrorException($this->emptyError('currencyModel'));
            }
            
            $dataArray['collection'] = ProductsListWidget::widget([
                'productsCollection'=>$productsCollection,
                'priceWidget'=>new PriceWidget(['currencyModel'=>$currencyModel]),
                'thumbnailsWidget'=>new ThumbnailsWidget(['view'=>'thumbnails.twig']),
                'paginationWidget'=>new PaginationWidget(['view'=>'pagination.twig']),
                'view'=>'products-list.twig',
            ]);
            
           $dataArray['user'] = UserInfoWidget::widget([
                'user'=>\Yii::$app->user,
                'view'=>'user-info.twig',
            ]);
            
            $purchasesFinder = new GroupSessionFinder([
                'collection'=>new PurchasesSessionCollection()
            ]);
            $purchasesFinder->load(['key'=>HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? ''])]);
            $purchasesCollection = $purchasesFinder->find()->getModels();
            $dataArray['cart'] = CartWidget::widget([
                'purchasesCollection'=>$purchasesCollection, 
                'priceWidget'=>new PriceWidget([
                    'currencyModel'=>$currencyModel, 
                ]),
                'view'=>'short-cart.twig'
            ]);
            
            $currencyFinder = new CurrencyFinder([
                'collection'=>new BaseCollection()
            ]);
            $currencyCollection = $currencyFinder->find()->getModels();
            if ($currencyCollection->isEmpty()) {
                throw new ErrorException($this->emptyError('currencyCollection'));
            }
            $dataArray['currency'] = CurrencyWidget::widget([
                'currencyCollection'=>$currencyCollection,
                'form'=>new ChangeCurrencyForm(),
                'view'=>'currency-form.twig'
            ]);
            
            $dataArray['search'] = SearchWidget::widget([
                'text'=>$request[\Yii::$app->params['searchKey']],
                'view'=>'search.twig'
            ]);
            
            $categoriesFinder = new CategoriesFinder([
                'collection'=>new BaseCollection()
            ]);
            $categoriesCollection = $categoriesFinder->find()->getModels();
            if ($categoriesCollection->isEmpty()) {
                throw new ErrorException($this->emptyError('categoriesCollection'));
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
                    throw new ErrorException($this->emptyError('categoryModel'));
                }
                $categoriesBreadcrumbsConfig['category'] = $categoryModel;
                if (!empty($subcategory = $request[\Yii::$app->params['subcategoryKey']])) {
                    $subcategorySeocodeFinder = new SubcategorySeocodeFinder();
                    $subcategorySeocodeFinder->load(['seocode'=>$subcategory]);
                    $subcategoryModel = $subcategorySeocodeFinder->find();
                    if (empty($subcategoryModel)) {
                        throw new ErrorException($this->emptyError('subcategoryModel'));
                    }
                    $categoriesBreadcrumbsConfig['subcategory'] = $subcategoryModel;
                }
            }
            $dataArray['breadcrumbs'] = CategoriesBreadcrumbsWidget::widget($categoriesBreadcrumbsConfig);
            
            $colorsFilterFinder = new ColorsFilterFinder([
                'collection'=>new BaseCollection()
            ]);
            $colorsFilterFinder->load($request);
            $colorsCollection = $colorsFilterFinder->find();
            if ($colorsCollection->isEmpty()) {
                throw new ErrorException($this->emptyError('colorsCollection'));
            }
            
            $sizesFilterFinder = new SizesFilterFinder([
                'collection'=>new BaseCollection()
            ]);
            $sizesFilterFinder->load($request);
            $sizesCollection = $sizesFilterFinder->find();
            if ($sizesCollection->isEmpty()) {
                throw new ErrorException($this->emptyError('sizesCollection'));
            }
            
            $brandsFilterFinder = new BrandsFilterFinder([
                'collection'=>new BaseCollection()
            ]);
            $brandsFilterFinder->load($request);
            $brandsCollection = $brandsFilterFinder->find();
            if ($brandsCollection->isEmpty()) {
                throw new ErrorException($this->emptyError('brandsCollection'));
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
