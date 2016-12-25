<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use app\services\{AbstractBaseService,
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
     * @var ProductsModel текущий товар
     */
    private $productsModel = null;
    /**
     * @var array данные товара
     */
    private $productArray = [];
    /**
     * @var array данные формы заказа
     */
    private $purchaseFormArray = [];
    /**
     * @var array данные breadcrumbs
     */
    private $breadcrumbsArray = [];
    /**
     * @var array данные похожих товаров
     */
    private $similarArray = [];
    /**
     * @var array данные связанных товаров
     */
    private $relatedArray = [];
    /**
     * @var array данные комментариев
     */
    private $commentsArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы каталога товаров
     * @param array $request
     */
    public function handle($request): array
    {
        try {
            $dataArray = [];
            
            $dataArray = array_merge($dataArray, $this->getUserArray());
            $dataArray = array_merge($dataArray, $this->getCartArray());
            $dataArray = array_merge($dataArray, $this->getCurrencyArray());
            $dataArray = array_merge($dataArray, $this->getSearchArray());
            $dataArray = array_merge($dataArray, $this->getCategoriesArray());
            
            $dataArray = array_merge($dataArray, $this->getProductArray($request));
            $dataArray = array_merge($dataArray, $this->getPurchaseFormArray($request));
            $dataArray = array_merge($dataArray, $this->getBreadcrumbsArray($request));
            $dataArray = array_merge($dataArray, $this->getSimilarArray($request));
            $dataArray = array_merge($dataArray, $this->getRelatedArray($request));
            $dataArray = array_merge($dataArray, $this->getCommentsArray($request));
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные выбранного товара
     * @param array $request массив данных запроса
     * @return ProductsModel
     */
    private function getProductsModel(array $request): ProductsModel
    {
        try {
            if (empty($this->productsModel)) {
                $finder = new ProductDetailFinder([
                    'seocode'=>$request[\Yii::$app->params['productKey']]
                ]);
                $productsModel = $finder->find();
                if (empty($productsModel)) {
                    throw new NotFoundHttpException($this->error404());
                }
                
                $this->productsModel = $productsModel;
            }
            
            return $this->productsModel;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные для вывода информации о товаре
     * @param array $request массив данных запроса
     * @return array
     */
    private function getProductArray(array $request): array
    {
        try {
            if (empty($this->productArray)) {
                $dataArray = [];
                
                $dataArray['productConfig']['product'] = $this->getProductsModel($request);
                $dataArray['productConfig']['currency'] = $this->getCurrencyModel();
                $dataArray['productConfig']['view'] = 'product-detail.twig';
                
                $this->productArray = $dataArray;
            }
            
            return $this->productArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные для вывода формы заказа
     * @param array $request массив данных запроса
     * @return array
     */
    private function getPurchaseFormArray(array $request): array
    {
        try {
            if (empty($this->purchaseFormArray)) {
                $dataArray = [];
                
                $dataArray['toCartConfig']['product'] = $this->getProductsModel($request);
                $dataArray['toCartConfig']['form'] = new PurchaseForm(['quantity'=>1]);
                $dataArray['toCartConfig']['view'] = 'add-to-cart-form.twig';
                
                $this->purchaseFormArray = $dataArray;
            }
            
            return $this->purchaseFormArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные для вывода breadcrumbs
     * @param array $request массив данных запроса
     * @return array
     */
    private function getBreadcrumbsArray(array $request): array
    {
        try {
            if (empty($this->breadcrumbsArray)) {
                $dataArray = [];
                
                $dataArray['breadcrumbsConfig']['product'] = $this->getProductsModel($request);
                
                $this->breadcrumbsArray = $dataArray;
            }
            
            return $this->breadcrumbsArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные для вывода похожих товаров
     * @param array $request массив данных запроса
     * @return array
     */
    private function getSimilarArray(array $request): array
    {
        try {
            if (empty($this->similarArray)) {
                $dataArray = [];
                
                $finder = new SimilarFinder([
                    'product'=>$this->getProductsModel($request)
                ]);
                $similarArray = $finder->find();
                
                $dataArray['similarConfig']['products'] = $similarArray;
                $dataArray['similarConfig']['currency'] = $this->getCurrencyModel();
                $dataArray['similarConfig']['header'] = \Yii::t('base', 'Similar products');
                $dataArray['similarConfig']['view'] = 'see-also.twig';
                
                $this->similarArray = $dataArray;
            }
            
            return $this->similarArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные для вывода связанных товаров
     * @param array $request массив данных запроса
     * @return array
     */
    private function getRelatedArray(array $request): array
    {
        try {
            if (empty($this->relatedArray)) {
                $dataArray = [];
                
                $finder = new RelatedFinder([
                    'product'=>$this->getProductsModel($request)
                ]);
                $relatedArray = $finder->find();
                
                $dataArray['relatedConfig']['products'] = $relatedArray;
                $dataArray['relatedConfig']['currency'] = $this->getCurrencyModel();
                $dataArray['relatedConfig']['header'] = \Yii::t('base', 'Related products');
                $dataArray['relatedConfig']['view'] = 'see-also.twig';
                
                $this->relatedArray = $dataArray;
            }
            
            return $this->relatedArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные для вывода комментариев
     * @param array $request массив данных запроса
     * @return array
     */
    private function getCommentsArray(array $request): array
    {
        try {
            if (empty($this->commentsArray)) {
                $dataArray = [];
                
                $productsModel = $this->getProductsModel($request);
                
                $finder = new CommentsProductFinder([
                    'product'=>$productsModel
                ]);
                $commentsArray = $finder->find();
                
                ArrayHelper::multisort($commentsArray, 'date', SORT_DESC);
                $dataArray['commentsConfig']['comments'] = $commentsArray;
                $dataArray['commentsConfig']['form'] = new CommentForm(['id_product'=>$productsModel->id]);
                $dataArray['commentsConfig']['view'] = 'comments.twig';
                
                $this->commentsArray = $dataArray;
            }
            
            return $this->commentsArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
