<?php

namespace app\services;

use yii\base\{ErrorException,
    Model,
    Object};
use app\exceptions\ExceptionsTrait;
use app\models\{Collection,
    CollectionInterface,
    ChangeCurrencyFormModel,
    CurrencyModel,
    ProductsFiltersFormModel,
    PurchasesCollection,
    PurchasesModel};
use app\services\{BrandsFilterSearch,
    CategoryOneSearchService,
    CategoriesMenuSearchService,
    ColorsFilterSearch,
    CurrencyCollectionSearchService,
    SearchServiceInterface,
    SizesFilterSearch};
use app\widgets\{CategoriesBreadcrumbsWidget,
    CategoriesMenuWidget,
    CartWidget,
    CurrencyWidget,
    FiltersWidget,
    ProductsListWidget,
    SearchWidget,
    UserInfoWidget};
use app\repositories\SessionRepository;
use app\search\ProductsSearchModel;
use app\queries\LightPagination;

class ProductsListIndexService extends Object implements SearchServiceInterface
{
    use ExceptionsTrait;
    
    /**
     * @var object Model
     */
    private $productsSearchModel;
    
    public function init()
    {
        try {
            parent::init();
            
            /*if (empty($this->productsSearchModel)) {
                throw new ErrorException(ExceptionsTrait::emptyError('productsSearchModel'));
            }*/
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на поиск информации для 
     * формирования страницы каталога товаров
     * @param array $request
     */
    public function search($request)
    {
        try {
            $renderArray = [];
            
            $renderArray['collection'] = ProductsListWidget::widget([
                'searchModel'=>new ProductsSearchModel(array_merge($request, [
                    'collection'=>new Collection([
                        'pagination'=>new LightPagination()
                    ])
                ])),
                'view'=>'products-list.twig',
            ]);
           
           $renderArray['user'] = UserInfoWidget::widget([
                'user'=>\Yii::$app->user,
                'view'=>'user-info.twig',
            ]);
            
            $renderArray['cart'] = CartWidget::widget([
                'repository'=>new SessionRepository([
                    'collection'=>new PurchasesCollection(),
                    'class'=>PurchasesModel::class
                ]), 
                'repositoryCurrency'=>new SessionRepository([
                    'class'=>CurrencyModel::class
                ]), 
                'view'=>'short-cart.twig'
            ]);
            
            $renderArray['currency'] = CurrencyWidget::widget([
                'service'=>new CurrencyCollectionSearchService([
                    'collection'=>new Collection(),
                ]),
                'form'=>new ChangeCurrencyFormModel(),
                'view'=>'currency-form.twig'
            ]);
            
            $renderArray['search'] = SearchWidget::widget([
                'view'=>'search.twig'
            ]);
            
            $renderArray['menu'] = CategoriesMenuWidget::widget([
                'service'=>new CategoriesMenuSearchService([
                    'collection'=>new Collection(),
                ]),
            ]);
            
            $renderArray['breadcrumbs'] = CategoriesBreadcrumbsWidget::widget([
                'service'=>new CategoryOneSearchService(),
                'category'=>$request[\Yii::$app->params['categoryKey']],
                'subcategory'=>$request[\Yii::$app->params['subcategoryKey']],
            ]);
            
            $renderArray['filters'] = FiltersWidget::widget([
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
            
            return $renderArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Model свойству ProductsListIndexService::productsSearchModel
     * @param object $model Model
     */
    public function setProductsSearchModel(Model $model)
    {
        try {
            $this->productsSearchModel = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
