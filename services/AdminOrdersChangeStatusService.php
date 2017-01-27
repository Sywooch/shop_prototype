<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\services\AbstractBaseService;
use app\forms\OrderStatusForm;
use app\finders\PurchaseIdFinder;
use app\models\PurchasesModel;
use app\savers\ModelSaver;

/**
 * Отменят заказ
 */
class AdminOrdersChangeStatusService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на отмену заказа
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new OrderStatusForm(['scenario'=>OrderStatusForm::SAVE]);
            
            if ($request->isPost === true) {
                if ($form->load($request->post()) === true) {
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(PurchaseIdFinder::class, ['id'=>$form->id]);
                        $purchasesModel = $finder->find();
                        
                        if (empty($purchasesModel)) {
                            throw new ErrorException($this->emptyError('purchasesModel'));
                        }
                        
                        $purchasesModel->scenario = PurchasesModel::UPDATE_STATUS;
                        
                        $newStatus = $form->status;
                        
                        foreach (\Yii::$app->params['orderStatuses'] as $status) {
                            switch ($status) {
                                case $newStatus:
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
                        
                        return Url::to(['/admin/orders']);
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
