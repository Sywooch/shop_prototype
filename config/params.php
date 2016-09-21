<?php

$params = [
    # Вывод записей на страницу
    'limit'=>3, # Кол-во записей на страницу
    'pagePointer'=>'page', # Ключ, по которому в $_REQUEST доступен номер текущей страницы
    
    # Фильтры
    'filterKeys'=>['colors', 'sizes', 'brands'], # Ключи, по которым доступны значения фильтров
    
    # Путь к товару
    'categoryKey'=>'category', # Ключ, по которому в $_REQUEST доступна текущая категория
    'subcategoryKey'=>'subcategory', # Ключ, по которому в $_REQUEST доступна текущая подкатегория
    'idKey'=>'id', # Ключ, по которому в $_REQUEST доступен id продукта
    
    # Поиск по товарам
    'searchKey'=>'search', # Ключ, по которому в $_REQUEST доступно значение для поиска
];

return $params;
