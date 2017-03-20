<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use app\handlers\AbstractBaseHandler;
use app\forms\ChangeCurrencyForm;
use app\helpers\HashHelper;
use app\savers\SessionModelSaver;
use app\models\CurrencyModel;

/**
 * Обновляет текущую валюту
 */
class CurrencySetRequestHandlerAjax extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на изменение текущей валюты
     * @param array $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            if ($request->isAjax === true) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                
                $id = $request->post('id') ?? null;
                $url = $request->post('url') ?? null;
                
                if (empty($id)) {
                    throw new ErrorException($this->emptyError('id'));
                }
                if (empty($url)) {
                    throw new ErrorException($this->emptyError('url'));
                }
                
                $currencyModel = new CurrencyModel(['scenario'=>CurrencyModel::SESSION]);
                $currencyModel->id = $id;
                if ($currencyModel->validate() === false) {
                    throw new ErrorException($this->modelError($currencyModel->errors));
                }
                
                $saver = new SessionModelSaver([
                    'key'=>HashHelper::createCurrencyKey(),
                    'model'=>$currencyModel
                ]);
                $saver->save();
                
                return $url;
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
