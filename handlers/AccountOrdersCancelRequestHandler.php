<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\forms\PurchaseForm;
use app\finders\PurchaseIdFinder;
use app\savers\ModelSaver;
use app\models\PurchasesModel;

/**
 * Обрабатывает запрос на отмену заказа
 */
class AccountOrdersCancelRequestHandler extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на отмену заказа
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new PurchaseForm(['scenario'=>PurchaseForm::CANCEL]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(PurchaseIdFinder::class, [
                            'id'=>$form->id
                        ]);
                        $purchasesModel = $finder->find();
                        
                        if (empty($purchasesModel)) {
                            throw new ErrorException($this->emptyError('purchasesModel'));
                        }
                        
                        $purchasesModel->scenario = PurchasesModel::CANCEL;
                        
                        foreach (\Yii::$app->params['orderStatuses'] as $status) {
                            switch ($status) {
                                case 'canceled':
                                    $purchasesModel->$status = true;
                                    break;
                                case 'received':
                                    break;
                                default:
                                    $purchasesModel->$status = false;
                            }
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$purchasesModel
                        ]);
                        $saver->save();
                        
                        $transaction->commit();
                        
                        return \Yii::t('base', 'Canceled');
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
