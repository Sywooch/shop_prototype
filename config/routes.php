<?php

$routes = [
    # Вывод списка товаров
    [
        'class'=>'app\routes\CategoriesRoute',
    ],
    
    # Вывод одного товара
    [
        'class'=>'app\routes\ProductsRoute',
    ],
    
    # Фильтры
    'set-filters'=>'filters/set',
    'unset-filters'=>'filters/unset',
    
    # Поиск
    'search-<page:\d{1,3}>'=>'products-list/search',
    'search'=>'products-list/search',
    
    # Пользователь
    'login'=>'user/login',
    'logout'=>'user/logout',
    'registration'=>'user/registration',
    'restore'=>'user/restore',
    'forgot'=>'user/forgot',
    
    # Валюта
    'set-currency'=>'currency/set',
    
    # Управление товарами
    'admin/add-product'=>'products-manager/add-one',
    
    #Ajax
    'ajax/get-subcategory'=>'ajax/get-subcategory',
];

return $routes;
