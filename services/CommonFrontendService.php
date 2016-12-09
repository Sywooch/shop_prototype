<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use app\services\ServiceInterface;
use app\exceptions\ExceptionsTrait;
use app\finders\{CategoriesFinder,
    CurrencyFinder,
    GroupSessionFinder,
    MainCurrencyFinder,
    OneSessionFinder};
use app\collections\{BaseCollection,
    BaseSessionCollection,
    PurchasesSessionCollection};
use app\models\{CurrencyModel,
    PurchasesModel};
use app\helpers\{HashHelper,
    SessionHelper};
use app\widgets\PriceWidget;
use app\forms\ChangeCurrencyForm;

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
            
            $finder = new OneSessionFinder([
                'collection'=>new BaseSessionCollection()
            ]);
            $finder->load(['key'=>\Yii::$app->params['currencyKey']]);
            $collection = $finder->find();
            if ($collection->isEmpty() === false) {
                $model = $collection->getModel(CurrencyModel::class);
            } else {
                $finder = new MainCurrencyFinder([
                    'collection'=>new BaseCollection()
                ]);
                $model = $finder->find()->getModel();
                if (empty($model)) {
                    throw new ErrorException($this->emptyError('currencyModel'));
                }
                SessionHelper::write(\Yii::$app->params['currencyKey'], $model->toArray());
            }
            $dataArray['currencyModel'] = $model;
            
            # Данные для вывода информации о текущем пользователе
            
            $dataArray['userConfig']['user'] = \Yii::$app->user;
            $dataArray['userConfig']['view'] = 'user-info.twig';
            
            # Данные для вывода информации о состоянии корзины
            
            $finder = new GroupSessionFinder([
                'collection'=>new PurchasesSessionCollection()
            ]);
            $finder->load(['key'=>HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? ''])]);
            $collection = $finder->find()->getModels(PurchasesModel::class);
            
            $dataArray['cartConfig']['purchasesCollection'] = $collection;
            $dataArray['cartConfig']['priceWidget'] = new PriceWidget(['currencyModel'=>$dataArray['currencyModel']]);
            $dataArray['cartConfig']['view'] = 'short-cart.twig';
            
            # Данные для вывода списка доступных валют
            
            $finder = new CurrencyFinder([
                'collection'=>new BaseCollection()
            ]);
            $collection = $finder->find()->getModels();
            if ($collection->isEmpty()) {
                throw new ErrorException($this->emptyError('currencyCollection'));
            }
            
            $dataArray['currencyConfig']['currencyCollection'] = $collection;
            $dataArray['currencyConfig']['form'] = new ChangeCurrencyForm();
            $dataArray['currencyConfig']['view'] = 'currency-form.twig';
            
            # Данные для вывода строки поиска
            
            $dataArray['searchConfig']['text'] = $request[\Yii::$app->params['searchKey']];
            $dataArray['searchConfig']['view'] = 'search.twig';
            
            # Данные для вывода меню категорий
            
            $finder = new CategoriesFinder([
                'collection'=>new BaseCollection()
            ]);
            $collection = $finder->find()->getModels();
            if ($collection->isEmpty()) {
                throw new ErrorException($this->emptyError('categoriesCollection'));
            }
            $dataArray['menuConfig']['categoriesCollection'] = $collection;
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
