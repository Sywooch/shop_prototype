<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает массив конфигурации для виджета AdminCsvOrdersFormWidget
 */
class GetAdminCsvOrdersFormWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AdminCsvOrdersFormWidget
     */
    private $adminCsvOrdersFormWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->adminCsvOrdersFormWidgetArray)) {
                $dataArray = [];
                
                $dataArray['header'] = \Yii::t('base', 'Download selected orders in csv format');
                
                $dataArray['template'] = 'admin-csv-orders-form.twig';
                
                $this->adminCsvOrdersFormWidgetArray = $dataArray;
            }
            
            return $this->adminCsvOrdersFormWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
