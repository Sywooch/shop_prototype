<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\services\AbstractBaseService;
use app\forms\OrdersFiltersForm;
use app\finders\{OrdersFiltersSessionFinder,
    OrderStatusesFinder,
    SortingTypesFinder};
use app\helpers\{DateHelper,
    HashHelper};

/**
 * Возвращает массив конфигурации для виджета OrdersFiltersWidget
 */
class GetOrdersFiltersWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета OrdersFiltersWidget
     */
    private $ordersFiltersWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета OrdersFiltersWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->ordersFiltersWidgetArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(SortingTypesFinder::class);
                $sortingTypesArray = $finder->find();
                if (empty($sortingTypesArray)) {
                    throw new ErrorException($this->emptyError('sortingTypesArray'));
                }
                ArrayHelper::multisort($sortingTypesArray, 'value');
                $dataArray['sortingTypes'] = ArrayHelper::map($sortingTypesArray, 'name', 'value');
                
                $finder = \Yii::$app->registry->get(OrderStatusesFinder::class);
                $statusesArray = $finder->find();
                asort($statusesArray,SORT_STRING);
                array_unshift($statusesArray, \Yii::t('base', 'All'));
                $dataArray['statuses'] = $statusesArray;
                
                $finder = \Yii::$app->registry->get(OrdersFiltersSessionFinder::class, ['key'=>HashHelper::createHash([\Yii::$app->params['ordersFilters']])]);
                $filtersModel = $finder->find();
                
                $form = new OrdersFiltersForm(array_filter($filtersModel->toArray()));
                
                if (empty($form->sortingType)) {
                    foreach ($sortingTypesArray as $item) {
                        if ($item['name'] === \Yii::$app->params['sortingType']) {
                            $form->sortingType = $item;
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
                
                $this->ordersFiltersWidgetArray = $dataArray;
            }
            
            return $this->ordersFiltersWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
