<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\PurchaseIdFinder;

/**
 * Формирует массив данных для рендеринга страницы 
 * с деталями заказа
 */
class AdminOrderDetailService extends AbstractBaseService
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $id = $request->get(\Yii::$app->params['orderId']);
            
            if (empty($id)) {
                throw new ErrorException($this->emptyError('orderId'));
            }
            
            if (empty($this->dataArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(PurchaseIdFinder::class, ['id'=>$id]);
                $purchasesModel = $finder->find();
                
                if (empty($purchasesModel)) {
                    throw new ErrorException($this->emptyError('purchasesModel'));
                }
                
                $dataArray['purchase'] = '';
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
