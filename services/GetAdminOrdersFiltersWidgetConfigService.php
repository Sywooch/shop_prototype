<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\services\AbstractBaseService;
use app\forms\AdminOrdersFiltersForm;
use app\finders\{AdminOrdersFiltersSessionFinder,
    OrderStatusesFinder,
    SortingTypesFinder};
use app\helpers\HashHelper;

/**
 * Возвращает массив конфигурации для виджета AdminOrdersFiltersWidget
 */
class GetAdminOrdersFiltersWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AdminOrdersFiltersWidget
     */
    private $adminOrdersFiltersWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета AdminOrdersFiltersWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->adminOrdersFiltersWidgetArray)) {
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
                
                $finder = \Yii::$app->registry->get(AdminOrdersFiltersSessionFinder::class, ['key'=>HashHelper::createHash([\Yii::$app->params['adminOrdersFilters']])]);
                $filtersModel = $finder->find();
                
                $form = new AdminOrdersFiltersForm(array_filter($filtersModel->toArray()));
                
                if (empty($form->sortingType)) {
                    foreach ($sortingTypesArray as $item) {
                        if ($item['name'] === \Yii::$app->params['sortingType']) {
                            $form->sortingType = $item;
                        }
                    }
                }
                
                $dataArray['form'] = $form;
                
                $dataArray['header'] = \Yii::t('base', 'Filters');
                
                $dataArray['template'] = 'admin-orders-filters.twig';
                
                $this->adminOrdersFiltersWidgetArray = $dataArray;
            }
            
            return $this->adminOrdersFiltersWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
