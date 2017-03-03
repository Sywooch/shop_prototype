<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\finders\DeliveriesFinder;
use app\forms\{AbstractBaseForm,
    DeliveriesForm};

/**
 * Обрабатывает запрос на получение данных 
 * с перечнем валют
 */
class AdminDeliveriesRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы
     * @param yii\web\Request $request
     */
    public function handle($request)
    {
        try {
            if (empty($this->dataArray)) {
                $finder = \Yii::$app->registry->get(DeliveriesFinder::class);
                $currencyModelArray = $finder->find();
                
                $currencyForm = new DeliveriesForm();
                
                $dataArray = [];
                
                $dataArray['adminDeliveriesWidgetConfig'] = $this->adminDeliveriesWidgetConfig($currencyModelArray, $currencyForm);
                $dataArray['adminCreateDeliveriesWidgetConfig'] = $this->adminCreateDeliveriesWidgetConfig($currencyForm);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminCreateDeliveriesWidget
     * @param AbstractBaseForm $currencyFormCreate
     */
    private function adminCreateDeliveriesWidgetConfig(AbstractBaseForm $currencyForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['form'] = $currencyForm;
            $dataArray['header'] = \Yii::t('base', 'Add currency');
            $dataArray['template'] = 'admin-create-currency.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
