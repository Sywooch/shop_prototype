<?php

/*$routes = [
    # Вывод одного товара
    'catalog/<category:[a-z]+>/<subcategory:[a-z]+>/<seocode>-<id:\d{1,3}>'=>'product-detail/index',
    
    # Вывод списка товаров
    'catalog/<category:[a-z]+>/<subcategory:[a-z]+>-<page:\d{1,3}>'=>'products-list/index',
    'catalog/<category:[a-z]+>/<subcategory:[a-z]+>'=>'products-list/index',
    'catalog/<category:[a-z]+>-<page:\d{1,3}>'=>'products-list/index',
    'catalog/<category:[a-z]+>'=>'products-list/index',
    
    'catalog-<page:\d{1,3}>'=>'products-list/index',
    'catalog'=>'products-list/index',
    
    # Поиск
    'search-<page:\d{1,3}>'=>'products-list/search',
    'search'=>'products-list/search',
];*/

$routes = [
    # Вывод товаров
    [
        'class'=>'app\routes\RouteClass',
    ],
    
    '-<page:\d{1,3}>'=>'products-list/index',
    ''=>'products-list/index',
    
    # Поиск
    'search-<page:\d{1,3}>'=>'products-list/search',
    'search'=>'products-list/search',
];

return $routes;
