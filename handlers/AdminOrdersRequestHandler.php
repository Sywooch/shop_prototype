<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use app\handlers\AbstractBaseHandler;
use app\services\GetCurrentCurrencyModelService;
use app\finders\{AdminOrdersFinder,
    OrdersFiltersSessionFinder,
    OrderStatusesFinder,
    SortingTypesFinder};
use app\forms\{AdminChangeOrderForm,
    OrdersFiltersForm};
use app\helpers\{DateHelper,
    HashHelper};
use app\filters\OrdersFilters;
use app\collections\{PaginationInterface,
    PurchasesCollectionInterface};

/**
 * Обрабатывает запрос на получение данных 
 * с перечнем заказов
 */
class AdminOrdersRequestHandler extends AbstractBaseHandler
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
            if (empty($this->dataArray)) {
                $finder = \Yii::$app->registry->get(OrdersFiltersSessionFinder::class, [
                    'key'=>HashHelper::createHash([\Yii::$app->params['ordersFilters']])
                ]);
                $filtersModel = $finder->find();
                
                $finder = \Yii::$app->registry->get(AdminOrdersFinder::class, [
                    'page'=>$request->get(\Yii::$app->params['pagePointer']) ?? 0,
                    'filters'=>$filtersModel
                ]);
                $ordersCollection = $finder->find();
                
                $dataArray = [];
                
                $dataArray['оrdersFiltersWidgetConfig'] = $this->оrdersFiltersWidgetConfig($filtersModel);
                $dataArray['adminOrdersWidgetConfig'] = $this->adminOrdersWidgetConfig($ordersCollection);
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
     * Возвращает массив конфигурации для виджета OrdersFiltersWidget
     * @param OrdersFilters $filtersModel
     * @return array
     */
    private function оrdersFiltersWidgetConfig(OrdersFilters $filtersModel): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(SortingTypesFinder::class);
            $sortingTypesArray = $finder->find();
            if (empty($sortingTypesArray)) {
                throw new ErrorException($this->emptyError('sortingTypesArray'));
            }
            asort($sortingTypesArray, SORT_STRING);
            $dataArray['sortingTypes'] = $sortingTypesArray;
            
            $finder = \Yii::$app->registry->get(OrderStatusesFinder::class);
            $statusesArray = $finder->find();
            asort($statusesArray,SORT_STRING);
            array_unshift($statusesArray, \Yii::$app->params['formFiller']);
            $dataArray['statuses'] = $statusesArray;
            
            $form = new OrdersFiltersForm(array_filter($filtersModel->toArray()));
            
            if (empty($form->sortingType)) {
                foreach ($sortingTypesArray as $key=>$val) {
                    if ($key === \Yii::$app->params['sortingType']) {
                        $form->sortingType = $key;
                    }
                }
            }
            
            if (empty($form->dateFrom)) {
                $form->dateFrom = DateHelper::getToday00();
            }
            if (empty($form->dateTo)) {
                $form->dateTo = DateHelper::getToday00();
            }
            
            $form->url = Url::current();
            
            $dataArray['form'] = $form;
            $dataArray['header'] = \Yii::t('base', 'Filters');
            $dataArray['template'] = 'orders-filters.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminOrdersWidget
     * @param PurchasesCollectionInterface $ordersCollection
     * @return array
     */
    private function adminOrdersWidgetConfig(PurchasesCollectionInterface $ordersCollection): array
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Orders');
            
            if ($ordersCollection->isEmpty() === true) {
                if ($ordersCollection->pagination->totalCount > 0) {
                    throw new NotFoundHttpException($this->error404());
                }
            }
            
            $dataArray['purchases'] = $ordersCollection->asArray();
            
            $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                'key'=>HashHelper::createCurrencyKey()
            ]);
            $dataArray['currency'] = $service->get();
            
            $dataArray['form'] = new AdminChangeOrderForm(['scenario'=>AdminChangeOrderForm::GET]);
            $dataArray['template'] = 'admin-orders.twig';
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета PaginationWidget
     * @param PaginationInterface $filtersModel
     * @return array
     */
    private function paginationWidgetConfig(PaginationInterface $ordersPagination): array
    {
        try {
            $dataArray = [];
            
            $dataArray['pagination'] = $ordersPagination;
            $dataArray['template'] = 'pagination.twig';
            
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
