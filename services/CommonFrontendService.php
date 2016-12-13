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
    GroupSessionFinder,
    MainCurrencyFinder,
    OneSessionFinder,
    PurchasesSessionFinder};
use app\collections\{BaseCollection,
    BaseSessionCollection,
    PurchasesSessionCollection};
use app\models\{CurrencyModel,
    PurchasesModel};
use app\helpers\{HashHelper,
    SessionHelper};
use app\widgets\PriceWidget;
use app\forms\ChangeCurrencyForm;
use app\savers\OneSessionSaver;

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
            
            $key = HashHelper::createHash([\Yii::$app->params['currencyKey'], \Yii::$app->user->id ?? '']);
            
            $finder = new CurrencySessionFinder();
            if (!empty($key)) {
                $finder->load(['key'=>$key]);
            }
            $model = $finder->find();
            if (empty($model)) {
                $finder = new MainCurrencyFinder();
                $model = $finder->find();
                if (empty($model)) {
                    throw new ErrorException($this->emptyError('currencyModel'));
                }
                if (!empty($key)) {
                    $saver = new OneSessionSaver();
                    $saver->load(['key'=>$key, 'model'=>$model]);
                    $saver->save();
                }
            }
            $dataArray['currencyModel'] = $model;
            
            # Данные для вывода информации о текущем пользователе
            
            $dataArray['userConfig']['user'] = \Yii::$app->user;
            $dataArray['userConfig']['view'] = 'user-info.twig';
            
            # Данные для вывода информации о состоянии корзины
            
            $finder = new PurchasesSessionFinder();
            $finder->load(['key'=>HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? ''])]);
            $collection = $finder->find();
            
            $dataArray['cartConfig']['purchasesCollection'] = $collection;
            $dataArray['cartConfig']['priceWidget'] = new PriceWidget(['currencyModel'=>$dataArray['currencyModel']]);
            $dataArray['cartConfig']['view'] = 'short-cart.twig';
            
            # Данные для вывода списка доступных валют
            
            $finder = new CurrencyFinder();
            $currencyArray = $finder->find();
            if (empty($currencyArray)) {
                throw new ErrorException($this->emptyError('currencyArray'));
            }
            ArrayHelper::multisort($currencyArray, 'code');
            $currencyArray = ArrayHelper::map($currencyArray, 'id', 'code');
            $dataArray['currencyConfig']['currencyArray'] = $currencyArray;
            
            $dataArray['currencyConfig']['form'] = new ChangeCurrencyForm(['url'=>Url::current(), 'id'=>$dataArray['currencyModel']->id]);
            $dataArray['currencyConfig']['view'] = 'currency-form.twig';
            
            # Данные для вывода строки поиска
            
            $dataArray['searchConfig']['text'] = $request[\Yii::$app->params['searchKey']] ?? '';
            $dataArray['searchConfig']['view'] = 'search.twig';
            
            # Данные для вывода меню категорий
            
            $finder = new CategoriesFinder();
            $categoriesArray = $finder->find();
            if (empty($categoriesArray)) {
                throw new ErrorException($this->emptyError('categoriesArray'));
            }
            $dataArray['menuConfig']['categoriesArray'] = $categoriesArray;
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
