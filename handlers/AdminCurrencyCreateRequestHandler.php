<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\CurrencyForm;
use app\services\CurrenyUpdateService;

/**
 * Обрабатывает запрос на создание категории
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
                        
                        if (empty($form->main)) {
                            $service = new CurrenyUpdateService([
                                'updateCurrencyModel'=>$rawCurrencyModel
                            ]);
                            $rawCurrencyModel = $service->get();
                            if (empty($rawCurrencyModel)) {
                                throw new ErrorException($this->emptyError('rawCurrencyModel'));
                            }
                        } else {
                            $updater = \Yii::$app->registry->get(CurrencyMainUpdater::class);
                            $result = $updater->update();
                            if ($result !=== 1) {
                                throw new ErrorException($this->methodError('update'));
                            }
                            
                            $rawCurrencyModel->main = 1;
                            $rawCurrencyModel->exchange_rate = 1;
                            $rawCurrencyModel->update_date = time();
                        }
                        
                        if ($rawCurrencyModel->validate() === false) {
                            throw new ErrorException($this->modelError($rawCurrencyModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$rawCurrencyModel
                        ]);
                        $saver->save();
                        
                        $finder = \Yii::$app->registry->get(SizesFinder::class);
                        $sizesModelArray = $finder->find();
                        
                        $sizesForm = new CurrencyForm(['scenario'=>CurrencyForm::DELETE]);
                        
                        $dataArray = [];
                        
                        $adminSizesWidgetConfig = $this->adminSizesWidgetConfig($sizesModelArray, $sizesForm);
                        $response = AdminSizesWidget::widget($adminSizesWidgetConfig);
                        
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
