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
    AdminCategoriesCategoryChangeRequestHandler,
    AdminCategoriesCategoryCreateRequestHandler,
    AdminCategoriesCategoryDeleteRequestHandler,
    AdminCategoriesSubcategoryChangeRequestHandler,
    AdminCategoriesSubcategoryCreateRequestHandler,
    AdminCategoriesSubcategoryDeleteRequestHandler,
    AdminCategoriesRequestHandler,
    AdminColorCreateRequestHandler,
    AdminColorDeleteRequestHandler,
    AdminColorsRequestHandler,
    AdminCommentChangeRequestHandler,
    AdminCommentDeleteRequestHandler,
    AdminCommentFormRequestHandler,
    AdminCommentsRequestHandler,
    AdminCurrencyBaseChangeRequestHandler,
    AdminCurrencyCreateRequestHandler,
    AdminCurrencyDeleteRequestHandler,
    AdminCurrencyRequestHandler,
    AdminDeliveryChangeRequestHandler,
    AdminDeliveryCreateRequestHandler,
    AdminDeliveryDeleteRequestHandler,
    AdminDeliveryFormRequestHandler,
    AdminDeliveriesRequestHandler,
    AdminIndexRequestHandler,
    AdminMailingChangeRequestHandler,
    AdminMailingCreateRequestHandler,
    AdminMailingDeleteRequestHandler,
    AdminMailingFormRequestHandler,
    AdminMailingsRequestHandler,
    AdminOrderDetailChangeRequestHandler,
    AdminOrderDetailFormRequestHandler,
    AdminOrdersRequestHandler,
    AdminPaymentChangeRequestHandler,
    AdminPaymentCreateRequestHandler,
    AdminPaymentDeleteRequestHandler,
    AdminPaymentFormRequestHandler,
    AdminPaymentsRequestHandler,
    AdminProductDetailChangeRequestHandler,
    AdminProductDetailDeleteRequestHandler,
    AdminProductDetailFormRequestHandler,
    AdminProductsRequestHandler,
    AdminSizeCreateRequestHandler,
    AdminSizeDeleteRequestHandler,
    AdminSizesRequestHandler,
    AdminUserDataChangePostRequestHandler,
    AdminUserDataRequestHandler,
    AdminUserDetailRequestHandler,
    AdminUserOrdersRequestHandler,
    AdminUserPasswordRequestHandler,
    AdminUserPasswordChangePostRequestHandler,
    AdminUsersRequestHandler,
    AdminUserSubscriptionsAddRequestHandler,
    AdminUserSubscriptionsCancelRequestHandler,
    AdminUserSubscriptionsRequestHandler};

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
            'categories-category-change'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminCategoriesCategoryChangeRequestHandler(),
            ],
            'categories-category-delete'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminCategoriesCategoryDeleteRequestHandler(),
            ],
            'categories-subcategory-create'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminCategoriesSubcategoryCreateRequestHandler(),
            ],
            'categories-subcategory-change'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminCategoriesSubcategoryChangeRequestHandler(),
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
            'users'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminUsersRequestHandler(),
                'view'=>'users.twig',
            ],
            'user-detail'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminUserDetailRequestHandler(),
                'view'=>'user-detail.twig',
            ],
            'user-orders'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminUserOrdersRequestHandler(),
                'view'=>'user-orders.twig',
            ],
            'user-data'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminUserDataRequestHandler(),
                'view'=>'user-data.twig',
            ],
            'user-data-change-post'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminUserDataChangePostRequestHandler(),
            ],
            'user-password'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminUserPasswordRequestHandler(),
                'view'=>'user-password.twig',
            ],
            'user-password-change-post'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminUserPasswordChangePostRequestHandler(),
            ],
            'user-subscriptions'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminUserSubscriptionsRequestHandler(),
                'view'=>'user-subscriptions.twig',
            ],
            'user-subscriptions-cancel'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminUserSubscriptionsCancelRequestHandler(),
            ],
            'user-subscriptions-add'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminUserSubscriptionsAddRequestHandler(),
            ],
            'comments'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminCommentsRequestHandler(),
                'view'=>'comments.twig',
            ],
            'comment-delete'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminCommentDeleteRequestHandler(),
            ],
            'comment-form'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminCommentFormRequestHandler(),
            ],
            'comment-change'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminCommentChangeRequestHandler(),
            ],
            'currency'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminCurrencyRequestHandler(),
                'view'=>'currency.twig',
            ],
            'currency-create'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminCurrencyCreateRequestHandler(),
            ],
            'currency-delete'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminCurrencyDeleteRequestHandler(),
            ],
            'currency-base-change'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminCurrencyBaseChangeRequestHandler(),
            ],
            'deliveries'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminDeliveriesRequestHandler(),
                'view'=>'deliveries.twig',
            ],
            'delivery-create'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminDeliveryCreateRequestHandler(),
            ],
            'delivery-form'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminDeliveryFormRequestHandler(),
            ],
            'delivery-change'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminDeliveryChangeRequestHandler(),
            ],
            'delivery-delete'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminDeliveryDeleteRequestHandler(),
            ],
            'payments'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminPaymentsRequestHandler(),
                'view'=>'payments.twig',
            ],
            'payment-create'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminPaymentCreateRequestHandler(),
            ],
            'payment-form'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminPaymentFormRequestHandler(),
            ],
            'payment-change'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminPaymentChangeRequestHandler(),
            ],
            'payment-delete'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminPaymentDeleteRequestHandler(),
            ],
            'mailings'=>[
                'class'=>GetAction::class,
                'handler'=>new AdminMailingsRequestHandler(),
                'view'=>'mailings.twig',
            ],
            'mailing-create'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminMailingCreateRequestHandler(),
            ],
            'mailing-form'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminMailingFormRequestHandler(),
            ],
            'mailing-change'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminMailingChangeRequestHandler(),
            ],
            'mailing-delete'=>[
                'class'=>AjaxAction::class,
                'handler'=>new AdminMailingDeleteRequestHandler(),
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
