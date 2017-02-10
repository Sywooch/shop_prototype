<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use app\handlers\{AbstractBaseHandler,
    BaseFrontendHandlerTrait};
use app\models\{CurrencyInterface,
    ProductsModel};
use app\finders\{CommentsProductFinder,
    ProductDetailFinder,
    RelatedFinder,
    SimilarFinder};
use app\forms\{CommentForm,
    PurchaseForm};

/**
 * Обрабатывает запрос на получение данных 
 * для рендеринга страницы каталога товаров
 */
class ProductDetailIndexRequestHandler extends AbstractBaseHandler
{
    use BaseFrontendHandlerTrait;
    
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы каталога товаров
     * @param $request данные запроса
     */
    public function handle($request): array
    {
        try {
            if (empty($this->dataArray)) {
                $seocode = $request->get(\Yii::$app->params['productKey']) ?? null;
                if (empty($seocode)) {
                    throw new ErrorException($this->emptyError('seocode'));
                }
                
                $currentCurrencyModel = $this->getCurrentCurrency();
                
                $finder = \Yii::$app->registry->get(ProductDetailFinder::class, [
                    'seocode'=>$seocode
                ]);
                $productsModel = $finder->find();
                
                if (empty($productsModel)) {
                    throw new NotFoundHttpException($this->error404());
                }
                
                $dataArray = [];
                
                $dataArray['userInfoWidgetConfig'] = $this->userInfoWidgetConfig();
                $dataArray['shortCartWidgetConfig'] = $this->shortCartWidgetConfig($currentCurrencyModel);
                $dataArray['currencyWidgetConfig'] = $this->currencyWidgetConfig($currentCurrencyModel);
                $dataArray['searchWidgetConfig'] = $this->searchWidgetConfig();
                $dataArray['categoriesMenuWidgetConfig'] = $this->categoriesMenuWidgetConfig();
                $dataArray['productDetailWidgetConfig'] = $this->productDetailWidgetConfig($productsModel, $currentCurrencyModel);
                $dataArray['purchaseFormWidgetConfig'] = $this->purchaseFormWidgetConfig($productsModel);
                $dataArray['productBreadcrumbsWidget'] = $this->productBreadcrumbsWidget($productsModel);
                $dataArray['seeAlsoWidgetSimilarConfig'] = $this->seeAlsoWidgetSimilarConfig($productsModel, $currentCurrencyModel);
                $dataArray['seeAlsoWidgetRelatedConfig'] = $this->seeAlsoWidgetRelatedConfig($productsModel, $currentCurrencyModel);
                $dataArray['commentsWidgetConfig'] = $this->commentsWidgetConfig($productsModel);
                $dataArray['сommentFormWidgetConfig'] = $this->сommentFormWidgetConfig($productsModel->id);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ProductDetailWidget
     * @param ProductsModel $productsModel объект товара
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    private function productDetailWidgetConfig(ProductsModel $productsModel, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['product'] = $productsModel;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['template'] = 'product-detail.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета PurchaseFormWidget
     * @param ProductsModel $productsModel объект товара
     * @return array
     */
    private function purchaseFormWidgetConfig(ProductsModel $productsModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['product'] = $productsModel;
            $dataArray['form'] = new PurchaseForm(['scenario'=>PurchaseForm::SAVE, 'quantity'=>1]);
            $dataArray['template'] = 'purchase-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ProductBreadcrumbsWidget
     * @param ProductsModel $productsModel объект товара
     * @return array
     */
    private function productBreadcrumbsWidget(ProductsModel $productsModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['product'] = $productsModel;
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета SeeAlsoWidget (Similar)
     * @param ProductsModel $productsModel объект товара
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    private function seeAlsoWidgetSimilarConfig(ProductsModel $productsModel, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(SimilarFinder::class, [
                'product'=>$productsModel
            ]);
            $similarArray = $finder->find();
            
            $dataArray['products'] = $similarArray;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['header'] = \Yii::t('base', 'Similar products');
            $dataArray['template'] = 'see-also.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета SeeAlsoWidget (Related)
     * @param ProductsModel $productsModel объект товара
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    private function seeAlsoWidgetRelatedConfig(ProductsModel $productsModel, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(RelatedFinder::class, [
                'product'=>$productsModel
            ]);
            $relatedArray = $finder->find();
            
            $dataArray['products'] = $relatedArray;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['header'] = \Yii::t('base', 'Related products');
            $dataArray['template'] = 'see-also.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CommentsWidget
     * @param ProductsModel $productsModel объект товара
     * @return array
     */
    private function commentsWidgetConfig(ProductsModel $productsModel): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(CommentsProductFinder::class, [
                'product'=>$productsModel
            ]);
            $commentsArray = $finder->find();
            
            if (!empty($commentsArray)) {
                ArrayHelper::multisort($commentsArray, 'date', SORT_DESC);
                $dataArray['comments'] = $commentsArray;
            }
            
            $dataArray['template'] = 'comments.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CommentFormWidget
     * @param int $id товара
     * @return array
     */
    private function сommentFormWidgetConfig(int $id): array
    {
        try {
            $dataArray = [];
            
            $dataArray['form'] = new CommentForm([
                'scenario'=>CommentForm::SAVE,
                'id_product'=>$id,
            ]);
            $dataArray['template'] = 'comment-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
