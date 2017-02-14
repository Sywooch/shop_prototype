<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\db\ActiveRecord;
use yii\helpers\{ArrayHelper,
    Html,
    Url};
use app\handlers\AbstractBaseHandler;
use app\finders\{AdminProductsCsvFinder,
    AdminProductsFiltersSessionFinder};
use app\models\CurrencyInterface;
use app\helpers\HashHelper;

/**
 * Обрабатывает запрос на сохранение заказов в формате csv
 */
class CsvGetProductsRequestHandler extends AbstractBaseHandler
{
    /**
     * @var string путь к файлу
     */
    private $path;
    /**
     * @var дескриптор файла
     */
    private $file;
    
    /**
     * Обрабатывает запрос
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if ($request->isAjax === true) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                
                $filename = sprintf('products%s.csv', time());
                $this->path = \Yii::getAlias(sprintf('%s/products/%s', '@csvroot', $filename));
                $this->file = fopen($this->path, 'w');
                
                $finder = \Yii::$app->registry->get(AdminProductsFiltersSessionFinder::class, [
                    'key'=>HashHelper::createHash([\Yii::$app->params['adminProductsFilters']]),
                ]);
                $filtersModel = $finder->find();
                
                $finder = \Yii::$app->registry->get(AdminProductsCsvFinder::class, [
                    'filters'=>$filtersModel
                ]);
                $productsQuery = $finder->find();
                
                $this->writeHeaders();
                
                foreach ($productsQuery->each(10) as $product) {
                    $this->writeProduct($product);
                }
                
                fclose($this->file);
                
                return Html::a($filename, Url::to(sprintf('@csvweb/products/%s', $filename)));
            }
        } catch (\Throwable $t) {
            $this->cleanCsv();
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Пишет строку заголовков
     */
    private function writeHeaders()
    {
        try {
            $array = [
                \Yii::t('base', 'Product Id'),
                \Yii::t('base', 'Date added'),
                \Yii::t('base', 'Code'),
                \Yii::t('base', 'Product name'),
                \Yii::t('base', 'Short description'),
                \Yii::t('base', 'Description'),
                \Yii::t('base', 'Price'),
                \Yii::t('base', 'Images catalog'),
                \Yii::t('base', 'Category'),
                \Yii::t('base', 'Subcategory'),
                \Yii::t('base', 'Colors'),
                \Yii::t('base', 'Sizes'),
                \Yii::t('base', 'Brand'),
                \Yii::t('base', 'Active'),
                \Yii::t('base', 'Total products'),
                \Yii::t('base', 'Seocode'),
                \Yii::t('base', 'Views'),
            ];
            
            $this->write($array);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Пишет данные заказов
     * @param ActiveRecord $product
     */
    private function writeProduct(ActiveRecord $product)
    {
        try {
            $array = [];
            
            $array[] = $product->id;
            $array[] = \Yii::$app->formatter->asDate($product->date);
            $array[] = $product->code;
            $array[] = $product->name;
            $array[] = $product->short_description;
            $array[] = $product->description;
            $array[] = $product->price;
            $array[] = $product->images;
            $array[] = $product->category->name;
            $array[] = $product->subcategory->name;
            $array[] = implode(', ', ArrayHelper::getColumn($product->colors, 'color'));
            $array[] = implode(', ', ArrayHelper::getColumn($product->sizes, 'size'));
            $array[] = $product->brand->brand;
            $array[] = $product->active;
            $array[] = $product->total_products;
            $array[] = $product->seocode;
            $array[] = $product->views;
            
            $this->write($array);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Пишет данные
     * @param array $array 
     */
    private function write(array $array)
    {
        try {
            if (fputcsv($this->file, $array) === false) {
                throw new ErrorException($this->methodError('fputcsv'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Удаляет созданный файл в случае ошибки
     * @param string $path путь к файлу
     */
    private function cleanCsv()
    {
        try {
            if (file_exists($this->path)) {
                unlink($this->path);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
