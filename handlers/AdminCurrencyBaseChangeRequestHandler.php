<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\{CurrencyIdFinder,
    CurrencyExcludeIdFinder};
use app\models\CurrencyModel;
use app\forms\CurrencyForm;
use app\savers\ModelSaver;
use app\helpers\CurrencyHelper;
use app\updaters\CurrencyArrayUpdater;
use app\finders\CurrencyFinder;
use app\widgets\AdminCurrencyWidget;

/**
 * Обрабатывает запрос на смену базовой валюты
 */
class AdminCurrencyBaseChangeRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * Меняет базовую валюту
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new CurrencyForm(['scenario'=>CurrencyForm::BASE_CHANGE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(CurrencyIdFinder::class, [
                            'id'=>$form->id
                        ]);
                        $rawCurrencyModel = $finder->find();
                        if (empty($rawCurrencyModel)) {
                            throw new ErrorException($this->emptyError('rawCurrencyModel'));
                        }
                        
                        $rawCurrencyModel->scenario = CurrencyModel::BASE_CHANGE;
                        $rawCurrencyModel->main = 1;
                        $rawCurrencyModel->exchange_rate = 1;
                        $rawCurrencyModel->update_date = time();
                        if ($rawCurrencyModel->validate() === false) {
                            throw new ErrorException($this->modelError($rawCurrencyModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$rawCurrencyModel
                        ]);
                        $saver->save();
                        
                        $finder = \Yii::$app->registry->get(CurrencyExcludeIdFinder::class, [
                            'id'=>$rawCurrencyModel->id
                        ]);
                        $existsCurrencyModelArray = $finder->find();
                        if (empty($existsCurrencyModelArray)) {
                            throw new ErrorException($this->emptyError('existsCurrencyModelArray'));
                        }
                        
                        $updateCurrencyModelArray = [];
                        foreach ($existsCurrencyModelArray as $existsCurrencyModel) {
                            $exchange_rate = CurrencyHelper::exchangeRate($rawCurrencyModel->code, $existsCurrencyModel->code);
                            $existsCurrencyModel->scenario = CurrencyModel::UPDATE;
                            $existsCurrencyModel->main = 0;
                            $existsCurrencyModel->exchange_rate = $exchange_rate;
                            $existsCurrencyModel->update_date = time();
                            if ($existsCurrencyModel->validate() === false) {
                                throw new ErrorException($this->modelError($existsCurrencyModel->errors));
                            }
                            $updateCurrencyModelArray[] = $existsCurrencyModel;
                        }
                        
                        $updater = new CurrencyArrayUpdater([
                            'models'=>$updateCurrencyModelArray
                        ]);
                        $updater->update();
                        
                        $finder = \Yii::$app->registry->get(CurrencyFinder::class);
                        $currencyModelArray = $finder->find();
                        if (empty($currencyModelArray)) {
                            throw new ErrorException($this->emptyError('currencyModelArray'));
                        }
                        
                        $currencyForm = new CurrencyForm();
                        
                        $adminCurrencyWidgetConfig = $this->adminCurrencyWidgetConfig($currencyModelArray, $currencyForm);
                        $response = AdminCurrencyWidget::widget($adminCurrencyWidgetConfig);
                        
                        $transaction->commit();
                        
                        return $response;
                    } catch (\Throwable $t) {
                        $transaction->rollBack();
                        throw $t;
                    }
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
