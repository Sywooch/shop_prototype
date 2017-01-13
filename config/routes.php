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
    
    # Валюта
    'set-currency'=>'currency/set',
    
    # Управление товарами
    'admin/add-product'=>'products-manager/add-one',
    
    # Ajax
    'ajax/subcategory'=>'ajax/subcategory',
    
    # Корзина
    'cart'=>'cart/index',
    'cart-add'=>'cart/add',
    'cart-clean'=>'cart/clean',
    'cart-clean-redirect'=>'cart/clean-redirect',
    'cart-update'=>'cart/update',
    'cart-delete'=>'cart/delete',
    'cart-сheckout'=>'cart/сheckout',
    'cart-сheckout-post'=>'cart/сheckout-post',
    'cart-confirm'=>'cart/confirm',
    
    # Подписки
    'mailing'=>'mailing/index',
    
    # Комментарии
    'comment-save'=>'comments/save',
];

return $routes;
