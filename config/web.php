<?php

$config = [
    'id'=>'shop',
    'basePath'=>__DIR__ . '/../',
    'language'=>'ru-RU',
    'sourceLanguage'=>'en-US',
    'timeZone'=>'Europe/Kiev',
    'layout'=>'main.twig',
    'bootstrap'=>['log'],
    
    'components'=>[
        'db'=>require(__DIR__ . '/db.php'),
        'view'=>[
            'class'=>'yii\web\View',
            'renderers'=>[
                'twig'=>[
                    'class'=>'yii\twig\ViewRenderer',
                    'options'=>['auto_reload'=>true],
                    'globals'=>[
                        'html'=>'yii\helpers\Html',
                        'array'=>'yii\helpers\ArrayHelper',
                        'url'=>'yii\helpers\Url',
                        'objectsToArrayHelper'=>'app\helpers\ObjectsToArrayHelper',
                        'picturesHelper'=>'app\helpers\PicturesHelper',
                    ],
                ]
            ],
        ],
        'request'=>[
            'cookieValidationKey'=>md5('sLkuN'),
        ],
        'log'=>[
            'traceLevel'=>YII_DEBUG ? 3 : 0,
            'flushInterval'=>100,
            'targets'=>[
                'file'=>[
                    'class'=>'yii\log\FileTarget',
                    'logFile'=>__DIR__ . '/../logs/error.log',
                    'levels'=>['error', 'warning'],
                    'exportInterval'=>100,
                ],
            ],
        ],
        'assetManager'=>[
            'bundles'=>[
                'yii\web\JqueryAsset'=>[
                    'sourcePath'=>null,
                    'js'=>['https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js']
                ],
            ],
            'basePath'=>'@webroot/sources/temp',
            'baseUrl'=>'@web/sources/temp',
            'appendTimestamp'=>true
        ],
        'urlManager'=>[
            'enablePrettyUrl'=>true,
            'showScriptName'=>false,
            'enableStrictParsing' => false,
            'rules'=>[
                'products/<categories>/<subcategory>/<id:\d+>'=>'product-detail/index',
                'products/<categories>/<subcategory>'=>'products-list/index',
                'products/<categories>'=>'products-list/index',
                'products'=>'products-list/index',
                'add-filters'=>'filter/add-filters',
                'clean-filters'=>'filter/clean-filters',
                'currency-filter'=>'currency/set-currency',
                'search'=>'products-list/search',
                'join'=>'users/add-user',
                'login'=>'users/login-user',
                'logout'=>'users/logout-user',
                'account'=>'users/show-user-account',
                'add-comment'=>'comments/add-comment',
                'add-to-cart'=>'shopping-cart/add-to-cart',
                'clear-cart'=>'shopping-cart/clear-cart',
                'remove-product'=>'shopping-cart/remove-product',
                'update-product'=>'shopping-cart/update-product',
                'shopping-cart'=>'shopping-cart/index',
                'shopping-cart-checkout'=>'shopping-cart/address-contacts',
                'shopping-cart-check-pay'=>'shopping-cart/check-pay',
                'shopping-cart-pay'=>'shopping-cart/pay',
                'add-product'=>'products-manager/add-product',
                'get-subcategory-ajax'=>'products-manager/get-subcategory-ajax',
            ],
        ],
        'cart'=>[
            'class'=>'app\cart\ShoppingCart',
        ],
        'filters'=>[
            'class'=>'app\models\FiltersModel',
        ],
        'user'=>[
            'class'=>'app\models\UsersModel',
            'login'=>'Guest',
        ],
        'session'=>[
            'class'=>'yii\web\DbSession',
            'timeout'=>60*60*24*7
        ],
        'mailer'=>[
            'class'=>'yii\swiftmailer\Mailer',
            'viewPath'=>'@app/views/mail',
        ],
    ],
    
    'aliases'=>[
        '@pic'=>'/var/www/html/shop/web/sources/images/products',
        '@wpic'=>'/sources/images/products',
    ],
    
    'as shoppingCartFilter'=>['class'=>'app\filters\ShoppingCartFilter'],
    'as usersFilter'=>['class'=>'app\filters\UsersFilter'],
    
    'params'=>[
        # Вывод записей на страницу
        'limit'=>20, # Кол-во записей на страницу
        'pagePointer'=>'page', # Ключ, по которому в $_REQUEST доступен номер текущей страницы
        
        # Фильтры
        'filterKeys'=>['colors', 'sizes', 'brands'], # Ключи, по которым в $_REQUEST доступны значения выбранных фильтров
        'filtersKeyInSession'=>'filters', # Ключ, по которому в $_SESSION доступена переменная, хранящая выбранные фильтры
        
        # Путь к товару
        'categoryKey'=>'categories', # Ключ, по которому в $_REQUEST доступно название категории
        'subCategoryKey'=>'subcategory', # Ключ, по которому в $_REQUEST доступно название подкатегории
        'idKey'=>'id', # Ключ, по которому в $_REQUEST доступно значение id продукта
        
        # Поиск по товарам
        'searchKey'=>'search', # Ключ, по которому в $_REQUEST доступно значение для поиска
        
        # Сортировка данных СУБД
        'defaultOrderByType'=>'DESC', # Порядок сортировки для БД по умолчанию
        
        # Отладка
        'fixSentRequests'=>0, #Количество запросов к БД при выполнении скрипта
        
        # Корзина
        'cartKeyInSession'=>'cart', # Ключ, по которому в $_SESSION доступена переменная, хранящая купленные товары
        
        # Пользователи
        'defaultRulesId'=>[1, 4], # ID прав доступа по-умолчанию, назначаемые при регистрации
        'userFromFormForAuthentication'=>null, # Объект пользователя в процессе аутентификации
        'usersKeyInSession'=>'user', # Ключ, по которому в $_SESSION доступена переменная, пользователя
        'nonAuthenticatedUserLogin'=>'Guest', # логин не аутентифицированного пользователя, доступный в \Yii::$app->user по умолчанию
        
        # Изображения
        'maxWidth'=>1000, # максимально допустимая ширина сохраняемого изображения
        'maxHeight'=>700, # максимально допустимая высота сохраняемого изображения
        'maxThumbnailWidth'=>500, # максимально допустимая ширина сохраняемого эскиза изображения
        'maxThumbnailHeight'=>500, # максимально допустимая высота сохраняемого эскиза изображения
        'thumbnailsPrefix'=>'thumbn_', # префикс эскизов изображений
        'thumbnails'=>'thumbnails', # ключ по которому доступен массив эскизов изображений, в массиве, возвращаемом app\helpers\PicturesHelper
        'fullpath'=>'fullpath', # ключ по которому доступен массив полноразмерных изображений, в массиве, возвращаемом app\helpers\PicturesHelper
    ],
];

if (YII_DEBUG) {
    $config['as checkScriptInfoFilter'] = ['class'=>'app\filters\CheckScriptInfoFilter'];
    $config['as csrfSwitch'] = ['class'=>'app\filters\CsrfSwitch'];
}

return $config;
