<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use yii\web\Response;
use app\controllers\AbstractBaseController;
use app\models\{AddressModel,
    DeliveriesModel,
    EmailsModel,
    PaymentsModel,
    PhonesModel,
    ProductsModel,
    PurchasesModel,
    UsersModel};
use app\helpers\{HashHelper,
    InstancesHelper,
    SessionHelper,
    UrlHelper};
use app\widgets\CartWidget;

/**
 * Обрабатывает запросы, связанные с данными корзины
 */
class CartController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос детальной информации о товарах в корзине
     * @return string
     */
    public function actionIndex()
    {
        try {
            if (empty(\Yii::$app->params['cartArray'])) {
                return $this->redirect(Url::to(['/products-list/index']));
            }
            
            $renderArray = InstancesHelper::getInstances();
            if (!is_array($renderArray) || empty($renderArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray']));
            }
            
            foreach (\Yii::$app->params['cartArray'] as $hash=>$purchase) {
                $renderArray['purchasesList'][$hash] = \Yii::configure((new PurchasesModel()), array_filter($purchase, function($key) {
                    return array_key_exists($key, (new PurchasesModel())->attributes);
                }, ARRAY_FILTER_USE_KEY));
            }
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/cart/index'], 'label'=>\Yii::t('base', 'Cart')];
            
            Url::remember(Url::current(), 'shop');
            
            return $this->render('cart.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на добавление товара в корзину
     * @return string
     */
    public function actionSet()
    {
        try {
            $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_ADD_TO_CART]);
            $rawProductsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_TO_CART]);
            
            if (\Yii::$app->request->isPost || \Yii::$app->request->isAjax) {
                if ($rawPurchasesModel->load(\Yii::$app->request->post()) && $rawProductsModel->load(\Yii::$app->request->post())) {
                    if ($rawPurchasesModel->validate() && $rawProductsModel->validate()) {
                        if (!$this->write($rawPurchasesModel, $rawProductsModel)) {
                            throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'CartController::write']));
                        }
                    } else {
                        $this->writeMessageInLogs(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'Model::validate']), __METHOD__);
                    }
                }
                if (\Yii::$app->request->isAjax) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return CartWidget::widget();
                }
            }
            
            return $this->redirect(UrlHelper::previous('shop'));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            if (YII_ENV_DEV) {
                $this->throwException($t, __METHOD__);
            } else {
                return $this->redirect(UrlHelper::previous('shop'));
            }
        }
    }
    
    /**
     * Обрабатывает запрос на удаление всех товаров из корзины
     * @return string
     */
    public function actionClean()
    {
        try {
            if (\Yii::$app->request->isPost) {
                SessionHelper::remove([\Yii::$app->params['cartKey']]);
                \Yii::$app->params['cartArray'] = [];
            }
            
            return $this->redirect(UrlHelper::previous('shop'));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            if (YII_ENV_DEV) {
                $this->throwException($t, __METHOD__);
            } else {
                return $this->redirect(UrlHelper::previous('shop'));
            }
        }
    }
    
    /**
     * Обрабатывает запрос на обновление характеристик товара в корзине
     * @return string
     */
    public function actionUpdate()
    {
        try {
            $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_ADD_TO_CART]);
            $rawProductsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_TO_CART]);
            
            if (\Yii::$app->request->isPost && $rawPurchasesModel->load(\Yii::$app->request->post()) && $rawProductsModel->load(\Yii::$app->request->post())) {
                if ($rawPurchasesModel->validate() && $rawProductsModel->validate()) {
                    $hash = \Yii::$app->request->post('hash') ?? '';
                    if (array_key_exists($hash, (\Yii::$app->params['cartArray']))) {
                        unset(\Yii::$app->params['cartArray'][$hash]);
                        if (!$this->write($rawPurchasesModel, $rawProductsModel)) {
                            throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'CartController::write']));
                        }
                    }
                } else {
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'PurchasesModel::validate']), __METHOD__);
                }
            }
            
            return $this->redirect(UrlHelper::previous('shop'));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            if (YII_ENV_DEV) {
                $this->throwException($t, __METHOD__);
            } else {
                return $this->redirect(UrlHelper::previous('shop'));
            }
        }
    }
    
     /**
     * Обрабатывает запрос на удаление 1 товара из корзины
     * @return string
     */
    public function actionDelete()
    {
        try {
            $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_DELETE_FROM_CART]);
            
            if (\Yii::$app->request->isPost && $rawPurchasesModel->load(\Yii::$app->request->post())) {
                if ($rawPurchasesModel->validate()) {
                    $hash = \Yii::$app->request->post('hash') ?? '';
                    if (array_key_exists($hash, (\Yii::$app->params['cartArray']))) {
                        unset(\Yii::$app->params['cartArray'][$hash]);
                        if (empty(\Yii::$app->params['cartArray'])) {
                            SessionHelper::remove([\Yii::$app->params['cartKey']]);
                        } else {
                            SessionHelper::write(\Yii::$app->params['cartKey'], \Yii::$app->params['cartArray']);
                        }
                    }
                } else {
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'PurchasesModel::validate']), __METHOD__);
                }
            }
            
            return $this->redirect(UrlHelper::previous('shop'));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            if (YII_ENV_DEV) {
                $this->throwException($t, __METHOD__);
            } else {
                return $this->redirect(UrlHelper::previous('shop'));
            }
        }
    }
    
    /**
     * Обрабатывает запрос на добавление данных о покупателе 
     * @return string
     */
    public function actionCustomer()
    {
        try {
            if (empty(\Yii::$app->params['cartArray'])) {
                return $this->redirect(Url::to(['/products-list/index']));
            }
            
            $rawUsersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_ORDER]);
            if (!empty(\Yii::$app->params['customerArray'])) {
                if (!empty(\Yii::$app->params['customerArray'][UsersModel::tableName()])) {
                    $rawUsersModel = \Yii::configure($rawUsersModel, \Yii::$app->params['customerArray'][UsersModel::tableName()]);
                }
            } elseif (\Yii::$app->user->isGuest == false) {
                $rawUsersModel = \Yii::configure($rawUsersModel, array_filter(\Yii::$app->user->identity->toArray()));
            }
            
            $rawEmailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_ORDER]);
            if (!empty(\Yii::$app->params['customerArray'])) {
                if (!empty(\Yii::$app->params['customerArray'][EmailsModel::tableName()])) {
                    $rawEmailsModel = \Yii::configure($rawEmailsModel, \Yii::$app->params['customerArray'][EmailsModel::tableName()]);
                }
            } elseif (\Yii::$app->user->isGuest == false) {
                $rawEmailsModel = \Yii::configure($rawEmailsModel, array_filter(\Yii::$app->user->identity->email->toArray()));
            }
            
            $rawPhonesModel = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_ORDER]);
            if (!empty(\Yii::$app->params['customerArray'])) {
                if (!empty(\Yii::$app->params['customerArray'][PhonesModel::tableName()])) {
                    $rawPhonesModel = \Yii::configure($rawPhonesModel, \Yii::$app->params['customerArray'][PhonesModel::tableName()]);
                }
            } elseif (\Yii::$app->user->isGuest == false && !empty(\Yii::$app->user->identity->id_phone)) {
                $rawPhonesModel = \Yii::configure($rawPhonesModel, array_filter(\Yii::$app->user->identity->phone->toArray()));
            }
            
            $rawAddressModel = new AddressModel(['scenario'=>AddressModel::GET_FROM_ORDER]);
            if (!empty(\Yii::$app->params['customerArray'])) {
                if (!empty(\Yii::$app->params['customerArray'][AddressModel::tableName()])) {
                    $rawAddressModel = \Yii::configure($rawAddressModel, \Yii::$app->params['customerArray'][AddressModel::tableName()]);
                }
            } elseif (\Yii::$app->user->isGuest == false && !empty(\Yii::$app->user->identity->id_address)) {
                $rawAddressModel = \Yii::configure($rawAddressModel, array_filter(\Yii::$app->user->identity->address->toArray()));
            }
            
            $rawDeliveriesModel = new DeliveriesModel(['scenario'=>DeliveriesModel::GET_FROM_ORDER]);
            if (!empty(\Yii::$app->params['customerArray'])) {
                if (!empty(\Yii::$app->params['customerArray'][DeliveriesModel::tableName()])) {
                    $rawDeliveriesModel = \Yii::configure($rawDeliveriesModel, \Yii::$app->params['customerArray'][DeliveriesModel::tableName()]);
                }
            }
            
            $rawPaymentsModel = new PaymentsModel(['scenario'=>PaymentsModel::GET_FROM_ORDER]);
            if (!empty(\Yii::$app->params['customerArray'])) {
                if (!empty(\Yii::$app->params['customerArray'][PaymentsModel::tableName()])) {
                    $rawPaymentsModel = \Yii::configure($rawPaymentsModel, \Yii::$app->params['customerArray'][PaymentsModel::tableName()]);
                }
            }
            
            $renderArray = InstancesHelper::getInstances();
            if (!is_array($renderArray) || empty($renderArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray']));
            }
            
            $renderArray['usersModel'] = $rawUsersModel;
            $renderArray['emailsModel'] = $rawEmailsModel;
            $renderArray['phonesModel'] = $rawPhonesModel;
            $renderArray['addressModel'] = $rawAddressModel;
            $renderArray['deliveriesModel'] = $rawDeliveriesModel;
            $renderArray['paymentsModel'] = $rawPaymentsModel;
            
            $deliveriesQuery = DeliveriesModel::find();
            $deliveriesQuery->extendSelect(['id', 'name', 'description', 'price']);
            $deliveriesQuery->asArray();
            $deliveriesArray = $deliveriesQuery->all();
            if (!is_array($deliveriesArray) || empty($deliveriesArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $deliveriesArray']));
            }
            ArrayHelper::multisort($deliveriesArray, 'name', SORT_ASC);
            $renderArray['deliveriesList'] = $deliveriesArray;
            
            $paymentsQuery = PaymentsModel::find();
            $paymentsQuery->extendSelect(['id', 'name', 'description']);
            $paymentsQuery->asArray();
            $paymentsArray = $paymentsQuery->all();
            if (!is_array($paymentsArray) || empty($paymentsArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $paymentsArray']));
            }
            ArrayHelper::multisort($paymentsArray, 'name', SORT_ASC);
            $renderArray['paymentsList'] = $paymentsArray;
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/cart/index'], 'label'=>\Yii::t('base', 'Customer information')];
            
            Url::remember(Url::current(), 'shop');
            
            return $this->render('customer.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Пишет в сессию массив данных о товарах в корзине
     * @param object $rawPurchasesModel объект PurchasesModel
     * @param object $rawProductsModel объект ProductsModel
     * @return bool
     */
    private function write(PurchasesModel $rawPurchasesModel, ProductsModel $rawProductsModel): bool
    {
        try {
            $purchaseArray = array_filter(ArrayHelper::merge($rawPurchasesModel->attributes, $rawProductsModel->attributes));
            $clonePurchaseArray = $purchaseArray;
            unset($clonePurchaseArray['quantity']);
            $hash = HashHelper::createHash($clonePurchaseArray);
            if (array_key_exists($hash, \Yii::$app->params['cartArray'])) {
                \Yii::$app->params['cartArray'][$hash]['quantity'] += $purchaseArray['quantity'];
            } else {
                \Yii::$app->params['cartArray'][$hash] = $purchaseArray;
            }
            SessionHelper::write(\Yii::$app->params['cartKey'], \Yii::$app->params['cartArray']);
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function behaviors()
    {
        return [
            [
                'class'=>'app\filters\CurrencyFilter',
                'only'=>['index', 'set', 'customer']
            ],
            [
                'class'=>'app\filters\CartFilter',
                'only'=>['index', 'set', 'update', 'delete', 'customer']
            ],
        ];
    }
}
