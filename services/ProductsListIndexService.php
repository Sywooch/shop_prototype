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
    ProductsListWidget,
    SearchWidget,
    UserInfoWidget};
use app\repositories\SessionRepository;
use app\finders\ProductsFinder;
use app\queries\LightPagination;
use app\collections\{Collection,
    PurchasesCollection};

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
            $renderArray = [];
            
            $renderArray['collection'] = ProductsListWidget::widget([
                'finder'=>new ProductsFinder(array_merge($request, [
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
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
