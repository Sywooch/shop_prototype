<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\CurrencyForm;
use app\services\CurrenyUpdateService;
use app\finders\{CurrencyFinder,
    MainCurrencyFinder};
use app\widgets\AdminCurrencyWidget;
use app\models\CurrencyModel;
use app\helpers\CurrencyHelper;
use app\savers\ModelSaver;
use app\updaters\CurrencyArrayUpdater;

/**
 * Обрабатывает запрос на добавление валюты
 */
class AdminCurrencyCreateRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * Добавляет валюту
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new CurrencyForm(['scenario'=>CurrencyForm::CREATE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $rawCurrencyModel = new CurrencyModel(['scenario'=>CurrencyModel::CREATE]);
                        $rawCurrencyModel->code = $form->code;
                        
                        if (!empty($form->main)) {
                            $finder = new CurrencyFinder();
                            $existsCurrencyModelArray = $finder->find();
                            if (empty($existsCurrencyModelArray)) {
                                throw new ErrorException($this->emptyError('existsCurrencyModelArray'));
                            }
                            
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
                        } else {
                            $finder = \Yii::$app->registry->get(MainCurrencyFinder::class);
                            $baseCurrencyModel = $finder->find();
                            if (empty($baseCurrencyModel)) {
                                throw new ErrorException($this->emptyError('baseCurrencyModel'));
                            }
                            
                            $exchange_rate = CurrencyHelper::exchangeRate($baseCurrencyModel->code, $rawCurrencyModel->code);
                            
                            $rawCurrencyModel->exchange_rate = $exchange_rate;
                            $rawCurrencyModel->update_date = time();
                            if ($rawCurrencyModel->validate() === false) {
                                throw new ErrorException($this->modelError($rawCurrencyModel->errors));
                            }
                            
                            $saver = new ModelSaver([
                                'model'=>$rawCurrencyModel
                            ]);
                            $saver->save();
                        }
                        
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
