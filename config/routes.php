<?php

$routes = [
    # Вывод товаров
    'catalog/<category:[a-z]+>/<subcategory:[a-z]+>/<seocode>/id-<id:\d{1,3}>'=>'product-detail/index',
    'catalog/<category:[a-z]+>/<subcategory:[a-z]+>/page-<page:\d{1,3}>'=>'products-list/index',
    'catalog/<category:[a-z]+>/<subcategory:[a-z]+>'=>'products-list/index',
    'catalog/<category:[a-z]+>/page-<page:\d{1,3}>'=>'products-list/index',
    'catalog/<category:[a-z]+>'=>'products-list/index',
    'catalog/page-<page:\d{1,3}>'=>'products-list/index',
    'catalog'=>'products-list/index',
];

return $routes;
