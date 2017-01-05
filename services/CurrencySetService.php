<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\forms\ChangeCurrencyForm;
use app\helpers\HashHelper;
use app\savers\SessionModelSaver;
use app\finders\CurrencyIdFinder;

/**
 * Сохраняет изменения текущей валюты
 */
class CurrencySetService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на изменение текущей валюты
     * @param array $request
     * @return string URL
     */
    public function handle($request): string
    {
        try {
            $form = new ChangeCurrencyForm(['scenario'=>ChangeCurrencyForm::GET]);
            
            if ($form->load($request->post()) === false) {
                throw new ErrorException($this->emptyError('request'));
            }
            
            if ($form->validate() === false) {
                throw new ErrorException($this->modelError($form->errors));
            }
            
            $finder = new CurrencyIdFinder([
                'id'=>$form->id
            ]);
            $currencyModel = $finder->find();
            if (empty($currencyModel)) {
                throw new ErrorException($this->emptyError('currencyModel'));
            }
            
            $key = HashHelper::createCurrencyKey();
            
            if (!empty($key)) {
                $saver = new SessionModelSaver([
                    'key'=>$key,
                    'model'=>$currencyModel
                ]);
                $saver->save();
            }
            
            return $form->url;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
