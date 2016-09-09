<?php

$routes = [
    # Вывод товаров
    'catalog/<categories>/<subcategory>/<id:\d+>'=>'products-detail/index',
    'catalog/<categories>/<subcategory>'=>'products-list/index',
    'catalog/<categories>'=>'products-list/index',
    'catalog'=>'products-list/index',
];

return $routes;
