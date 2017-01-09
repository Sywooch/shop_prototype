<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\AbstractBaseService;
use app\finders\ProductDetailFinder;
use app\models\ProductsModel;

/**
 * Возвращает объект ProductsModel
 */
class GetProductDetailModelService extends AbstractBaseService
{
    /**
     * @var ProductsModel
     */
    private $productsModel = null;
    
    /**
     * Возвращает ProductsModel
     * @param $request
     * @return ProductsModel
     */
    public function handle($request): ProductsModel
    {
        try {
            $seocode = $request->get(\Yii::$app->params['productKey']);
            
            if (empty($seocode)) {
                throw new ErrorException($this->emptyError('seocode'));
            }
            
            if (empty($this->productsModel)) {
                $finder = \Yii::$app->registry->get(ProductDetailFinder::class, ['seocode'=>$seocode]);
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
}
