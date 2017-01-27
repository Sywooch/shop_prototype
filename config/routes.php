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
    'admin-orders-set'=>'filters/admin-orders-set',
    'admin-orders-unset'=>'filters/admin-orders-unset',
    
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
    'admin-order-<orderId:\d+>'=>'admin/order-detail',
    'admin-orders-change-status'=>'admin/orders-change-status',
];

return $routes;
