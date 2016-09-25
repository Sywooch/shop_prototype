<?php

$routes = [
    # Вывод списка товаров
    [
        'class'=>'app\routes\CategoriesRoute',
    ],
    
    # Поиск
    'search-<page:\d{1,3}>'=>'products-list/search',
    'search'=>'products-list/search',
    
    # Пользователь
    'login'=>'user/login',
    'logout'=>'user/logout',
    
    # Вывод одного товара
    '<product-seocode>'=>'product-detail/index',
];

return $routes;
