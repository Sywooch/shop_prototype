<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use yii\web\NotFoundHttpException;
use app\services\{CommonFrontendService,
    ServiceInterface};
use app\exceptions\ExceptionsTrait;
use app\finders\ProductDetailFinder;
use app\collections\BaseCollection;
use app\widgets\{ImagesWidget,
    PriceWidget};
use app\forms\PurchaseForm;

/**
 * Формирует массив данных для рендеринга страницы каталога товаров
 */
class ProductDetailIndexService extends Object implements ServiceInterface
{
    use ExceptionsTrait;
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы каталога товаров
     * @param array $request
     */
    public function handle($request): array
    {
        try {
            # Общие для всех frontend сервисов данные
            
            $common = new CommonFrontendService();
            $dataArray = $common->handle($request);
            
            # Данные для вывода данных о товаре
            
            $finder = new ProductDetailFinder([
                'collection'=>new BaseCollection(),
            ]);
            $finder->load($request);
            $model = $finder->find()->getModel();
            if (empty($model)) {
                throw new NotFoundHttpException($this->error404());
            }
            
            $dataArray['productConfig']['model'] = $model;
            $dataArray['productConfig']['priceWidget'] = new PriceWidget(['currencyModel'=>$dataArray['currencyModel']]);
            $dataArray['productConfig']['imagesWidget'] = new ImagesWidget(['view'=>'images.twig']);
            $dataArray['productConfig']['view'] = 'product-detail.twig';
            
            # Данные для вывода breadcrumbs
            
            $dataArray['breadcrumbsConfig']['model'] = $model;
            
            # Данные для вывода формы заказа
            
            $dataArray['toCartConfig']['model'] = $model;
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
