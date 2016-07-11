<?php

namespace app\controllers;

use yii\base\ErrorException;
use app\controllers\AbstractBaseProductsController;
use app\mappers\ProductDetailMapper;
use app\models\CommentsModel;
use app\models\ProductsModel;

/**
 * Обрабатывает запросы на получение информации о конкретном продукте
 */
class ProductDetailController extends AbstractBaseProductsController
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
            if (!is_array($dataForRender = $this->getDataForRender())) {
                throw new ErrorException('Ошибка при формировании массива данных!');
            }
            $resultArray = array_merge(['productsObject'=>$productsObject], $dataForRender);
            return $this->render('product-detail.twig', $resultArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Получает данные, необходимые в нескольких типах контроллеров 
     * @return array
     */
    /*protected function getDataForRender()
    {
        try {
            if (!is_array($result = parent::getDataForRender())) {
                throw new ErrorException('Ошибка при формировании массива данных!');
            }
            $result['commentsModel'] = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
            $result['productsModel'] = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
            return $result;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }*/
}
