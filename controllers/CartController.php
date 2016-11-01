<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use yii\web\Response;
use yii\db\Transaction;
use app\controllers\AbstractBaseController;
use app\models\{AddressModel,
    CitiesModel,
    CountriesModel,
    DeliveriesModel,
    EmailsModel,
    NamesModel,
    PaymentsModel,
    PhonesModel,
    PostcodesModel,
    ProductsModel,
    PurchasesModel,
    SurnamesModel,
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
            
            if (\Yii::$app->request->isPost || \Yii::$app->request->isAjax) {
                if ($rawPurchasesModel->load(\Yii::$app->request->post())) {
                    if ($rawPurchasesModel->validate()) {
                        if (!$this->write($rawPurchasesModel->toArray())) {
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
            
            if (\Yii::$app->request->isPost && $rawPurchasesModel->load(\Yii::$app->request->post()) && !empty(\Yii::$app->request->post('hash'))) {
                if ($rawPurchasesModel->validate()) {
                    $hash = \Yii::$app->request->post('hash') ?? '';
                    if (array_key_exists($hash, (\Yii::$app->params['cartArray']))) {
                        unset(\Yii::$app->params['cartArray'][$hash]);
                        if (!$this->write($rawPurchasesModel->toArray())) {
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
            if (\Yii::$app->request->isPost && !empty(\Yii::$app->request->post('hash'))) {
                $hash = \Yii::$app->request->post('hash') ?? '';
                if (array_key_exists($hash, (\Yii::$app->params['cartArray']))) {
                    unset(\Yii::$app->params['cartArray'][$hash]);
                    if (empty(\Yii::$app->params['cartArray'])) {
                        $cartKey = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
                        $customerKey = HashHelper::createHash([\Yii::$app->params['customerKey'], \Yii::$app->user->id ?? '']);
                        SessionHelper::remove([$cartKey, $customerKey]);
                        \Yii::$app->params['customerArray'] = [];
                    } else {
                        $cartKey = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
                        SessionHelper::write($cartKey, \Yii::$app->params['cartArray']);
                    }
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
            
            $renderArray = InstancesHelper::getInstances();
            if (!is_array($renderArray) || empty($renderArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray']));
            }
            
            $rawNamesModel = new NamesModel(['scenario'=>NamesModel::GET_FROM_ORDER]);
            $rawSurnamesModel = new SurnamesModel(['scenario'=>SurnamesModel::GET_FROM_ORDER]);
            $rawEmailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_ORDER]);
            $rawPhonesModel = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_ORDER]);
            $rawAddressModel = new AddressModel(['scenario'=>AddressModel::GET_FROM_ORDER]);
            $rawCitiesModel = new CitiesModel(['scenario'=>CitiesModel::GET_FROM_ORDER]);
            $rawCountriesModel = new CountriesModel(['scenario'=>CountriesModel::GET_FROM_ORDER]);
            $rawPostcodesModel = new PostcodesModel(['scenario'=>PostcodesModel::GET_FROM_ORDER]);
            $rawUsersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_ORDER]);
            $rawDeliveriesModel = new DeliveriesModel(['scenario'=>DeliveriesModel::GET_FROM_ORDER]);
            $rawPaymentsModel = new PaymentsModel(['scenario'=>PaymentsModel::GET_FROM_ORDER]);
            
            if (\Yii::$app->request->isPost && $rawNamesModel->load(\Yii::$app->request->post()) && $rawSurnamesModel->load(\Yii::$app->request->post()) && $rawEmailsModel->load(\Yii::$app->request->post()) && $rawPhonesModel->load(\Yii::$app->request->post()) && $rawAddressModel->load(\Yii::$app->request->post()) && $rawCitiesModel->load(\Yii::$app->request->post()) && $rawCountriesModel->load(\Yii::$app->request->post()) && $rawPostcodesModel->load(\Yii::$app->request->post()) && $rawUsersModel->load(\Yii::$app->request->post()) && $rawDeliveriesModel->load(\Yii::$app->request->post()) && $rawPaymentsModel->load(\Yii::$app->request->post())) {
                if ($rawNamesModel->validate() && $rawSurnamesModel->validate() && $rawEmailsModel->validate() && $rawPhonesModel->validate() && $rawAddressModel->validate() && $rawCitiesModel->validate() && $rawCountriesModel->validate() && $rawPostcodesModel->validate() && $rawUsersModel->validate() && $rawDeliveriesModel->validate() && $rawPaymentsModel->validate()) {
                    \Yii::$app->params['customerArray'][NamesModel::tableName()] = $rawNamesModel->toArray();
                    \Yii::$app->params['customerArray'][SurnamesModel::tableName()] = $rawSurnamesModel->toArray();
                    \Yii::$app->params['customerArray'][EmailsModel::tableName()] = $rawEmailsModel->toArray();
                    \Yii::$app->params['customerArray'][PhonesModel::tableName()] = $rawPhonesModel->toArray();
                    \Yii::$app->params['customerArray'][AddressModel::tableName()] = $rawAddressModel->toArray();
                    \Yii::$app->params['customerArray'][CitiesModel::tableName()] = $rawCitiesModel->toArray();
                    \Yii::$app->params['customerArray'][CountriesModel::tableName()] = $rawCountriesModel->toArray();
                    \Yii::$app->params['customerArray'][PostcodesModel::tableName()] = $rawPostcodesModel->toArray();
                    \Yii::$app->params['customerArray'][UsersModel::tableName()] = $rawUsersModel->toArray([], ['password']);
                    \Yii::$app->params['customerArray'][DeliveriesModel::tableName()] = $rawDeliveriesModel->toArray();
                    \Yii::$app->params['customerArray'][PaymentsModel::tableName()] = $rawPaymentsModel->toArray();
                    
                    $hash = HashHelper::createHash([\Yii::$app->params['customerKey'], \Yii::$app->user->id ?? '']);
                    SessionHelper::write($hash, \Yii::$app->params['customerArray']);
                    
                    return $this->redirect(Url::to(['/cart/check']));
                } else {
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'Model::validate']), __METHOD__);
                }
            }
            
            if (!empty(\Yii::$app->params['customerArray'][NamesModel::tableName()])) {
                $rawNamesModel = \Yii::configure($rawNamesModel, \Yii::$app->params['customerArray'][NamesModel::tableName()]);
            } elseif (\Yii::$app->user->isGuest == false) {
                $rawNamesModel = \Yii::configure($rawNamesModel, \Yii::$app->user->identity->name->toArray());
            }
            
            if (!empty(\Yii::$app->params['customerArray'][SurnamesModel::tableName()])) {
                $rawSurnamesModel = \Yii::configure($rawSurnamesModel, \Yii::$app->params['customerArray'][SurnamesModel::tableName()]);
            } elseif (\Yii::$app->user->isGuest == false) {
                $rawSurnamesModel = \Yii::configure($rawSurnamesModel, \Yii::$app->user->identity->surname->toArray());
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
            
            if (!empty(\Yii::$app->params['customerArray'][CitiesModel::tableName()])) {
                $rawCitiesModel = \Yii::configure($rawCitiesModel, \Yii::$app->params['customerArray'][CitiesModel::tableName()]);
            } elseif (\Yii::$app->user->isGuest == false && !empty(\Yii::$app->user->identity->id_city)) {
                $rawCitiesModel = \Yii::configure($rawCitiesModel, \Yii::$app->user->identity->city->toArray());
            }
            
            if (!empty(\Yii::$app->params['customerArray'][CountriesModel::tableName()])) {
                $rawCountriesModel = \Yii::configure($rawCountriesModel, \Yii::$app->params['customerArray'][CountriesModel::tableName()]);
            } elseif (\Yii::$app->user->isGuest == false && !empty(\Yii::$app->user->identity->id_country)) {
                $rawCountriesModel = \Yii::configure($rawCountriesModel, \Yii::$app->user->identity->country->toArray());
            }
            
            if (!empty(\Yii::$app->params['customerArray'][PostcodesModel::tableName()])) {
                $rawPostcodesModel = \Yii::configure($rawPostcodesModel, \Yii::$app->params['customerArray'][PostcodesModel::tableName()]);
            } elseif (\Yii::$app->user->isGuest == false && !empty(\Yii::$app->user->identity->id_postcode)) {
                $rawPostcodesModel = \Yii::configure($rawPostcodesModel, \Yii::$app->user->identity->postcode->toArray());
            }
            
            if (!empty(\Yii::$app->params['customerArray'][DeliveriesModel::tableName()])) {
                $rawDeliveriesModel = \Yii::configure($rawDeliveriesModel, \Yii::$app->params['customerArray'][DeliveriesModel::tableName()]);
            }
            
            if (!empty(\Yii::$app->params['customerArray'][PaymentsModel::tableName()])) {
                $rawPaymentsModel = \Yii::configure($rawPaymentsModel, \Yii::$app->params['customerArray'][PaymentsModel::tableName()]);
            }
            
            $renderArray['namesModel'] = $rawNamesModel;
            $renderArray['surnamesModel'] = $rawSurnamesModel;
            $renderArray['emailsModel'] = $rawEmailsModel;
            $renderArray['phonesModel'] = $rawPhonesModel;
            $renderArray['addressModel'] = $rawAddressModel;
            $renderArray['citiesModel'] = $rawCitiesModel;
            $renderArray['countriesModel'] = $rawCountriesModel;
            $renderArray['postcodesModel'] = $rawPostcodesModel;
            $renderArray['usersModel'] = $rawUsersModel;
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
     * @param array $purchaseArray массив данных для записи в сессию
     * @return bool
     */
    private function write(array $purchaseArray): bool
    {
        try {
            $clonePurchaseArray = $purchaseArray;
            unset($clonePurchaseArray['quantity']);
            $hash = HashHelper::createHash($clonePurchaseArray);
            \Yii::$app->params['cartArray'][$hash] = $purchaseArray;
            
            $cartKey = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
            SessionHelper::write($cartKey, \Yii::$app->params['cartArray']);
            
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
