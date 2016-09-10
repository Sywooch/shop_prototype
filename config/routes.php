<?php

$routes = [
    # Вывод товаров
    'catalog/<category>/<subcategory>/<id:\d+>'=>'product-detail/index',
    'catalog/<category>/<subcategory>'=>'products-list/index',
    'catalog/<category>'=>'products-list/index',
    'catalog'=>'products-list/index',
];

return $routes;
