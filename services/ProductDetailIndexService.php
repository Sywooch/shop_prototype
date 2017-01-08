<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\{NotFoundHttpException,
    Request};
use yii\helpers\ArrayHelper;
use app\services\{AbstractBaseService,
    ChangeCurrencyFormService,
    CommentFormService,
    CommentsSaveService,
    GetCartWidgetConfigService,
    GetCurrentCurrencyModelService,
    GetProductDetailModelService,
    GetProductDetailWidgetConfigService,
    GetCategoriesMenuWidgetConfigService,
    GetSearchWidgetConfigService,
    GetUserInfoWidgetConfigService,
    PurchaseFormService};
use app\finders\{SimilarFinder,
    RelatedFinder};
use app\forms\{CommentForm,
    PurchaseForm};
use app\models\ProductsModel;

/**
 * Формирует массив данных для рендеринга страницы каталога товаров
 */
class ProductDetailIndexService extends AbstractBaseService
{
    /**
     * @var array данные для ProductDetailWidget
     */
    private $productArray = [];
    /**
     * @var array данные для PurchaseFormWidget
     */
    private $purchaseFormArray = [];
    /**
     * @var array данные для ProductBreadcrumbsWidget
     */
    private $breadcrumbsArray = [];
    /**
     * @var array данные для SeeAlsoWidget
     */
    private $similarArray = [];
    /**
     * @var array данные для SeeAlsoWidget
     */
    private $relatedArray = [];
    /**
     * @var array данные для CommentsWidget
     */
    private $commentsArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы каталога товаров
     * @param $request данные запроса
     */
    public function handle($request): array
    {
        try {
            $dataArray = [];
            
            $service = new GetUserInfoWidgetConfigService();
            $dataArray['userConfig'] = $service->handle();
            
            $service = new GetCartWidgetConfigService();
            $dataArray['cartConfig'] = $service->handle();
            
            $service = new ChangeCurrencyFormService();
            $dataArray['currencyConfig'] = $service->handle();
            
            $service = new GetSearchWidgetConfigService();
            $dataArray['searchConfig'] = $service->handle($request);
            
            $service = new GetCategoriesMenuWidgetConfigService();
            $dataArray['menuConfig'] = $service->handle();
            
            $service = new GetProductDetailWidgetConfigService();
            $dataArray['productConfig'] = $service->handle($request);
            
            $dataArray['purchaseFormWidgetConfig'] = $this->getPurchaseFormArray($request);
            $dataArray['breadcrumbsConfig'] = $this->getBreadcrumbsArray($request);
            $dataArray['similarConfig'] = $this->getSimilarArray($request);
            $dataArray['relatedConfig'] = $this->getRelatedArray($request);
            
            $service = new CommentsService();
            $dataArray['commentsConfig'] = $service->handle($request);
            
            $service = new CommentFormService();
            $dataArray['commentFormConfig'] = $service->handle($request);
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ProductDetailWidget
     * @param Request $request данные запроса
     * @return array
     */
    /*private function getProductArray(Request $request): array
    {
        try {
            if (empty($this->productArray)) {
                $dataArray = [];
                
                $service = new GetProductDetailModelService();
                $dataArray['product'] = $service->handle($request);
                
                $service = new GetCurrentCurrencyModelService();
                $dataArray['currency'] = $service->handle();
                
                $dataArray['view'] = 'product-detail.twig';
                
                $this->productArray = $dataArray;
            }
            
            return $this->productArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }*/
    
    /**
     * Возвращает массив конфигурации для виджета PurchaseFormWidget
     * @param Request $request данные запроса
     * @return array
     */
    private function getPurchaseFormArray(Request $request): array
    {
        try {
            if (empty($this->purchaseFormArray)) {
                $service = new PurchaseFormService();
                $this->purchaseFormArray = $service->handle($request);
            }
            
            return $this->purchaseFormArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ProductBreadcrumbsWidget
     * @param Request $request данные запроса
     * @return array
     */
    private function getBreadcrumbsArray(Request $request): array
    {
        try {
            if (empty($this->breadcrumbsArray)) {
                $dataArray = [];
                
                $service = new GetProductDetailModelService();
                $dataArray['product'] = $service->handle($request);
                
                $this->breadcrumbsArray = $dataArray;
            }
            
            return $this->breadcrumbsArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета SeeAlsoWidget, 
     * с информацией о похожих товарах
     * @param Request $request данные запроса
     * @return array
     */
    private function getSimilarArray(Request $request): array
    {
        try {
            if (empty($this->similarArray)) {
                $dataArray = [];
                
                $service = new GetProductDetailModelService();
                $productsModel = $service->handle($request);
                
                $finder = \Yii::$app->registry->get(SimilarFinder::class, ['product'=>$productsModel]);
                $similarArray = $finder->find();
                
                $dataArray['products'] = $similarArray;
                
                $service = new GetCurrentCurrencyModelService();
                $dataArray['currency'] = $service->handle();
                
                $dataArray['header'] = \Yii::t('base', 'Similar products');
                $dataArray['view'] = 'see-also.twig';
                
                $this->similarArray = $dataArray;
            }
            
            return $this->similarArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета SeeAlsoWidget, 
     * с информацией о связанных товарах
     * @param Request $request данные запроса
     * @return array
     */
    private function getRelatedArray(Request $request): array
    {
        try {
            if (empty($this->relatedArray)) {
                $dataArray = [];
                
                $service = new GetProductDetailModelService();
                $productsModel = $service->handle($request);
                
                $finder = \Yii::$app->registry->get(RelatedFinder::class, ['product'=>$productsModel]);
                $relatedArray = $finder->find();
                
                $dataArray['products'] = $relatedArray;
                
                $service = new GetCurrentCurrencyModelService();
                $dataArray['currency'] = $service->handle();
                
                $dataArray['header'] = \Yii::t('base', 'Related products');
                $dataArray['view'] = 'see-also.twig';
                
                $this->relatedArray = $dataArray;
            }
            
            return $this->relatedArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CommentsWidget
     * @param Request $request данные запроса
     * @return array
     */
    private function getCommentsArray(Request $request): array
    {
        try {
            if (empty($this->commentsArray)) {
                $service = new CommentsSaveService();
                $this->commentsArray = $service->handle($request);
            }
            
            return $this->commentsArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
