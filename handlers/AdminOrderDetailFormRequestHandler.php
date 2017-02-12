<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\handlers\AbstractBaseHandler;
use app\services\GetCurrentCurrencyModelService;
use app\forms\{AbstractBaseForm,
    AdminChangeOrderForm};
use app\widgets\AdminOrderDetailFormWidget;
use app\finders\{ColorsProductFinder,
    DeliveriesFinder,
    OrderStatusesFinder,
    PaymentsFinder,
    PurchaseIdFinder,
    SizesProductFinder};
use app\helpers\HashHelper;
use app\models\{CurrencyInterface,
    PurchasesModel};

/**
 * Обрабатывает запрос на получение данных 
 * с формой редактирования деталей заказа
 */
class AdminOrderDetailFormRequestHandler extends AbstractBaseHandler
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы
     * @param array $request
     */
    public function handle($request)
    {
        try {
           $form = new AdminChangeOrderForm(['scenario'=>AdminChangeOrderForm::GET]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                        'key'=>HashHelper::createCurrencyKey()
                    ]);
                    $currentCurrencyModel = $service->get();
                    if (empty($currentCurrencyModel)) {
                        throw new ErrorException($this->emptyError('currentCurrencyModel'));
                    }
                    
                    $finder = \Yii::$app->registry->get(PurchaseIdFinder::class, [
                        'id'=>$form->id
                    ]);
                    $purchasesModel = $finder->find();
                    if (empty($purchasesModel)) {
                        throw new ErrorException($this->emptyError('purchasesModel'));
                    }
                    
                    $finder = \Yii::$app->registry->get(OrderStatusesFinder::class);
                    $statusesArray = $finder->find();
                    if (empty($statusesArray)) {
                        throw new ErrorException($this->emptyError('statusesArray'));
                    }
                    
                    $finder = \Yii::$app->registry->get(ColorsProductFinder::class, [
                        'id_product'=>$purchasesModel->id_product
                    ]);
                    $colorsArray = $finder->find();
                    if (empty($colorsArray)) {
                        throw new ErrorException($this->emptyError('colorsArray'));
                    }
                    
                    $finder = \Yii::$app->registry->get(SizesProductFinder::class, [
                        'id_product'=>$purchasesModel->id_product
                    ]);
                    $sizesArray = $finder->find();
                    if (empty($sizesArray)) {
                        throw new ErrorException($this->emptyError('sizesArray'));
                    }
                    
                    $finder = \Yii::$app->registry->get(DeliveriesFinder::class);
                    $deliveriesArray = $finder->find();
                    if (empty($deliveriesArray)) {
                        throw new ErrorException($this->emptyError('deliveriesArray'));
                    }
                    
                    $finder = \Yii::$app->registry->get(PaymentsFinder::class);
                    $paymentsArray = $finder->find();
                    if (empty($paymentsArray)) {
                        throw new ErrorException($this->emptyError('paymentsArray'));
                    }
                    
                    $adminChangeOrderForm = new AdminChangeOrderForm(['scenario'=>AdminChangeOrderForm::SAVE]);
                    
                    $adminOrderDetailFormWidgetConfig = $this->adminOrderDetailFormWidgetConfig($currentCurrencyModel, $purchasesModel, $statusesArray, $colorsArray, $sizesArray, $deliveriesArray, $paymentsArray, $adminChangeOrderForm);
                    
                    return AdminOrderDetailFormWidget::widget($adminOrderDetailFormWidgetConfig);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminOrderDetailFormWidget
     * @param CurrencyInterface $currentCurrencyModel
     * @params PurchasesModel $purchasesModel
     * @param array $statusesArray
     * @param array $colorsArray
     * @param array $sizesArray
     * @param array $deliveriesArray
     * @param array $paymentsArray
     * @param AbstractBaseForm $adminChangeOrderForm
     * @return array
     */
    private function adminOrderDetailFormWidgetConfig(CurrencyInterface $currentCurrencyModel, PurchasesModel $purchasesModel, array $statusesArray, array $colorsArray, array $sizesArray, array $deliveriesArray, array $paymentsArray, AbstractBaseForm $adminChangeOrderForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['purchase'] = $purchasesModel;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['statuses'] = $statusesArray;
            
            ArrayHelper::multisort($colorsArray, 'color');
            $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
            
            ArrayHelper::multisort($sizesArray, 'size');
            $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
            
            ArrayHelper::multisort($deliveriesArray, 'description');
            $dataArray['deliveries'] = ArrayHelper::map($deliveriesArray, 'id', 'description');
            
            ArrayHelper::multisort($paymentsArray, 'description');
            $dataArray['payments'] = ArrayHelper::map($paymentsArray, 'id', 'description');
            
            $dataArray['form'] = $adminChangeOrderForm;
            $dataArray['template'] = 'admin-order-detail-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
