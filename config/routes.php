<?php

$routes = [
    # Вывод списка товаров
    [
        'class'=>'app\routes\CategoriesRoute',
    ],
    
    # Вывод одного товара
    [
        'class'=>'app\routes\ProductsRoute',
    ],
    
    # Фильтры
    'set-filters'=>'filters/set',
    'unset-filters'=>'filters/unset',
    'filters-orders-set'=>'filters/orders-set',
    'filters-orders-unset'=>'filters/orders-unset',
    'filters-admin-products-set'=>'filters/admin-products-set',
    'filters-admin-products-unset'=>'filters/admin-products-unset',
    'filters-users-set'=>'filters/users-set',
    'filters-users-unset'=>'filters/users-unset',
    
    # Поиск
    'search-<page:\d{1,3}>'=>'products-list/search',
    'search'=>'products-list/search',
    
    # Пользователь
    'login'=>'user/login',
    'login-post'=>'user/login-post',
    'logout'=>'user/logout',
    'registration'=>'user/registration',
    'registration-post'=>'user/registration-post',
    'recovery'=>'user/recovery',
    'recovery-post'=>'user/recovery-post',
    'generate'=>'user/generate',
    
    # Аккаунт
    'account'=>'account/index',
    'account-orders-<page:\d{1,3}>'=>'account/orders',
    'account-orders'=>'account/orders',
    'account-order-cancel'=>'account/order-cancel',
    'account-data'=>'account/data',
    'account-change-data-post'=>'account/change-data-post',
    'account-password'=>'account/password',
    'account-change-password-post'=>'account/change-password-post',
    'account-subscriptions'=>'account/subscriptions',
    'account-subscriptions-cancel'=>'account/subscriptions-cancel',
    'account-subscriptions-add'=>'account/subscriptions-add',
    
    # Валюта
    'set-currency'=>'currency/set',
    
    # Корзина
    'cart-add'=>'cart/add',
    'cart-clean'=>'cart/clean',
    'cart-clean-redirect'=>'cart/clean-redirect',
    'cart-update'=>'cart/update',
    'cart-delete'=>'cart/delete',
    'cart'=>'cart/index',
    'cart-сheckout-ajax-form'=>'cart/сheckout-ajax-form',
    'cart-сheckout-ajax'=>'cart/сheckout-ajax',
    
    # Комментарии
    'comment-save'=>'comments/save',
    
    # Рассылки
    'mailings'=>'mailings/index',
    'mailings-save'=>'mailings/save',
    'unsubscribe'=>'mailings/unsubscribe',
    'unsubscribe-post'=>'mailings/unsubscribe-post',
    
    # Админ
    'admin'=>'admin/index',
    'admin-orders-<page:\d{1,3}>'=>'admin/orders',
    'admin-orders'=>'admin/orders',
    'admin-order-detail-form'=>'admin/order-detail-form',
    'admin-order-detail-change'=>'admin/order-detail-change',
    'admin-products-<page:\d{1,3}>'=>'admin/products',
    'admin-products'=>'admin/products',
    'admin-product-detail-form'=>'admin/product-detail-form',
    'admin-product-detail-change'=>'admin/product-detail-change',
    'admin-product-detail-delete'=>'admin/product-detail-delete',
    'admin-add-product'=>'admin/add-product',
    'admin-add-product-post'=>'admin/add-product-post',
    'admin-categories'=>'admin/categories',
    'admin-categories-category-create'=>'admin/categories-category-create',
    'admin-categories-subcategory-create'=>'admin/categories-subcategory-create',
    'admin-categories-category-delete'=>'admin/categories-category-delete',
    'admin-categories-subcategory-delete'=>'admin/categories-subcategory-delete',
    'admin-brands'=>'admin/brands',
    'admin-brand-create'=>'admin/brand-create',
    'admin-brand-delete'=>'admin/brand-delete',
    'admin-colors'=>'admin/colors',
    'admin-color-create'=>'admin/color-create',
    'admin-color-delete'=>'admin/color-delete',
    'admin-sizes'=>'admin/sizes',
    'admin-size-create'=>'admin/size-create',
    'admin-size-delete'=>'admin/size-delete',
    'admin-users-<page:\d{1,3}>'=>'admin/users',
    'admin-users'=>'admin/users',
    'admin-user-<id:\d{1,4}>'=>'admin/user-detail',
    'admin-user-orders-<id:\d{1,4}>'=>'admin/user-orders',
    'admin-user-data-<id:\d{1,4}>'=>'admin/user-data',
    'admin-user-password-<id:\d{1,4}>'=>'admin/user-password',
    'admin-user-subscriptions-<id:\d{1,4}>'=>'admin/user-subscriptions',
    
    # Календарь
    'calendar-get'=>'calendar/get',
    
    # Csv
    'csv-get-orders'=>'csv/get-orders',
    'csv-get-products'=>'csv/get-products',
    'csv-get-users'=>'csv/get-users',
    
    # Категории
    'categories-get-subcategory'=>'categories/get-subcategory',
    
    # Отладчик
    'debug/<controller>/<action>'=>'debug/<controller>/<action>',
];

return $routes;
