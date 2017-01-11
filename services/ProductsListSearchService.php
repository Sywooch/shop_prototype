<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    GetShortCartWidgetConfigService,
    GetCategoriesMenuWidgetConfigService,
    GetCurrencyWidgetConfigService,
    GetEmptyProductsWidgetConfigService,
    GetEmptySphinxWidgetConfigService,
    GetFiltersWidgetConfigSphinxService,
    GetPaginationWidgetConfigSphinxService,
    GetProductsCollectionSphinxService,
    GetProductsWidgetSphinxConfigService,
    GetSearchWidgetConfigService,
    GetSphinxArrayService,
    GetUserInfoWidgetConfigService};

/**
 * Формирует массив данных для рендеринга страницы каталога товаров
 */
class ProductsListSearchService extends AbstractBaseService
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы каталога товаров
     * @param array $request
     */
    public function handle($request): array
    {
        try {
            if (empty($request->get(\Yii::$app->params['searchKey']))) {
                throw new ErrorException($this->emptyError('searchKey'));
            }
            
            if (empty($this->dataArray)) {
                $dataArray = [];
                
                $service = \Yii::$app->registry->get(GetUserInfoWidgetConfigService::class);
                $dataArray['userInfoWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetShortCartWidgetConfigService::class);
                $dataArray['shortCartWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetCurrencyWidgetConfigService::class);
                $dataArray['currencyWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetSearchWidgetConfigService::class);
                $dataArray['searchWidgetConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetCategoriesMenuWidgetConfigService::class);
                $dataArray['categoriesMenuWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetSphinxArrayService::class);
                $sphinxArray = $service->handle($request);
                
                if (empty($sphinxArray)) {
                    $service = \Yii::$app->registry->get(GetEmptySphinxWidgetConfigService::class);
                    $dataArray['emptySphinxWidgetConfig'] = $service->handle();
                } else {
                    $service = \Yii::$app->registry->get(GetProductsCollectionSphinxService::class);
                    $productsCollection = $service->handle($request);
                    
                    if ($productsCollection->isEmpty() === true) {
                        if ($productsCollection->pagination->totalCount > 0) {
                            throw new NotFoundHttpException($this->error404());
                        }
                        
                        $service = \Yii::$app->registry->get(GetEmptyProductsWidgetConfigService::class);
                        $dataArray['emptyProductsWidgetConfig'] = $service->handle();
                    } else {
                        $service = \Yii::$app->registry->get(GetProductsWidgetSphinxConfigService::class);
                        $dataArray['productsWidgetConfig'] = $service->handle($request);
                        
                        $service = \Yii::$app->registry->get(GetPaginationWidgetConfigSphinxService::class);
                        $dataArray['paginationWidgetConfig'] = $service->handle($request);
                    }
                    
                    $service = \Yii::$app->registry->get(GetFiltersWidgetConfigSphinxService::class);
                    $dataArray['filtersWidgetConfig'] = $service->handle($request);
                }
                
                $service = \Yii::$app->registry->get(GetSearchBreadcrumbsWidgetConfigService::class);
                $dataArray['searchBreadcrumbsWidgetConfig'] = $service->handle($request);
                
                $this->dataArray = $dataArray;
            }
                
            return $this->dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
