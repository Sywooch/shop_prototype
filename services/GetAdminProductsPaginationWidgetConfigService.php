<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    AdminProductsCollectionService};

/**
 * Возвращает массив данных для PaginationWidget
 */
class GetAdminProductsPaginationWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array данные для PaginationWidget
     */
    private $paginationWidgetArray = [];
    
    /**
     * Возвращает массив данных для PaginationWidget
     * @param array $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            if (empty($this->paginationWidgetArray)) {
                $dataArray = [];
                
                $service = \Yii::$app->registry->get(AdminProductsCollectionService::class);
                $purchasesCollection = $service->handle($request);
            
                $pagination = $purchasesCollection->pagination;
                
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
