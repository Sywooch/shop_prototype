<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    GetProductDetailModelService};
use app\forms\PurchaseForm;

/**
 * Возвращает массив данных для PurchaseFormWidget
 */
class GetPurchaseFormWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array данные для PurchaseFormWidget
     */
    private $purchaseFormWidgetArray = [];
    
    /**
     * Возвращает массив данных для PurchaseFormWidget
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            if (empty($this->purchaseFormWidgetArray)) {
                $dataArray = [];
                
                $service = \Yii::$app->registry->get(GetProductDetailModelService::class);
                $dataArray['product'] = $service->handle($request);
                
                $dataArray['form'] = new PurchaseForm(['scenario'=>PurchaseForm::SAVE, 'quantity'=>1]);
                $dataArray['template'] = 'purchase-form.twig';
                
                $this->purchaseFormWidgetArray = $dataArray;
            }
            
            return $this->purchaseFormWidgetArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
