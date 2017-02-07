<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    AdminProductsCollectionService,
    GetCurrentCurrencyModelService};
use app\forms\AdminProductForm;

/**
 * Возвращает массив конфигурации для виджета AdminProductsWidget
 */
class GetAdminProductsWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AdminProductsWidget
     */
    private $adminProductsWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @return array
     */
    public function get(): array
    {
        try {
            if (empty($this->adminProductsWidgetArray)) {
                $dataArray = [];
                
                $dataArray['header'] = \Yii::t('base', 'Products');
                
                $service = \Yii::$app->registry->get(AdminProductsCollectionService::class);
                $productsCollection = $service->get();
                
                if ($productsCollection->isEmpty() === true) {
                    if ($productsCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                }
                
                $dataArray['products'] = $productsCollection->asArray();
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->get();
                
                $dataArray['form'] = new AdminProductForm(['scenario'=>AdminProductForm::GET]);
                $dataArray['template'] = 'admin-products.twig';
                
                $this->adminProductsWidgetArray = $dataArray;
            }
            
            return $this->adminProductsWidgetArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
