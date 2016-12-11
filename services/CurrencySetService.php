<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use app\exceptions\ExceptionsTrait;
use app\services\ServiceInterface;
use app\forms\ChangeCurrencyForm;
use app\helpers\HashHelper;
use app\savers\OneSessionSaver;
use app\finders\CurrencyIdFinder;
use app\collections\BaseCollection;

/**
 * Формирует массив данных для рендеринга страницы каталога товаров
 */
class CurrencySetService extends Object implements ServiceInterface
{
    use ExceptionsTrait;
    
    /**
     * Обрабатывает запрос на сохранение товарных фильтров
     * @param array $request
     * @return string URL
     */
    public function handle($request): string
    {
        try {
            $form = new ChangeCurrencyForm(['scenario'=>ChangeCurrencyForm::CHANGE]);
            
            if ($form->load($request)) {
                if ($form->validate() === false) {
                    throw new ErrorException($this->modelError($form->errors));
                }
                
                $finder = new CurrencyIdFinder([
                    'collection'=>new BaseCollection()
                ]);
                $finder->load(['id'=>$form->id]);
                $model = $finder->find()->getModel();
                if (empty($model)) {
                    throw new ErrorException($this->emptyError('currencyModel'));
                }
                
                $key = HashHelper::createHash([\Yii::$app->params['currencyKey'], \Yii::$app->user->id ?? '']);
                
                if (!empty($key)) {
                    $saver = new OneSessionSaver();
                    $saver->load(['key'=>$key, 'model'=>$model]);
                    $saver->save();
                }
            }
            
            return $form->url;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
