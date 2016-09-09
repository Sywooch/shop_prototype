<?php

$params = [
    # Вывод записей на страницу
    'limit'=>4, # Кол-во записей на страницу
    'pagePointer'=>'page', # Ключ, по которому в $_REQUEST доступен номер текущей страницы
    
    # Фильтры
    'filterKeys'=>['colors', 'sizes', 'brands'], # Ключи, по которым доступны значения фильтров
    
    # Путь к товару
    'categoriesKey'=>'categories', # Ключ, по которому в $_REQUEST доступна текущая категория
    'subcategoryKey'=>'subcategory', # Ключ, по которому в $_REQUEST доступна текущая подкатегория
    'idKey'=>'id', # Ключ, по которому в $_REQUEST доступен id продукта
];

return $params;
