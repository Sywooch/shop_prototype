<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetProductsCollectionService};

/**
 * Возвращает массив данных для ProductsWidget
 */
class GetPaginationWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array данные для ProductsWidget
     */
    private $paginationWidgetArray = [];
    
    /**
     * Возвращает массив данных для ProductsWidget
     * @param array $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            if (empty($this->paginationWidgetArray)) {
                $dataArray = [];
                
                $service = \Yii::$app->registry->get(GetProductsCollectionService::class);
                $productsCollection = $service->handle($request);
            
                $pagination = $productsCollection->pagination;
                
                if (empty($pagination)) {
                    throw new ErrorException($this->emptyError('pagination'));
                }
                
                $dataArray['pagination'] = $pagination;
                $dataArray['template'] = 'pagination.twig';
                
                $this->paginationWidgetArray = $dataArray;
            }
            
            return $this->paginationWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
