<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\db\ActiveRecord;
use yii\helpers\{Html,
    Url};
use app\handlers\AbstractBaseHandler;
use app\services\GetCurrentCurrencyModelService;
use app\helpers\HashHelper;
use app\finders\{AdminOrdersCsvFinder,
    OrdersFiltersSessionFinder};
use app\models\CurrencyInterface;

/**
 * Обрабатывает запрос на сохранение заказов в формате csv
 */
class CsvGetOrdersRequestHandler extends AbstractBaseHandler
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
                
                $filename = sprintf('orders%s.csv', time());
                $this->path = \Yii::getAlias(sprintf('%s/orders/%s', '@csvroot', $filename));
                $this->file = fopen($this->path, 'w');
                
                $finder = \Yii::$app->registry->get(OrdersFiltersSessionFinder::class, [
                    'key'=>HashHelper::createHash([\Yii::$app->params['ordersFilters']])
                ]);
                $filtersModel = $finder->find();
                
                $finder = \Yii::$app->registry->get(AdminOrdersCsvFinder::class, [
                    'filters'=>$filtersModel
                ]);
                $ordersQuery = $finder->find();
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                    'key'=>HashHelper::createCurrencyKey()
                ]);
                $currentCurrencyModel = $service->get();
                if (empty($currentCurrencyModel)) {
                    throw new ErrorException($this->emptyError('currentCurrencyModel'));
                }
                
                $this->writeHeaders();
                
                foreach ($ordersQuery->each(10) as $order) {
                    $this->writeOrder($order, $currentCurrencyModel);
                }
                
                fclose($this->file);
                
                return Html::a($filename, Url::to(sprintf('@csvweb/orders/%s', $filename)));
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
                \Yii::t('base', 'Order number'),
                \Yii::t('base', 'Client Id'),
                \Yii::t('base', 'Client name'),
                \Yii::t('base', 'Client surname'),
                'Email',
                \Yii::t('base', 'Phone'),
                \Yii::t('base', 'Address'),
                \Yii::t('base', 'City'),
                \Yii::t('base', 'Country'),
                \Yii::t('base', 'Postcode'),
                \Yii::t('base', 'Product Id'),
                \Yii::t('base', 'Quantity'),
                \Yii::t('base', 'Price'),
                \Yii::t('base', 'Total price'),
                \Yii::t('base', 'Color'),
                \Yii::t('base', 'Size'),
                \Yii::t('base', 'Delivery'),
                \Yii::t('base', 'Payment'),
                \Yii::t('base', 'Received'),
                \Yii::t('base', 'Received date'),
                \Yii::t('base', 'Processed'),
                \Yii::t('base', 'Canceled'),
                \Yii::t('base', 'Shipped'),
            ];
            
            $this->write($array);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Пишет данные заказов
     * @param ActiveRecord $order
     * @param CurrencyInterface $currentCurrencyModel
     */
    private function writeOrder(ActiveRecord $order, CurrencyInterface $currentCurrencyModel)
    {
        try {
            $array = [];
            
            $array[] = $order->id;
            $array[] = $order->id_user;
            $array[] = $order->name->name;
            $array[] = $order->surname->surname;
            $array[] = $order->email->email;
            $array[] = $order->phone->phone;
            $array[] = $order->address->address;
            $array[] = $order->city->city;
            $array[] = $order->country->country;
            $array[] = $order->postcode->postcode;
            $array[] = $order->id_product;
            $array[] = $order->quantity;
            $array[] = sprintf('%s %s', \Yii::$app->formatter->asDecimal($order->price * $currentCurrencyModel->exchangeRate(), 2), $currentCurrencyModel->code());
            $array[] = sprintf('%s %s', \Yii::$app->formatter->asDecimal(($order->price * $order->quantity) * $currentCurrencyModel->exchangeRate(), 2), $currentCurrencyModel->code());
            $array[] = $order->color->color;
            $array[] = $order->size->size;
            $array[] = $order->delivery->description;
            $array[] = $order->payment->description;
            $array[] = $order->received;
            $array[] = \Yii::$app->formatter->asDate($order->received_date);
            $array[] = $order->processed;
            $array[] = $order->canceled;
            $array[] = $order->shipped;
            
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
