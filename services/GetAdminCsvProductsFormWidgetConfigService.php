<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает массив конфигурации для виджета AdminCsvProductsFormWidget
 */
class GetAdminCsvProductsFormWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AdminCsvProductsFormWidget
     */
    private $adminCsvProductsFormWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @return array
     */
    public function get(): array
    {
        try {
            if (empty($this->adminCsvProductsFormWidgetArray)) {
                $dataArray = [];
                
                $dataArray['header'] = \Yii::t('base', 'Download selected products in csv format');
                $dataArray['template'] = 'admin-csv-products-form.twig';
                
                $this->adminCsvProductsFormWidgetArray = $dataArray;
            }
            
            return $this->adminCsvProductsFormWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
