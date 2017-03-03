<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\AdminDeliveriesFinder;
use app\forms\{AbstractBaseForm,
    DeliveriesForm};
use app\services\GetCurrentCurrencyModelService;
use app\helpers\HashHelper;

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
                $finder = \Yii::$app->registry->get(AdminDeliveriesFinder::class);
                $deliveriesModelArray = $finder->find();
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                    'key'=>HashHelper::createCurrencyKey()
                ]);
                $currentCurrencyModel = $service->get();
                if (empty($currentCurrencyModel)) {
                    throw new ErrorException($this->emptyError('currentCurrencyModel'));
                }
                
                $deliveriesForm = new DeliveriesForm();
                
                $dataArray = [];
                
                $dataArray['adminDeliveriesWidgetConfig'] = $this->adminDeliveriesWidgetConfig($deliveriesModelArray, $currentCurrencyModel, $deliveriesForm);
                $dataArray['adminCreateDeliveriesWidgetConfig'] = $this->adminCreateDeliveriesWidgetConfig($deliveriesForm);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminCreateDeliveriesWidget
     * @param AbstractBaseForm $deliveriesFormCreate
     */
    private function adminCreateDeliveriesWidgetConfig(AbstractBaseForm $deliveriesForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['form'] = $deliveriesForm;
            $dataArray['header'] = \Yii::t('base', 'Add delivery');
            $dataArray['template'] = 'admin-create-delivery.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
