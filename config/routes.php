<?php

$routes = [
    # Вывод товаров
    'catalog/<category:[a-z]+>/<subcategory:[a-z]+>/<id:\d{1,3}>-<seocode>'=>'product-detail/index',
    'catalog/<category:[a-z]+>/<subcategory:[a-z]+>-<page:\d{1,3}>'=>'products-list/index',
    'catalog/<category:[a-z]+>/<subcategory:[a-z]+>'=>'products-list/index',
    'catalog/<category:[a-z]+>-<page:\d{1,3}>'=>'products-list/index',
    'catalog/<category:[a-z]+>'=>'products-list/index',
    'catalog-<page:\d{1,3}>'=>'products-list/index',
    'catalog'=>'products-list/index',
    
    # Поиск
    'search-<page:\d{1,3}>'=>'products-list/search',
    'search'=>'products-list/search',
];

return $routes;
