<?php

$routes = [
    # Поиск
    'search-<page:\d{1,3}>'=>'products-list/search',
    'search'=>'products-list/search',
    
    # Вывод списка товаров
    [
        'class'=>'app\routes\CategoriesRoute',
    ],
    
    # Вывод одного товара
    '<id:\d{1,3}>'=>'product-detail/index',
];

return $routes;
