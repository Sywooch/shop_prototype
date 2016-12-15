<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\CommonFrontendService;
use app\finders\{ProductDetailFinder,
    SimilarFinder};
use app\forms\PurchaseForm;

/**
 * Формирует массив данных для рендеринга страницы каталога товаров
 */
class ProductDetailIndexService extends CommonFrontendService
{
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы каталога товаров
     * @param array $request
     */
    public function handle($request): array
    {
        try {
            # Общие для всех frontend сервисов данные
            
            $dataArray = parent::handle($request);
            
            # Данные товара
            
            $finder = new ProductDetailFinder([
                'seocode'=>$request[\Yii::$app->params['productKey']]
            ]);
            $productModel = $finder->find();
            if (empty($productModel)) {
                throw new NotFoundHttpException($this->error404());
            }
            $dataArray['productConfig']['product'] = $productModel;
            
            $dataArray['productConfig']['currency'] = $dataArray['currencyModel'];
            $dataArray['productConfig']['view'] = 'product-detail.twig';
            
            # Данные для вывода breadcrumbs
            
            $dataArray['breadcrumbsConfig']['product'] = $productModel;
            
            # Данные для вывода формы заказа
            
            $dataArray['toCartConfig']['product'] = $productModel;
            $dataArray['toCartConfig']['form'] = new PurchaseForm(['quantity'=>1]);
            $dataArray['toCartConfig']['view'] = 'add-to-cart-form.twig';
            
            # Похожие товары
            
            $finder = new SimilarFinder([
                'product'=>$productModel
            ]);
            $similarArray = $finder->find();
            $dataArray['similarConfig']['products'] = $similarArray;
            $dataArray['similarConfig']['currency'] = $dataArray['currencyModel'];
            $dataArray['similarConfig']['view'] = 'see-also.twig';
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
