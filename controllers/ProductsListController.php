<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\db\ActiveQuery;
use yii\helpers\{ArrayHelper,
    Url};
use yii\sphinx\{MatchExpression,
    Query};
use yii\data\Pagination;
use app\helpers\InstancesHelper;
use app\models\{BrandsModel,
    ColorsModel,
    ProductsModel,
    SizesModel};
use app\controllers\AbstractBaseController;

/**
 * Обрабатывает запросы на получение списка продуктов
 */
class ProductsListController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос к списку продуктов
     * @return string
     */
    public function actionIndex()
    {
        try {
            $renderArray = InstancesHelper::getInstances();
            if (!is_array($renderArray) || empty($renderArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray']));
            }
            
            $productsQuery = $this->productsListQuery();
            if (!$productsQuery instanceof ActiveQuery) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'ActiveQuery $productsQuery']));
            }
            
            $renderArray['paginator'] = $productsQuery->paginator;
            if (!$renderArray['paginator'] instanceof Pagination) {
                if (YII_ENV_DEV) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'Pagination']));
                } else {
                    $renderArray['paginator'] = null;
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'Pagination']), __METHOD__);
                }
            }
            
            $renderArray['productsList'] = $productsQuery->all();
            if (!is_array($renderArray['productsList']) || (!empty($renderArray['productsList']) && !$renderArray['productsList'][0] instanceof ProductsModel)) {
                if (YII_ENV_DEV) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'productsList\']']));
                } else {
                    $renderArray['productsList'] = null;
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'productsList\']']), __METHOD__);
                }
            }
            
            $colorsQuery = ColorsModel::find();
            $colorsQuery->extendSelect(['id', 'color']);
            $colorsQuery->distinct();
            $colorsQuery->innerJoin('{{products_colors}}', '[[colors.id]]=[[products_colors.id_color]]');
            $colorsQuery->innerJoin('{{products}}', '[[products_colors.id_product]]=[[products.id]]');
            $colorsQuery->where(['[[products.active]]'=>true]);
            if (\Yii::$app->request->get(\Yii::$app->params['categoryKey'])) {
                $colorsQuery->innerJoin('{{categories}}', '[[products.id_category]]=[[categories.id]]');
                $colorsQuery->andWhere(['[[categories.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
                if (\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])) {
                    $colorsQuery->innerJoin('{{subcategory}}', '[[products.id_subcategory]]=[[subcategory.id]]');
                    $colorsQuery->andWhere(['[[subcategory.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
                }
            }
            $colorsQuery->orderBy(['[[colors.color]]'=>SORT_ASC]);
            $renderArray['colorsList'] = $colorsQuery->allMap('id', 'color');
            if (!is_array($renderArray['colorsList']) || empty($renderArray['colorsList'])) {
                if (YII_ENV_DEV) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'colorsList\']']));
                } else {
                    $renderArray['colorsList'] = null;
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'colorsList\']']), __METHOD__);
                }
            }
            
            $sizesQuery = SizesModel::find();
            $sizesQuery->extendSelect(['id', 'size']);
            $sizesQuery->distinct();
            $sizesQuery->innerJoin('{{products_sizes}}', '[[sizes.id]]=[[products_sizes.id_size]]');
            $sizesQuery->innerJoin('{{products}}', '[[products_sizes.id_product]]=[[products.id]]');
            $sizesQuery->where(['[[products.active]]'=>true]);
            if (\Yii::$app->request->get(\Yii::$app->params['categoryKey'])) {
                $sizesQuery->innerJoin('{{categories}}', '[[products.id_category]]=[[categories.id]]');
                $sizesQuery->andWhere(['[[categories.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
                if (\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])) {
                    $sizesQuery->innerJoin('{{subcategory}}', '[[products.id_subcategory]]=[[subcategory.id]]');
                    $sizesQuery->andWhere(['[[subcategory.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
                }
            }
            $sizesQuery->orderBy(['[[sizes.size]]'=>SORT_ASC]);
            $renderArray['sizesList'] = $sizesQuery->allMap('id', 'size');
            if (!is_array($renderArray['sizesList']) || empty($renderArray['sizesList'])) {
                if (YII_ENV_DEV) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'sizesList\']']));
                } else {
                    $renderArray['sizesList'] = null;
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'sizesList\']']), __METHOD__);
                }
            }
            
            $brandsQuery = BrandsModel::find();
            $brandsQuery->extendSelect(['id', 'brand']);
            $brandsQuery->distinct();
            $brandsQuery->innerJoin('{{products_brands}}', '[[brands.id]]=[[products_brands.id_brand]]');
            $brandsQuery->innerJoin('{{products}}', '[[products_brands.id_product]]=[[products.id]]');
            $brandsQuery->where(['[[products.active]]'=>true]);
            if (\Yii::$app->request->get(\Yii::$app->params['categoryKey'])) {
                $brandsQuery->innerJoin('{{categories}}', '[[products.id_category]]=[[categories.id]]');
                $brandsQuery->andWhere(['[[categories.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
                if (\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])) {
                    $brandsQuery->innerJoin('{{subcategory}}', '[[products.id_subcategory]]=[[subcategory.id]]');
                    $brandsQuery->andWhere(['[[subcategory.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
                }
            }
            $brandsQuery->orderBy(['[[brands.brand]]'=>SORT_ASC]);
            $renderArray['brandsList'] = $brandsQuery->allMap('id', 'brand');
            if (!is_array($renderArray['brandsList']) || empty($renderArray['brandsList'])) {
                if (YII_ENV_DEV) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'brandsList\']']));
                } else {
                    $renderArray['brandsList'] = null;
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'brandsList\']']), __METHOD__);
                }
            }
            
            $renderArray['sortingFieldList'] = ['date'=>\Yii::t('base', 'Sorting by date'), 'price'=>\Yii::t('base', 'Sorting by price')];
            $renderArray['sortingTypeList'] = ['SORT_DESC'=>\Yii::t('base', 'Sort descending'), 'SORT_ASC'=>\Yii::t('base', 'Sort ascending')];
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')];
            
            Url::remember();
            
            return $this->render('products-list.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает поисковый запрос к списку продуктов
     * @return string
     */
    public function actionSearch()
    {
        try {
            if (empty(\Yii::$app->request->get(\Yii::$app->params['searchKey']))) {
                return $this->redirect(Url::to(['/products-list/index']));
            }
            
            $renderArray = InstancesHelper::getInstances();
            if (!is_array($renderArray) || empty($renderArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray']));
            }
            
            $sphinxQuery = new Query();
            $sphinxQuery->select(['id']);
            $sphinxQuery->from('{{shop}}');
            $sphinxQuery->match(new MatchExpression('[[@* :search]]', ['search'=>\Yii::$app->request->get(\Yii::$app->params['searchKey'])]));
            $sphinxArray = $sphinxQuery->all();
            if (!is_array($sphinxArray)) {
                if (YII_ENV_DEV) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $sphinxArray']));
                } else {
                    $sphinxArray = [];
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $sphinxArray']), __METHOD__);
                }
            }
            
            $productsQuery = $this->productsListQuery(!empty($sphinxArray) ? ['[[products.id]]'=>ArrayHelper::getColumn($sphinxArray, 'id')] : []);
            if (!$productsQuery instanceof ActiveQuery) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'ActiveQuery $productsQuery']));
            }
            
            $renderArray['paginator'] = $productsQuery->paginator;
            if (!$renderArray['paginator'] instanceof Pagination) {
                if (YII_ENV_DEV) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'Pagination']));
                } else {
                    $renderArray['paginator'] = null;
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'Pagination']), __METHOD__);
                }
            }
            
            $renderArray['productsList'] = $productsQuery->all();
            if (!is_array($renderArray['productsList']) || (!empty($renderArray['productsList']) && !$renderArray['productsList'][0] instanceof ProductsModel)) {
                if (YII_ENV_DEV) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'productsList\']']));
                } else {
                    $renderArray['productsList'] = null;
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'productsList\']']), __METHOD__);
                }
            }
            
            $colorsQuery = ColorsModel::find();
            $colorsQuery->extendSelect(['id', 'color']);
            $colorsQuery->distinct();
            $colorsQuery->innerJoin('{{products_colors}}', '[[colors.id]]=[[products_colors.id_color]]');
            $colorsQuery->innerJoin('{{products}}', '[[products_colors.id_product]]=[[products.id]]');
            $colorsQuery->where(['[[products.active]]'=>true]);
            if (!empty($sphinxArray)) {
                $colorsQuery->andWhere(['[[products.id]]'=>ArrayHelper::getColumn($sphinxArray, 'id')]);
            }
            $colorsQuery->orderBy(['[[colors.color]]'=>SORT_ASC]);
            $renderArray['colorsList'] = $colorsQuery->allMap('id', 'color');
            if (!is_array($renderArray['colorsList']) || empty($renderArray['colorsList'])) {
                if (YII_ENV_DEV) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'colorsList\']']));
                } else {
                    $renderArray['colorsList'] = null;
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'colorsList\']']), __METHOD__);
                }
            }
            
            $sizesQuery = SizesModel::find();
            $sizesQuery->extendSelect(['id', 'size']);
            $sizesQuery->distinct();
            $sizesQuery->innerJoin('{{products_sizes}}', '[[sizes.id]]=[[products_sizes.id_size]]');
            $sizesQuery->innerJoin('{{products}}', '[[products_sizes.id_product]]=[[products.id]]');
            $sizesQuery->where(['[[products.active]]'=>true]);
            if (!empty($sphinxArray)) {
                $sizesQuery->andWhere(['[[products.id]]'=>ArrayHelper::getColumn($sphinxArray, 'id')]);
            }
            $sizesQuery->orderBy(['[[sizes.size]]'=>SORT_ASC]);
            $renderArray['sizesList'] = $sizesQuery->allMap('id', 'size');
            if (!is_array($renderArray['sizesList']) || empty($renderArray['sizesList'])) {
                if (YII_ENV_DEV) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'sizesList\']']));
                } else {
                    $renderArray['sizesList'] = null;
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'sizesList\']']), __METHOD__);
                }
            }
            
            $brandsQuery = BrandsModel::find();
            $brandsQuery->extendSelect(['id', 'brand']);
            $brandsQuery->distinct();
            $brandsQuery->innerJoin('{{products_brands}}', '[[brands.id]]=[[products_brands.id_brand]]');
            $brandsQuery->innerJoin('{{products}}', '[[products_brands.id_product]]=[[products.id]]');
            $brandsQuery->where(['[[products.active]]'=>true]);
            if (!empty($sphinxArray)) {
                $brandsQuery->andWhere(['[[products.id]]'=>ArrayHelper::getColumn($sphinxArray, 'id')]);
            }
            $brandsQuery->orderBy(['[[brands.brand]]'=>SORT_ASC]);
            $renderArray['brandsList'] = $brandsQuery->allMap('id', 'brand');
            if (!is_array($renderArray['brandsList']) || empty($renderArray['brandsList'])) {
                if (YII_ENV_DEV) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'brandsList\']']));
                } else {
                    $renderArray['brandsList'] = null;
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'brandsList\']']), __METHOD__);
                }
            }
            
            $renderArray['sortingFieldList'] = ['date'=>\Yii::t('base', 'Sorting by date'), 'price'=>\Yii::t('base', 'Sorting by price')];
            $renderArray['sortingTypeList'] = ['SORT_DESC'=>\Yii::t('base', 'Sort descending'), 'SORT_ASC'=>\Yii::t('base', 'Sort ascending')];
            
            \Yii::$app->params['breadcrumbs'] = ['label'=>\Yii::t('base', 'Searching results')];
            
            Url::remember();
            
            return $this->render('products-list.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует ProductsModel ActiveQuery для
     * - ProductsListController::actionIndex
     * - ProductsListController::actionSearch
     * @param array $extraWhere массив дополнительный условий, будет добавлен к WHERE
     * @return ActiveQuery
     */
    private function productsListQuery(array $extraWhere=[]): ActiveQuery
    {
        try {
            $productsQuery = ProductsModel::find();
            $productsQuery->extendSelect(['id', 'date', 'name', 'short_description', 'price', 'images', 'id_category', 'id_subcategory', 'active', 'seocode']);
            $productsQuery->where(['[[products.active]]'=>true]);
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['categoryKey']))) {
                $productsQuery->innerJoin('{{categories}}', '[[categories.id]]=[[products.id_category]]');
                $productsQuery->andWhere(['[[categories.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
            }
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['subcategoryKey']))) {
                $productsQuery->innerJoin('{{subcategory}}', '[[subcategory.id]]=[[products.id_subcategory]]');
                $productsQuery->andWhere(['[[subcategory.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
            }
            if (!empty($extraWhere)) {
                $productsQuery->andWhere($extraWhere);
            }
            $productsQuery->addFilters();
            $productsQuery->extendLimit();
            $sortingField = !empty(\Yii::$app->filters->sortingField) ? \Yii::$app->filters->sortingField : 'date';
            $sortingType = (!empty(\Yii::$app->filters->sortingType) && \Yii::$app->filters->sortingType === 'SORT_ASC') ? SORT_ASC : SORT_DESC;
            $productsQuery->orderBy(['[[products.' . $sortingField . ']]'=>$sortingType]);
            
            return $productsQuery;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function behaviors()
    {
        return [
            [
                'class'=>'app\filters\ProductsFilter',
            ],
            [
                'class'=>'app\filters\CurrencyFilter',
            ],
        ];
    }
}
