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
    'logout'=>'user/logout',
    'registration'=>'user/registration',
    'restore'=>'user/restore',
    'forgot'=>'user/forgot',
    
    # Валюта
    'set-currency'=>'currency/set',
    
    # Управление товарами
    'admin/add-product'=>'products-manager/add-one',
    
    # Ajax
    'ajax/subcategory'=>'ajax/subcategory',
    
    # Корзина
    'cart'=>'cart/index',
    'cart-set'=>'cart/set',
    'cart-customer'=>'cart/customer',
    'cart-check'=>'cart/check',
    'cart-clean'=>'cart/clean',
    'cart-update'=>'cart/update',
    'cart-delete'=>'cart/delete',
    'cart-send'=>'cart/send',
    'cart-complete'=>'cart/complete',
    
    # Подписки
    'mailing'=>'mailing/index',
];

return $routes;
