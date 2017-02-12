<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\services\GetCurrentCurrencyModelService;
use app\finders\{AdminOrdersFinder,
    OrdersFiltersSessionFinder,
    OrderStatusesFinder,
    SortingTypesFinder};
use app\forms\{AbstractBaseForm,
    AdminChangeOrderForm,
    OrdersFiltersForm};
use app\helpers\HashHelper;
use app\filters\OrdersFilters;
use app\models\CurrencyInterface;

/**
 * Обрабатывает запрос на получение данных 
 * с перечнем заказов
 */
class AdminOrdersRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
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
            if (empty($this->dataArray)) {
                $page = $request->get(\Yii::$app->params['pagePointer']) ?? 0;
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                    'key'=>HashHelper::createCurrencyKey()
                ]);
                $currentCurrencyModel = $service->get();
                if (empty($currentCurrencyModel)) {
                    throw new ErrorException($this->emptyError('currentCurrencyModel'));
                }
                
                $finder = \Yii::$app->registry->get(OrdersFiltersSessionFinder::class, [
                    'key'=>HashHelper::createHash([\Yii::$app->params['ordersFilters']])
                ]);
                $filtersModel = $finder->find();
                
                $finder = \Yii::$app->registry->get(AdminOrdersFinder::class, [
                    'page'=>$page,
                    'filters'=>$filtersModel
                ]);
                $ordersCollection = $finder->find();
                
                if ($ordersCollection->isEmpty() === true) {
                    if ($ordersCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                }
                
                $finder = \Yii::$app->registry->get(SortingTypesFinder::class);
                $sortingTypesArray = $finder->find();
                if (empty($sortingTypesArray)) {
                    throw new ErrorException($this->emptyError('sortingTypesArray'));
                }
                
                $finder = \Yii::$app->registry->get(OrderStatusesFinder::class);
                $statusesArray = $finder->find();
                
                $ordersFiltersForm = new OrdersFiltersForm(array_filter($filtersModel->toArray()));
                $adminChangeOrderForm = new AdminChangeOrderForm(['scenario'=>AdminChangeOrderForm::GET]);
                
                $dataArray = [];
                
                $dataArray['оrdersFiltersWidgetConfig'] = $this->оrdersFiltersWidgetConfig($sortingTypesArray, $statusesArray, $ordersFiltersForm);
                $dataArray['adminOrdersWidgetConfig'] = $this->adminOrdersWidgetConfig($ordersCollection->asArray(), $adminChangeOrderForm, $currentCurrencyModel);
                $dataArray['paginationWidgetConfig'] = $this->paginationWidgetConfig($ordersCollection->pagination);
                $dataArray['adminCsvOrdersFormWidgetConfig'] = $this->adminCsvOrdersFormWidgetConfig();
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminOrdersWidget
     * @param array $ordersArray массив PurchasesModel
     * @patram AbstractBaseForm $purchaseForm
     * @param CurrencyInterface $currentCurrencyModel
     * @return array
     */
    private function adminOrdersWidgetConfig(array $ordersArray, AbstractBaseForm $adminChangeOrderForm, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Orders');
            $dataArray['purchases'] = $ordersArray;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['form'] = $adminChangeOrderForm;
            $dataArray['template'] = 'admin-orders.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminCsvOrdersFormWidget
     * @return array
     */
    private function adminCsvOrdersFormWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Download selected orders in csv format');
            $dataArray['template'] = 'admin-csv-orders-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
