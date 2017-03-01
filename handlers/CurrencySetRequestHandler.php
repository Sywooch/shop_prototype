<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\forms\ChangeCurrencyForm;
use app\helpers\HashHelper;
use app\savers\SessionModelSaver;
use app\services\CurrenyUpdateService;
use app\finders\{CurrencyIdFinder,
    MainCurrencyFinder};

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
            
            $finder = \Yii::$app->registry->get(CurrencyIdFinder::class, [
                'id'=>$form->id
            ]);
            $currencyModel = $finder->find();
            if (empty($currencyModel)) {
                throw new ErrorException($this->emptyError('currencyModel'));
            }
            
            $service = \Yii::$app->registry->get(CurrenyUpdateService::class, [
                'updateCurrencyModel'=>$currencyModel,
            ]);
            $currencyModel = $service->get();
            if (empty($currencyModel)) {
                throw new ErrorException($this->emptyError('currencyModel'));
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
