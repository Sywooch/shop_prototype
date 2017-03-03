<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\CurrencyForm;
use app\finders\{CurrencyFinder,
    CurrencyIdFinder};
use app\models\CurrencyModel;
use app\removers\CurrencyModelRemover;
use app\widgets\AdminCurrencyWidget;

/**
 * Обрабатывает запрос на удаление данных товара
 */
class AdminCurrencyDeleteRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * Обновляет данные товара
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new CurrencyForm(['scenario'=>CurrencyForm::DELETE]);
            
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
                        $currencyModel = $finder->find();
                        if (empty($currencyModel)) {
                            throw new ErrorException($this->emptyError('currencyModel'));
                        }
                        
                        $currencyModel->scenario = CurrencyModel::DELETE;
                        if ($currencyModel->validate() === false) {
                            throw new ErrorException($this->modelError($currencyModel->errors));
                        }
                        
                        $remover = new CurrencyModelRemover([
                            'model'=>$currencyModel
                        ]);
                        $remover->remove();
                        
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
