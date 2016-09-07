<?php

$routes = [
    # Вывод товаров
    'products/<categories>/<subcategory>'=>'products-list/index',
    'products/<categories>'=>'products-list/index',
    'products'=>'products-list/index',
];

return $routes;
