<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    GetCartWidgetConfigService,
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
            
            $dataArray = [];
            
            $service = new GetUserInfoWidgetConfigService();
            $dataArray['userInfoWidgetConfig'] = $service->handle();
            
            $service = new GetCartWidgetConfigService();
            $dataArray['cartWidgetConfig'] = $service->handle();
            
            $service = new GetCurrencyWidgetConfigService();
            $dataArray['currencyWidgetConfig'] = $service->handle();
            
            $service = new GetSearchWidgetConfigService();
            $dataArray['searchWidgetConfig'] = $service->handle($request);
            
            $service = new GetCategoriesMenuWidgetConfigService();
            $dataArray['categoriesMenuWidgetConfig'] = $service->handle();
            
            $service = new GetSphinxArrayService();
            $sphinxArray = $service->handle($request);
            
            if (empty($sphinxArray)) {
                $service = new GetEmptySphinxWidgetConfigService();
                $dataArray['emptySphinxWidgetConfig'] = $service->handle();
            } else {
                $service = new GetProductsCollectionSphinxService();
                $productsCollection = $service->handle($request);
                
                if ($productsCollection->isEmpty() === true) {
                    if ($productsCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                    
                    $service = new GetEmptyProductsWidgetConfigService();
                    $dataArray['emptyProductsWidgetConfig'] = $service->handle();
                } else {
                    $service = new GetProductsWidgetSphinxConfigService();
                    $dataArray['productsWidgetConfig'] = $service->handle($request);
                    
                    $service = new GetPaginationWidgetConfigSphinxService();
                    $dataArray['paginationWidgetConfig'] = $service->handle($request);
                }
                
                $service = new GetFiltersWidgetConfigSphinxService();
                $dataArray['filtersWidgetConfig'] = $service->handle($request);
            }
            
            $service = new GetSearchBreadcrumbsWidgetConfigService();
            $dataArray['searchBreadcrumbsWidgetConfig'] = $service->handle($request);
                
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
