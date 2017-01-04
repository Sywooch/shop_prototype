<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\{NotFoundHttpException,
    Request};
use yii\helpers\ArrayHelper;
use app\services\{AbstractBaseService,
    CommentsSaveService,
    FrontendTrait};
use app\finders\{CommentsProductFinder,
    ProductDetailFinder,
    SimilarFinder,
    RelatedFinder};
use app\forms\{CommentForm,
    PurchaseForm};
use app\models\ProductsModel;

/**
 * Формирует массив данных для рендеринга страницы каталога товаров
 */
class ProductDetailIndexService extends AbstractBaseService
{
    use FrontendTrait;
    
    /**
     * @var array данные для ProductDetailWidget
     */
    private $productArray = [];
    /**
     * @var array данные для ToCartWidget
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
     * @param Request $request данные запроса
     */
    public function handle(Request $request): array
    {
        try {
            $dataArray = [];
            
            $dataArray['userConfig'] = $this->getUserArray();
            $dataArray['cartConfig'] = $this->getCartArray();
            $dataArray['currencyConfig'] = $this->getCurrencyArray();
            $dataArray['searchConfig'] = $this->getSearchArray();
            $dataArray['menuConfig'] = $this->getCategoriesArray();
            
            $dataArray['productConfig'] = $this->getProductArray($request);
            $dataArray['toCartConfig'] = $this->getPurchaseFormArray($request);
            $dataArray['breadcrumbsConfig'] = $this->getBreadcrumbsArray($request);
            $dataArray['similarConfig'] = $this->getSimilarArray($request);
            $dataArray['relatedConfig'] = $this->getRelatedArray($request);
            $dataArray['commentsConfig'] = $this->getCommentsArray($request);
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ProductDetailWidget
     * @param array $request массив данных запроса
     * @return array
     */
    private function getProductArray($request): array
    {
        try {
            if (empty($this->productArray)) {
                $dataArray = [];
                
                $dataArray['product'] = $this->getProductsModel($request);
                $dataArray['currency'] = $this->getCurrencyModel();
                $dataArray['view'] = 'product-detail.twig';
                
                $this->productArray = $dataArray;
            }
            
            return $this->productArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ToCartWidget
     * @param array $request массив данных запроса
     * @return array
     */
    private function getPurchaseFormArray($request): array
    {
        try {
            if (empty($this->purchaseFormArray)) {
                $dataArray = [];
                
                $dataArray['product'] = $this->getProductsModel($request);
                $dataArray['form'] = new PurchaseForm(['quantity'=>1]);
                $dataArray['view'] = 'add-to-cart-form.twig';
                
                $this->purchaseFormArray = $dataArray;
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
     * @param array $request массив данных запроса
     * @return array
     */
    private function getBreadcrumbsArray($request): array
    {
        try {
            if (empty($this->breadcrumbsArray)) {
                $dataArray = [];
                
                $dataArray['product'] = $this->getProductsModel($request);
                
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
     * @param array $request массив данных запроса
     * @return array
     */
    private function getSimilarArray($request): array
    {
        try {
            if (empty($this->similarArray)) {
                $dataArray = [];
                
                $finder = new SimilarFinder([
                    'product'=>$this->getProductsModel($request)
                ]);
                $similarArray = $finder->find();
                
                $dataArray['products'] = $similarArray;
                $dataArray['currency'] = $this->getCurrencyModel();
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
     * @param array $request массив данных запроса
     * @return array
     */
    private function getRelatedArray($request): array
    {
        try {
            if (empty($this->relatedArray)) {
                $dataArray = [];
                
                $finder = new RelatedFinder([
                    'product'=>$this->getProductsModel($request)
                ]);
                $relatedArray = $finder->find();
                
                $dataArray['products'] = $relatedArray;
                $dataArray['currency'] = $this->getCurrencyModel();
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
     * @param array $request массив данных запроса
     * @return array
     */
    private function getCommentsArray($request): array
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
