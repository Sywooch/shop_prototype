<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\{AjaxAction,
    GetAction};
use app\handlers\{AdminAddProductRequestHandler,
    AdminAddProductPostRequestHandler,
    AdminBrandCreateRequestHandler,
    AdminBrandDeleteRequestHandler,
    AdminBrandsRequestHandler,
    AdminCategoriesCategoryCreateRequestHandler,
    AdminCategoriesCategoryDeleteRequestHandler,
    AdminCategoriesSubcategoryCreateRequestHandler,
    AdminCategoriesSubcategoryDeleteRequestHandler,
    AdminCategoriesRequestHandler,
    AdminColorCreateRequestHandler,
    AdminColorDeleteRequestHandler,
    AdminColorsRequestHandler,
    AdminIndexRequestHandler,
    AdminOrderDetailChangeRequestHandler,
    AdminOrderDetailFormRequestHandler,
    AdminOrdersRequestHandler,
    AdminProductDetailChangeRequestHandler,
    AdminProductDetailDeleteRequestHandler,
    AdminProductDetailFormRequestHandler,
    AdminProductsRequestHandler,
    AdminSizeCreateRequestHandler,
    AdminSizeDeleteRequestHandler,
    AdminSizesRequestHandler};

/**
 * Обрабатывает запросы к админ разделу
 */
class AdminController extends Controller
{
    public function actions()
    {
        return [
            'index'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminIndexRequestHandler(),
                'view'=>'index.twig',
            ],
            'orders'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminOrdersRequestHandler(),
                'view'=>'orders.twig',
            ],
            'order-detail-form'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminOrderDetailFormRequestHandler()
            ],
            'order-detail-change'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminOrderDetailChangeRequestHandler(),
            ],
            'products'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminProductsRequestHandler(),
                'view'=>'products.twig',
            ],
            'product-detail-form'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminProductDetailFormRequestHandler(),
            ],
            'product-detail-change'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminProductDetailChangeRequestHandler(),
            ],
            'product-detail-delete'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminProductDetailDeleteRequestHandler(),
            ],
            'add-product'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminAddProductRequestHandler(),
                'view'=>'add-product.twig',
            ],
            'add-product-post'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminAddProductPostRequestHandler(),
            ],
            'categories'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminCategoriesRequestHandler(),
                'view'=>'categories.twig',
            ],
            'categories-category-create'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminCategoriesCategoryCreateRequestHandler(),
            ],
            'categories-subcategory-create'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminCategoriesSubcategoryCreateRequestHandler(),
            ],
            'categories-category-delete'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminCategoriesCategoryDeleteRequestHandler(),
            ],
            'categories-subcategory-delete'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminCategoriesSubcategoryDeleteRequestHandler(),
            ],
            'brands'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminBrandsRequestHandler(),
                'view'=>'brands.twig',
            ],
            'brand-delete'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminBrandDeleteRequestHandler(),
            ],
            'brand-create'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminBrandCreateRequestHandler(),
            ],
            'colors'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminColorsRequestHandler(),
                'view'=>'colors.twig',
            ],
            'color-delete'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminColorDeleteRequestHandler(),
            ],
            'color-create'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminColorCreateRequestHandler(),
            ],
            'sizes'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminSizesRequestHandler(),
                'view'=>'sizes.twig',
            ],
            'size-delete'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminSizeDeleteRequestHandler(),
            ],
            'size-create'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminSizeCreateRequestHandler(),
            ],
        ];
    }
    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::class,
                'rules'=>[
                    [
                        'allow'=>true,
                        'roles'=>['superUser']
                    ],
                    [
                        'allow'=>false,
                        'roles'=>['?', '@']
                    ],
                ],
            ],
        ];
    }
}
