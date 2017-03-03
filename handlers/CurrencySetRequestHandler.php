<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\forms\ChangeCurrencyForm;
use app\helpers\HashHelper;
use app\savers\SessionModelSaver;
use app\models\CurrencyModel;

/**
 * Обновляет текущую валюту
 */
class CurrencySetRequestHandler extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на изменение текущей валюты
     * @param array $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new ChangeCurrencyForm(['scenario'=>ChangeCurrencyForm::SET]);
            
            if ($form->load($request->post()) === false) {
                throw new ErrorException($this->emptyError('POST'));
            }
            if ($form->validate() === false) {
                throw new ErrorException($this->modelError($form->errors));
            }
            
            $currencyModel = new CurrencyModel(['scenario'=>CurrencyModel::SESSION]);
            $currencyModel->id = $form->id;
            if ($currencyModel->validate() === false) {
                throw new ErrorException($this->modelError($currencyModel->errors));
            }
            
            $saver = new SessionModelSaver([
                'key'=>HashHelper::createCurrencyKey(),
                'model'=>$currencyModel
            ]);
            $saver->save();
            
            return $form->url;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
