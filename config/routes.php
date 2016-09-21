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

/*$routes = [
    'catalog-<page:\d{1,3}>'=>'products-list/index',
    'catalog'=>'products-list/index',
    
    # Поиск
    'search-<page:\d{1,3}>'=>'products-list/search',
    'search'=>'products-list/search',
    
    # Вывод одного товара
    '<category:[a-z]+>/<subcategory:[a-z]+>/<seocode>-<id:\d{1,3}>'=>'product-detail/index',
    
    # Вывод списка товаров
    '<category:[a-z]+>/<subcategory:[a-z]+>-<page:\d{1,3}>'=>'products-list/inside',
    '<category:[a-z]+>/<subcategory:[a-z]+>'=>'products-list/inside',
    '<category:[a-z]+>-<page:\d{1,3}>'=>'products-list/inside',
    '<category:[a-z]+>'=>'products-list/inside',
];*/

$routes = [
    # Вывод товаров без деления на категории
    'catalog-<page:\d{1,3}>'=>'products-list/index',
    'catalog'=>'products-list/index',
    
    # Поиск
    'search-<page:\d{1,3}>'=>'products-list/search',
    'search'=>'products-list/search',
    
    # Вывод товаров по категориям
    [
        'class'=>'app\routes\CategoriesRoute',
    ],
    
    # Вывод одного товара
    '<id:\d{1,3}>'=>'product-detail/index',
];

return $routes;
