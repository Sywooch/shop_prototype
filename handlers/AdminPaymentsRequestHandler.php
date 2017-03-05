<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\AdminPaymentsFinder;
use app\forms\{AbstractBaseForm,
    PaymentsForm};
use app\helpers\HashHelper;

/**
 * Обрабатывает запрос на получение данных 
 * с перечнем валют
 */
class AdminPaymentsRequestHandler extends AbstractBaseHandler
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
                $finder = \Yii::$app->registry->get(AdminPaymentsFinder::class);
                $paymentsModelArray = $finder->find();
                
                $paymentsForm = new PaymentsForm();
                
                $dataArray = [];
                
                $dataArray['adminPaymentsWidgetConfig'] = $this->adminPaymentsWidgetConfig($paymentsModelArray, $paymentsForm);
                $dataArray['adminCreatePaymentWidgetConfig'] = $this->adminCreatePaymentWidgetConfig($paymentsForm);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminCreatePaymentWidget
     * @param AbstractBaseForm $paymentsFormCreate
     */
    private function adminCreatePaymentWidgetConfig(AbstractBaseForm $paymentsForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['form'] = $paymentsForm;
            $dataArray['header'] = \Yii::t('base', 'Add payment');
            $dataArray['template'] = 'admin-create-payment.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
