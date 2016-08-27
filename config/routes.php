<?php

$routes = [
    # Вывод товаров
    'products/<categories>/<subcategory>/<id:\d+>'=>'product-detail/index',
    'products/<categories>/<subcategory>'=>'products-list/index',
    'products/<categories>'=>'products-list/index',
    'products'=>'products-list/index',
    
    # Фильтры
    'add-filters'=>'filter/add-filters',
    'add-filters-admin'=>'filter/add-filters-admin',
    'add-filters-admin-categories'=>'filter/add-filters-admin-categories',
    'add-filters-admin-subcategory'=>'filter/add-filters-admin-subcategory',
    'add-filters-admin-comments'=>'filter/add-filters-admin-comments',
    'clean-filters-admin-comments'=>'filter/clean-filters-admin-comments',
    'clean-filters'=>'filter/clean-filters',
    'clean-filters-admin'=>'filter/clean-filters-admin',
    'clean-filters-admin-categories'=>'filter/clean-filters-admin-categories',
    'clean-filters-admin-subcategory'=>'filter/clean-filters-admin-subcategory',
    'currency-filter'=>'currency/set-currency',
    
    # Поиск
    'search'=>'products-list/search',
    
    # Пользователь
    'join'=>'users/add-user',
    'login'=>'users/login-user',
    'logout'=>'users/logout-user',
    'account'=>'users/show-user-account',
    'update-user'=>'users/update-user',
    
    # Комментарии
    'add-comment'=>'comments/add-comment',
    
    # Корзина
    'add-to-cart'=>'shopping-cart/add-to-cart',
    'clear-cart'=>'shopping-cart/clear-cart',
    'remove-product'=>'shopping-cart/remove-product',
    'update-product'=>'shopping-cart/update-product',
    'shopping-cart'=>'shopping-cart/index',
    'shopping-cart-checkout'=>'shopping-cart/address-contacts',
    'shopping-cart-check-pay'=>'shopping-cart/check-pay',
    'shopping-cart-pay'=>'shopping-cart/pay',
    
    # Подписки
    'subscribe'=>'newsletter/subscribe',
    'subscribe-ok'=>'newsletter/subscribe-ok',
    'subscription-exists'=>'newsletter/subscription-exists',
    'unsubscribe/<email>/<hash>'=>'newsletter/unsubscribe',
    'unsubscribe-ok'=>'newsletter/unsubscribe-ok',
    
    # Администрирование
    'admin'=>'admin/index',
    'admin/show-products'=>'admin/show-products',
    'admin/show-products/<id:\d+>'=>'admin/show-products-detail',
    'admin/add-products'=>'admin/add-products',
    'admin/update-products-cut'=>'admin/update-products-cut',
    'admin/update-products'=>'admin/update-products',
    'admin/delete-products'=>'admin/delete-products',
    'admin/show-categories'=>'admin/show-add-categories',
    'admin/update-categories/<id:\d+>'=>'admin/update-categories',
    'admin/delete-categories/<id:\d+>'=>'admin/delete-categories',
    'admin/show-subcategory'=>'admin/show-add-subcategory',
    'admin/update-subcategory/<id:\d+>'=>'admin/update-subcategory',
    'admin/delete-subcategory/<id:\d+>'=>'admin/delete-subcategory',
    'admin/show-brands'=>'admin/show-add-brands',
    'admin/update-brands/<id:\d+>'=>'admin/update-brands',
    'admin/delete-brands/<id:\d+>'=>'admin/delete-brands',
    'admin/show-colors'=>'admin/show-add-colors',
    'admin/update-colors/<id:\d+>'=>'admin/update-colors',
    'admin/delete-colors/<id:\d+>'=>'admin/delete-colors',
    'admin/show-sizes'=>'admin/show-add-sizes',
    'admin/update-sizes/<id:\d+>'=>'admin/update-sizes',
    'admin/delete-sizes/<id:\d+>'=>'admin/delete-sizes',
    'admin/show-comments'=>'admin/show-comments',
    'admin/update-comments-cut'=>'admin/update-comments-cut',
    'admin/update-comments/<id:\d+>'=>'admin/update-comments',
    'admin/delete-comments'=>'admin/delete-comments',
    'admin/show-currency'=>'admin/show-add-currency',
    'admin/update-currency/<id:\d+>'=>'admin/update-currency',
    'admin/delete-currency'=>'admin/delete-currency',
    'admin/data-convert'=>'admin/data-convert',
    'admin/download-products'=>'admin/download-products',
    'get-subcategory-ajax'=>'categories/get-subcategory-ajax',
];

return $routes;
