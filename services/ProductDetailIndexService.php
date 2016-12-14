<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\CommonFrontendService;
use app\finders\ProductDetailFinder;
use app\widgets\{ImagesWidget,
    PriceWidget};
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
            
            $finder = new ProductDetailFinder();
            $finder->load($request);
            $productModel = $finder->find();
            if (empty($productModel)) {
                throw new NotFoundHttpException($this->error404());
            }
            $dataArray['productConfig']['model'] = $productModel;
            
            $dataArray['productConfig']['priceWidget'] = new PriceWidget(['model'=>$dataArray['currencyModel']]);
            $dataArray['productConfig']['imagesWidget'] = new ImagesWidget(['view'=>'images.twig']);
            $dataArray['productConfig']['view'] = 'product-detail.twig';
            
            # Данные для вывода breadcrumbs
            
            $dataArray['breadcrumbsConfig']['model'] = $productModel;
            
            # Данные для вывода формы заказа
            
            $dataArray['toCartConfig']['model'] = $productModel;
            $dataArray['toCartConfig']['form'] = new PurchaseForm(['quantity'=>1]);
            $dataArray['toCartConfig']['view'] = 'add-to-cart-form.twig';
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
