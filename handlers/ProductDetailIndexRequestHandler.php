<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\{ArrayHelper,
    Url};
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\models\{CurrencyInterface,
    ProductsModel};
use app\finders\{CategoriesFinder,
    CommentsProductFinder,
    CurrencyFinder,
    ProductDetailFinder,
    PurchasesSessionFinder,
    RelatedFinder,
    SimilarFinder};
use app\forms\{AbstractBaseForm,
    CommentForm,
    ChangeCurrencyForm,
    PurchaseForm,
    UserLoginForm};
use app\helpers\HashHelper;
use app\services\GetCurrentCurrencyModelService;
use app\validators\StripTagsValidator;

/**
 * Обрабатывает запрос на получение данных 
 * для рендеринга страницы каталога товаров
 */
class ProductDetailIndexRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
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
                
                $validator = new StripTagsValidator();
                $seocode = $validator->validate($seocode);
                
                $seocode = filter_var($seocode, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>'#^[a-z0-9-]+$#u']]);
                if ($seocode === false) {
                    throw new ErrorException($this->invalidError('seocode'));
                }
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                    'key'=>HashHelper::createCurrencyKey()
                ]);
                $currentCurrencyModel = $service->get();
                if (empty($currentCurrencyModel)) {
                    throw new ErrorException($this->emptyError('currentCurrencyModel'));
                }
                
                $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, [
                    'key'=>HashHelper::createCartKey()
                ]);
                $ordersCollection = $finder->find();
                if (empty($ordersCollection)) {
                    throw new ErrorException($this->emptyError('ordersCollection'));
                }
                
                $finder = \Yii::$app->registry->get(CategoriesFinder::class);
                $categoriesModelArray = $finder->find();
                if (empty($categoriesModelArray)) {
                    throw new ErrorException($this->emptyError('categoriesModelArray'));
                }
                
                $finder = \Yii::$app->registry->get(ProductDetailFinder::class, [
                    'seocode'=>$seocode
                ]);
                $productsModel = $finder->find();
                if (empty($productsModel)) {
                    throw new NotFoundHttpException($this->error404());
                }
                
                $finder = \Yii::$app->registry->get(SimilarFinder::class, [
                    'product'=>$productsModel
                ]);
                $similarArray = $finder->find();
                
                $finder = \Yii::$app->registry->get(RelatedFinder::class, [
                    'product'=>$productsModel
                ]);
                $relatedArray = $finder->find();
                
                $finder = \Yii::$app->registry->get(CommentsProductFinder::class, [
                    'product'=>$productsModel
                ]);
                $commentsArray = $finder->find();
                
                $finder = \Yii::$app->registry->get(CurrencyFinder::class);
                $currencyArray = $finder->find();
                if (empty($currencyArray)) {
                    throw new ErrorException($this->emptyError('currencyArray'));
                }
                
                $purchaseForm = new PurchaseForm(['quantity'=>1]);
                
                $commentForm = new CommentForm(['id_product'=>$productsModel->id]);
                
                $changeCurrencyForm = new ChangeCurrencyForm([
                    'id'=>$currentCurrencyModel->id,
                    'url'=>Url::current()
                ]);
                
                $userLoginForm = new UserLoginForm();
                
                $dataArray = [];
                
                $dataArray['userInfoWidgetConfig'] = $this->userInfoWidgetConfig(\Yii::$app->user, $userLoginForm);
                $dataArray['shortCartWidgetConfig'] = $this->shortCartWidgetConfig($ordersCollection, $currentCurrencyModel);
                $dataArray['currencyWidgetConfig'] = $this->currencyWidgetConfig($currencyArray, $changeCurrencyForm);
                $dataArray['searchWidgetConfig'] = $this->searchWidgetConfig();
                $dataArray['categoriesMenuWidgetConfig'] = $this->categoriesMenuWidgetConfig($categoriesModelArray);
                $dataArray['productDetailWidgetConfig'] = $this->productDetailWidgetConfig($productsModel, $currentCurrencyModel);
                $dataArray['purchaseFormWidgetConfig'] = $this->orderFormWidgetConfig($productsModel, $purchaseForm);
                $dataArray['productBreadcrumbsWidget'] = $this->productBreadcrumbsWidget($productsModel);
                $dataArray['seeAlsoWidgetSimilarConfig'] = $this->seeAlsoWidgetSimilarConfig($similarArray, $currentCurrencyModel);
                $dataArray['seeAlsoWidgetRelatedConfig'] = $this->seeAlsoWidgetRelatedConfig($relatedArray, $currentCurrencyModel);
                $dataArray['commentsWidgetConfig'] = $this->commentsWidgetConfig($commentsArray);
                $dataArray['сommentFormWidgetConfig'] = $this->сommentFormWidgetConfig($commentForm);
                
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
     * @param AbstractBaseForm $purchaseForm
     * @return array
     */
    private function orderFormWidgetConfig(ProductsModel $productsModel, AbstractBaseForm $purchaseForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['product'] = $productsModel;
            $dataArray['form'] = $purchaseForm;
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
     * @param array $similarArray массив похожих товаров
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    private function seeAlsoWidgetSimilarConfig(array $similarArray, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
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
     * @param array $relatedArray массив связанных товаров
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    private function seeAlsoWidgetRelatedConfig(array $relatedArray, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
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
     * @param array $commentsArray массив комментариев
     * @return array
     */
    private function commentsWidgetConfig(array $commentsArray): array
    {
        try {
            $dataArray = [];
            
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
     * @param AbstractBaseForm $commentForm
     * @return array
     */
    private function сommentFormWidgetConfig(AbstractBaseForm $commentForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['form'] = $commentForm;
            $dataArray['template'] = 'comment-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
