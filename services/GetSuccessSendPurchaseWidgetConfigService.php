<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает массив конфигурации для виджета SuccessSendPurchaseWidget
 */
class GetSuccessSendPurchaseWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета SuccessSendPurchaseWidget
     */
    private $successSendPurchaseWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета SuccessSendPurchaseWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->successSendPurchaseWidgetArray)) {
                $dataArray = [];
                
                $dataArray['view'] = 'success-send-purchase.twig';
                
                $this->successSendPurchaseWidgetArray = $dataArray;
            }
            
            return $this->successSendPurchaseWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
