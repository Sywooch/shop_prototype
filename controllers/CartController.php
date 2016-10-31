<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use yii\web\Response;
use yii\db\Transaction;
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
use app\validators\{AddressExistsCreateValidator,
    EmailExistsCreateValidator,
    PhoneExistsCreateValidator};

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
                $cartKey = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
                $customerKey = HashHelper::createHash([\Yii::$app->params['customerKey'], \Yii::$app->user->id ?? '']);
                SessionHelper::remove([$cartKey, $customerKey]);
                \Yii::$app->params['cartArray'] = [];
                \Yii::$app->params['customerArray'] = [];
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
                            $cartKey = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
                            $customerKey = HashHelper::createHash([\Yii::$app->params['customerKey'], \Yii::$app->user->id ?? '']);
                            SessionHelper::remove([$cartKey, $customerKey]);
                            \Yii::$app->params['customerArray'] = [];
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
            $rawEmailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_ORDER]);
            $rawPhonesModel = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_ORDER]);
            $rawAddressModel = new AddressModel(['scenario'=>AddressModel::GET_FROM_ORDER]);
            $rawDeliveriesModel = new DeliveriesModel(['scenario'=>DeliveriesModel::GET_FROM_ORDER]);
            $rawPaymentsModel = new PaymentsModel(['scenario'=>PaymentsModel::GET_FROM_ORDER]);
            
            if (\Yii::$app->request->isPost && $rawUsersModel->load(\Yii::$app->request->post()) && $rawEmailsModel->load(\Yii::$app->request->post()) && $rawPhonesModel->load(\Yii::$app->request->post()) && $rawAddressModel->load(\Yii::$app->request->post()) && $rawDeliveriesModel->load(\Yii::$app->request->post()) && $rawPaymentsModel->load(\Yii::$app->request->post())) {
                if ($rawUsersModel->validate() && $rawEmailsModel->validate() && $rawPhonesModel->validate() && $rawAddressModel->validate() && $rawDeliveriesModel->validate() && $rawPaymentsModel->validate()) {
                    \Yii::$app->params['customerArray'][UsersModel::tableName()] = $rawUsersModel->toArray([], ['password']);
                    \Yii::$app->params['customerArray'][EmailsModel::tableName()] = $rawEmailsModel->toArray();
                    \Yii::$app->params['customerArray'][PhonesModel::tableName()] = $rawPhonesModel->toArray();
                    \Yii::$app->params['customerArray'][AddressModel::tableName()] = $rawAddressModel->toArray();
                    \Yii::$app->params['customerArray'][DeliveriesModel::tableName()] = $rawDeliveriesModel->toArray();
                    \Yii::$app->params['customerArray'][PaymentsModel::tableName()] = $rawPaymentsModel->toArray();
                    
                    $hash = HashHelper::createHash([\Yii::$app->params['customerKey'], \Yii::$app->user->id ?? '']);
                    SessionHelper::write($hash, \Yii::$app->params['customerArray']);
                    
                    return $this->redirect(Url::to(['/cart/check']));
                } else {
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'Model::validate']), __METHOD__);
                }
            }
            
            if (!empty(\Yii::$app->params['customerArray'][UsersModel::tableName()])) {
                $rawUsersModel = \Yii::configure($rawUsersModel, \Yii::$app->params['customerArray'][UsersModel::tableName()]);
            } elseif (\Yii::$app->user->isGuest == false) {
                $rawUsersModel = \Yii::configure($rawUsersModel, \Yii::$app->user->identity->toArray());
            }
            
            if (\Yii::$app->user->isGuest == false) {
                $rawEmailsModel = \Yii::configure($rawEmailsModel, \Yii::$app->user->identity->email->toArray());
            } elseif (!empty(\Yii::$app->params['customerArray'][EmailsModel::tableName()])) {
                $rawEmailsModel = \Yii::configure($rawEmailsModel, \Yii::$app->params['customerArray'][EmailsModel::tableName()]);
            }
            
            if (!empty(\Yii::$app->params['customerArray'][PhonesModel::tableName()])) {
                $rawPhonesModel = \Yii::configure($rawPhonesModel, \Yii::$app->params['customerArray'][PhonesModel::tableName()]);
            } elseif (\Yii::$app->user->isGuest == false && !empty(\Yii::$app->user->identity->id_phone)) {
                $rawPhonesModel = \Yii::configure($rawPhonesModel, \Yii::$app->user->identity->phone->toArray());
            }
            
            if (!empty(\Yii::$app->params['customerArray'][AddressModel::tableName()])) {
                $rawAddressModel = \Yii::configure($rawAddressModel, \Yii::$app->params['customerArray'][AddressModel::tableName()]);
            } elseif (\Yii::$app->user->isGuest == false && !empty(\Yii::$app->user->identity->id_address)) {
                $rawAddressModel = \Yii::configure($rawAddressModel, \Yii::$app->user->identity->address->toArray());
            }
            
            if (!empty(\Yii::$app->params['customerArray'][DeliveriesModel::tableName()])) {
                $rawDeliveriesModel = \Yii::configure($rawDeliveriesModel, \Yii::$app->params['customerArray'][DeliveriesModel::tableName()]);
            }
            
            if (!empty(\Yii::$app->params['customerArray'][PaymentsModel::tableName()])) {
                $rawPaymentsModel = \Yii::configure($rawPaymentsModel, \Yii::$app->params['customerArray'][PaymentsModel::tableName()]);
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
     * Обрабатывает запрос к странице проверки данных покупки
     * @return string
     */
    public function actionCheck()
    {
        try {
            if (empty(\Yii::$app->params['cartArray']) || empty(\Yii::$app->params['customerArray'])) {
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
            
            $renderArray['usersModel'] = \Yii::configure((new UsersModel()), \Yii::$app->params['customerArray'][UsersModel::tableName()]);
            $renderArray['emailsModel'] = \Yii::configure((new EmailsModel()), \Yii::$app->params['customerArray'][EmailsModel::tableName()]);
            $renderArray['phonesModel'] = \Yii::configure((new PhonesModel()), \Yii::$app->params['customerArray'][PhonesModel::tableName()]);
            $renderArray['addressModel'] = \Yii::configure((new AddressModel()), \Yii::$app->params['customerArray'][AddressModel::tableName()]);
            
            $deliveriesQuery = DeliveriesModel::find();
            $deliveriesQuery->extendSelect(['id', 'name', 'description', 'price']);
            $deliveriesQuery->where(['[[deliveries.id]]'=>\Yii::$app->params['customerArray'][DeliveriesModel::tableName()]]);
            $renderArray['deliveriesModel'] = $deliveriesQuery->one();
            if (!$renderArray['deliveriesModel'] instanceof DeliveriesModel) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'DeliveriesModel']));
            }
            
            $paymentsQuery = PaymentsModel::find();
            $paymentsQuery->extendSelect(['id', 'name', 'description']);
            $paymentsQuery->where(['[[payments.id]]'=>\Yii::$app->params['customerArray'][PaymentsModel::tableName()]]);
            $renderArray['paymentsModel'] = $paymentsQuery->one();
            if (!$renderArray['paymentsModel'] instanceof PaymentsModel) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'PaymentsModel']));
            }
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/cart/index'], 'label'=>\Yii::t('base', 'Check information')];
            
            Url::remember(Url::current(), 'shop');
            
            return $this->render('check.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает подтвержденный заказ
     * @return string
     */
    public function actionComplete()
    {
        try {
            if (empty(\Yii::$app->params['cartArray']) || empty(\Yii::$app->params['customerArray'])) {
                return $this->redirect(Url::to(['/products-list/index']));
            }
            
            $renderArray = InstancesHelper::getInstances();
            if (!is_array($renderArray) || empty($renderArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray']));
            }
            
            $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
            
            try {
                if (\Yii::$app->user->isGuest == false) {
                    $rawUsersModel = \Yii::$app->user->identity;
                } else {
                    $rawUsersModel = \Yii::configure((new UsersModel()), \Yii::$app->params['customerArray'][UsersModel::tableName()]);
                }
                
                $rawEmailsModel = \Yii::configure((new EmailsModel()), \Yii::$app->params['customerArray'][EmailsModel::tableName()]);
                if (!(new EmailExistsCreateValidator())->validate($rawEmailsModel->email)) {
                    if (!$rawEmailsModel->save(false)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'EmailsModel::save']));
                    }
                }
                $emailsQuery = EmailsModel::find();
                $emailsQuery->extendSelect(['id', 'email']);
                $emailsQuery->where(['[[emails.email]]'=>$rawEmailsModel->email]);
                $emailsModel = $emailsQuery->one();
                if (!$emailsModel instanceof EmailsModel || $rawEmailsModel->email != $emailsModel->email) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'EmailsModel']));
                }
                $rawUsersModel->id_email = $emailsModel->id;
                
                $rawPhonesModel = \Yii::configure((new PhonesModel()), \Yii::$app->params['customerArray'][PhonesModel::tableName()]);
                if (!(new PhoneExistsCreateValidator())->validate($rawPhonesModel->phone)) {
                    if (!$rawPhonesModel->save(false)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'PhonesModel::save']));
                    }
                }
                $phonesQuery = PhonesModel::find();
                $phonesQuery->extendSelect(['id', 'phone']);
                $phonesQuery->where(['[[phones.phone]]'=>$rawPhonesModel->phone]);
                $phonesModel = $phonesQuery->one();
                if (!$phonesModel instanceof PhonesModel || $rawPhonesModel->phone != $phonesModel->phone) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'PhonesModel']));
                }
                $rawUsersModel->id_phone = $phonesModel->id;
                
                $rawAddressModel = \Yii::configure((new AddressModel()), \Yii::$app->params['customerArray'][AddressModel::tableName()]);
                if (!(new AddressExistsCreateValidator())->validateAttributes($rawAddressModel)) {
                    if (!$rawAddressModel->save(false)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'AddressModel::save']));
                    }
                }
                $addressQuery = AddressModel::find();
                $addressQuery->extendSelect(['id', 'address', 'city', 'country', 'postcode']);
                $addressQuery->where(['[[address.address]]'=>$rawAddressModel->address, '[[address.city]]'=>$rawAddressModel->city, '[[address.country]]'=>$rawAddressModel->country, '[[address.postcode]]'=>$rawAddressModel->postcode]);
                $addressModel = $addressQuery->one();
                if (!$addressModel instanceof AddressModel || $rawAddressModel->address != $addressModel->address || $rawAddressModel->city != $addressModel->city || $rawAddressModel->country != $addressModel->country || $rawAddressModel->postcode != $addressModel->postcode) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'AddressModel']));
                }
                $rawUsersModel->id_address = $addressModel->id;
                
                if (!$rawUsersModel->save(false)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'UsersModel::save']));
                }
                
                $transaction->commit();
                
                /*foreach (\Yii::$app->params['cartArray'] as $purchase) {
                    $purchasesModel = \Yii::configure((new PurchasesModel()), array_filter($purchase, function($key) {
                        return array_key_exists($key, (new PurchasesModel())->attributes);
                    }, ARRAY_FILTER_USE_KEY));
                }*/
            } catch (\Throwable $t) {
                $transaction->rollBack();
                throw $t;
            }
            
            
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/cart/index'], 'label'=>\Yii::t('base', 'Cart')];
            
            Url::remember(Url::current(), 'shop');
            
            return $this->render('complete.twig', $renderArray);
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
            $purchaseArray = ArrayHelper::merge($rawPurchasesModel->toArray(), $rawProductsModel->toArray());
            $clonePurchaseArray = $purchaseArray;
            unset($clonePurchaseArray['quantity']);
            $hash = HashHelper::createHash($clonePurchaseArray);
            if (array_key_exists($hash, \Yii::$app->params['cartArray'])) {
                \Yii::$app->params['cartArray'][$hash]['quantity'] += $purchaseArray['quantity'];
            } else {
                \Yii::$app->params['cartArray'][$hash] = $purchaseArray;
            }
            $key = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
            SessionHelper::write($key, \Yii::$app->params['cartArray']);
            
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
                'only'=>['index', 'set', 'customer', 'check']
            ],
            [
                'class'=>'app\filters\CustomerFilter',
                'only'=>['customer', 'check', 'complete']
            ],
            [
                'class'=>'app\filters\CartFilter',
            ],
        ];
    }
}
