<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetProductsCollectionSphinxService};

/**
 * Возвращает массив данных для ProductsWidget
 */
class GetPaginationWidgetConfigSphinxService extends AbstractBaseService
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
                
                $service = new GetProductsCollectionSphinxService();
                $productsCollection = $service->handle($request);
            
                $pagination = $productsCollection->pagination;
                
                if (empty($pagination)) {
                    throw new ErrorException($this->emptyError('pagination'));
                }
                
                $dataArray['pagination'] = $pagination;
                $dataArray['view'] = 'pagination.twig';
                
                $this->paginationWidgetArray = $dataArray;
            }
            
            return $this->paginationWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
