<?php

$routes = [
    # Вывод списка товаров
    [
        'class'=>'app\routes\CategoriesRoute',
    ],
    
    # Фильтры
    'set-filters'=>'filters/set',
    
    # Поиск
    'search-<page:\d{1,3}>'=>'products-list/search',
    'search'=>'products-list/search',
    
    # Пользователь
    'login'=>'user/login',
    'logout'=>'user/logout',
    'registration'=>'user/registration',
    'restore'=>'user/restore',
    'forgot'=>'user/forgot',
    
    # Вывод одного товара
    '<product>'=>'product-detail/index', #!!! Make Class!
];

return $routes;
