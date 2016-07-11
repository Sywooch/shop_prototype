<?php

namespace app\controllers;

use yii\base\ErrorException;
use app\controllers\AbstractBaseController;
use app\helpers\MappersHelper;
use app\helpers\ModelsInstancesHelper;
use app\mappers\ProductDetailMapper;
use app\models\ProductsModel;

/**
 * Обрабатывает запросы на получение информации о конкретном продукте
 */
class ProductDetailController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос к конкретному продукту, рендерит ответ
     * @return string
     */
    public function actionIndex()
    {
        try {
            $productMapper = new ProductDetailMapper([
                'tableName'=>'products',
                'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
            ]);
            $productsObject = $productMapper->getOneFromGroup();
            if (!is_object($productsObject) || !$productsObject instanceof ProductsModel) {
                throw new ErrorException('Ошибка при получении данных для рендеринга!');
            }
            $renderArray = array();
            $renderArray['productsObject'] = $productsObject;
            $renderArray['categoriesList'] = MappersHelper::getCategoriesList();
            $renderArray['currencyList'] = MappersHelper::getСurrencyList();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('product-detail.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
