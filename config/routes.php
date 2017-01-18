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
    'account-contact-details'=>'account/contact-details',
    'account-change-password'=>'account/change-password',
    'account-subscriptions'=>'account/subscriptions',
    
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
    
    # Расслыки
    'mailings'=>'mailings/index',
    'mailings-save'=>'mailings/save',
];

return $routes;
