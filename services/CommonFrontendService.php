<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use yii\helpers\{ArrayHelper,
    Url};
use app\services\ServiceInterface;
use app\exceptions\ExceptionsTrait;
use app\finders\{CategoriesFinder,
    CurrencySessionFinder,
    CurrencyFinder,
    MainCurrencyFinder,
    PurchasesSessionFinder};
use app\helpers\HashHelper;
use app\widgets\PriceWidget;
use app\forms\{ChangeCurrencyForm,
    SearchForm};
use app\savers\SessionSaver;

/**
 * Формирует массив данных для рендеринга страниц пользовательского интерфейса
 */
class CommonFrontendService extends Object implements ServiceInterface
{
    use ExceptionsTrait;
    
    /**
     * Обрабатывает запрос на поиск данных для формирования HTML страницы
     * @param array $request
     */
    public function handle($request): array
    {
        try {
            $dataArray = [];
            
            # Данные текущей валюты
            
            $key = HashHelper::createCurrencyKey();
            
            $finder = new CurrencySessionFinder([
                'key'=>$key
            ]);
            $currencyModel = $finder->find();
            if (empty($currencyModel)) {
                $finder = new MainCurrencyFinder();
                $currencyModel = $finder->find();
                if (empty($currencyModel)) {
                    throw new ErrorException($this->emptyError('currencyModel'));
                }
                $saver = new SessionSaver([
                    'key'=>$key,
                    'models'=>[$currencyModel]
                ]);
                $saver->save();
            }
            $dataArray['currencyModel'] = $currencyModel;
            
            # Данные для вывода информации о текущем пользователе
            
            $dataArray['userConfig']['user'] = \Yii::$app->user;
            $dataArray['userConfig']['view'] = 'user-info.twig';
            
            # Данные для вывода информации о состоянии корзины
            
            $finder = new PurchasesSessionFinder([
                'key'=>HashHelper::createCartKey()
            ]);
            $purchasesCollection = $finder->find();
            
            $dataArray['cartConfig']['purchases'] = $purchasesCollection;
            $dataArray['cartConfig']['currency'] = $currencyModel;
            $dataArray['cartConfig']['view'] = 'short-cart.twig';
            
            # Данные для вывода списка доступных валют
            
            $finder = new CurrencyFinder();
            $currencyArray = $finder->find();
            if (empty($currencyArray)) {
                throw new ErrorException($this->emptyError('currencyArray'));
            }
            ArrayHelper::multisort($currencyArray, 'code');
            $dataArray['currencyConfig']['currency'] = ArrayHelper::map($currencyArray, 'id', 'code');
            $dataArray['currencyConfig']['form'] = new ChangeCurrencyForm(['url'=>Url::current(), 'id'=>$currencyModel->id]);
            $dataArray['currencyConfig']['view'] = 'currency-form.twig';
            
            # Данные для вывода строки поиска
            
            $dataArray['searchConfig']['form'] = new SearchForm(['text'=>$request[\Yii::$app->params['searchKey']] ?? '', 'url'=>Url::current()]);
            $dataArray['searchConfig']['view'] = 'search.twig';
            
            # Данные для вывода меню категорий
            
            $finder = new CategoriesFinder();
            $categoriesArray = $finder->find();
            if (empty($categoriesArray)) {
                throw new ErrorException($this->emptyError('categoriesArray'));
            }
            $dataArray['menuConfig']['categories'] = $categoriesArray;
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
