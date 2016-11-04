<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use yii\web\Response;
use yii\db\Transaction;
use app\controllers\{AbstractBaseController,
    CartControllerHelper};
use app\models\{AddressModel,
    CitiesModel,
    ColorsModel,
    CountriesModel,
    DeliveriesModel,
    EmailsModel,
    NamesModel,
    PaymentsModel,
    PhonesModel,
    PostcodesModel,
    ProductsModel,
    PurchasesModel,
    SizesModel,
    SurnamesModel,
    UsersModel};
use app\helpers\{HashHelper,
    InstancesHelper,
    MailHelper,
    SessionHelper,
    UrlHelper};
use app\widgets\CartWidget;
use app\validators\{AddressExistsCreateValidator,
    CityExistsCreateValidator,
    CountryExistsCreateValidator,
    EmailExistsCreateValidator,
    NameExistsCreateValidator,
    PostcodeExistsCreateValidator,
    SurnameExistsCreateValidator,
    PhoneExistsCreateValidator};

/**
 * Обрабатывает запросы, связанные с данными корзины
 */
class CartController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос детальной информации о товарах в корзине
     */
    public function actionIndex()
    {
        try {
            if (empty(\Yii::$app->params['cartArray'])) {
                return $this->redirect(Url::to(['/products-list/index']));
            }
            
            $renderArray = CartControllerHelper::indexGet();
            
            Url::remember(Url::current(), 'shop');
            
            return $this->render('cart.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на добавление товара в корзину
     */
    public function actionSet()
    {
        try {
            if (\Yii::$app->request->isAjax) {
                return CartControllerHelper::setAjax();
            }
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на удаление всех товаров из корзины
     */
    public function actionClean()
    {
        try {
            if (\Yii::$app->request->isPost) {
                CartControllerHelper::cleanPost();
            }
            
            return $this->redirect(UrlHelper::previous('shop'));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на обновление характеристик товара в корзине
     */
    public function actionUpdate()
    {
        try {
            if (\Yii::$app->request->isPost) {
                CartControllerHelper::updatePost();
            }
            
            return $this->redirect(UrlHelper::previous('shop'));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
     /**
     * Обрабатывает запрос на удаление 1 товара из корзины
     */
    public function actionDelete()
    {
        try {
            if (\Yii::$app->request->isPost) {
                CartControllerHelper::deletePost();
            }
            
            return $this->redirect(UrlHelper::previous('shop'));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
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
                $rawNamesModel = \Yii::configure($rawNamesModel, \Yii::$app->user->identity->surname->toArray());
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
            
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/cart/index'], 'label'=>\Yii::t('base', 'Customer information')];
            
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
            
            $productsQuery = ProductsModel::find();
            $productsQuery->extendSelect(['id', 'name', 'short_description', 'price', 'images', 'seocode']);
            $productsQuery->where(['[[products.id]]'=>ArrayHelper::getColumn(\Yii::$app->params['cartArray'], 'id_product')]);
            $productsQuery->asArray();
            $productsArray = $productsQuery->all();
            if (!is_array($productsArray) || empty($productsArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $productsArray']));
            }
            $productsArray = ArrayHelper::index($productsArray, 'id');
            
            $colorsQuery = ColorsModel::find();
            $colorsQuery->extendSelect(['id', 'color']);
            $colorsQuery->where(['[[colors.id]]'=>ArrayHelper::getColumn(\Yii::$app->params['cartArray'], 'id_color')]);
            $colorsQuery->asArray();
            $colorsArray = $colorsQuery->all();
            if (!is_array($colorsArray) || empty($colorsArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $colorsArray']));
            }
            $colorsArray = ArrayHelper::index($colorsArray, 'id');
            
            $sizesQuery = SizesModel::find();
            $sizesQuery->extendSelect(['id', 'size']);
            $sizesQuery->where(['[[sizes.id]]'=>ArrayHelper::getColumn(\Yii::$app->params['cartArray'], 'id_size')]);
            $sizesQuery->asArray();
            $sizesArray = $sizesQuery->all();
            if (!is_array($sizesArray) || empty($sizesArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $sizesArray']));
            }
            $sizesArray = ArrayHelper::index($sizesArray, 'id');
            
            foreach (\Yii::$app->params['cartArray'] as $purchase) {
                $renderArray['purchasesList'][] = [
                    'purchase'=>$purchase, 
                    'product'=>$productsArray[$purchase['id_product']],
                    'color'=>$colorsArray[$purchase['id_color']],
                    'size'=>$sizesArray[$purchase['id_size']],
                ];
            }
            
            $renderArray['customerArray'] = \Yii::$app->params['customerArray'];
            
            $deliveriesQuery = DeliveriesModel::find();
            $deliveriesQuery->extendSelect(['id', 'name', 'description', 'price']);
            $deliveriesQuery->where(['[[deliveries.id]]'=>\Yii::$app->params['customerArray'][DeliveriesModel::tableName()]]);
            $deliveriesQuery->asArray();
            $deliveriesArray = $deliveriesQuery->one();
            if (!is_array($deliveriesArray) || empty($deliveriesArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'deliveriesArray']));
            }
            $renderArray['deliveriesModel'] = $deliveriesArray;
            
            $paymentsQuery = PaymentsModel::find();
            $paymentsQuery->extendSelect(['id', 'name', 'description']);
            $paymentsQuery->where(['[[payments.id]]'=>\Yii::$app->params['customerArray'][PaymentsModel::tableName()]]);
            $paymentsQuery->asArray();
            $paymentsArray = $paymentsQuery->one();
            if (!is_array($paymentsArray) || empty($paymentsArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'paymentsArray']));
            }
            $renderArray['paymentsModel'] = $paymentsArray;
            
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/cart/index'], 'label'=>\Yii::t('base', 'Check information')];
            
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
    public function actionSend()
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
                $rawNamesModel = \Yii::configure((new NamesModel()), \Yii::$app->params['customerArray'][NamesModel::tableName()]);
                if (!(new NameExistsCreateValidator())->validate($rawNamesModel->name)) {
                    if (!$rawNamesModel->save(false)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'NamesModel::save']));
                    }
                }
                $namesQuery = NamesModel::find();
                $namesQuery->extendSelect(['id', 'name']);
                $namesQuery->where(['[[names.name]]'=>$rawNamesModel->name]);
                $namesQuery->asArray();
                $nameArray = $namesQuery->one();
                if (!is_array($nameArray) || empty($nameArray) || $nameArray['name'] != $rawNamesModel->name) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'nameArray']));
                }
                $name = $nameArray['id'];
                
                $rawSurnamesModel = \Yii::configure((new SurnamesModel()), \Yii::$app->params['customerArray'][SurnamesModel::tableName()]);
                if (!(new SurnameExistsCreateValidator())->validate($rawSurnamesModel->surname)) {
                    if (!$rawSurnamesModel->save(false)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'SurnamesModel::save']));
                    }
                }
                $surnamesQuery = SurnamesModel::find();
                $surnamesQuery->extendSelect(['id', 'surname']);
                $surnamesQuery->where(['[[surnames.surname]]'=>$rawSurnamesModel->surname]);
                $surnamesQuery->asArray();
                $surnameArray = $surnamesQuery->one();
                if (!is_array($surnameArray) || empty($surnameArray) || $surnameArray['surname'] != $rawSurnamesModel->surname) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'surnameArray']));
                }
                $surname = $surnameArray['id'];
                
                $rawEmailsModel = \Yii::configure((new EmailsModel()), \Yii::$app->params['customerArray'][EmailsModel::tableName()]);
                if (!(new EmailExistsCreateValidator())->validate($rawEmailsModel->email)) {
                    if (!$rawEmailsModel->save(false)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'EmailsModel::save']));
                    }
                }
                $emailsQuery = EmailsModel::find();
                $emailsQuery->extendSelect(['id', 'email']);
                $emailsQuery->where(['[[emails.email]]'=>$rawEmailsModel->email]);
                $emailsQuery->asArray();
                $emailArray = $emailsQuery->one();
                if (!is_array($emailArray) || empty($emailArray) || $emailArray['email'] != $rawEmailsModel->email) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'emailArray']));
                }
                $email = $emailArray['id'];
                
                $rawPhonesModel = \Yii::configure((new PhonesModel()), \Yii::$app->params['customerArray'][PhonesModel::tableName()]);
                if (!(new PhoneExistsCreateValidator())->validate($rawPhonesModel->phone)) {
                    if (!$rawPhonesModel->save(false)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'PhonesModel::save']));
                    }
                }
                $phonesQuery = PhonesModel::find();
                $phonesQuery->extendSelect(['id', 'phone']);
                $phonesQuery->where(['[[phones.phone]]'=>$rawPhonesModel->phone]);
                $phonesQuery->asArray();
                $phoneArray = $phonesQuery->one();
                if (!is_array($phoneArray) || empty($phoneArray) || $phoneArray['phone'] != $rawPhonesModel->phone) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'phoneArray']));
                }
                $phone = $phoneArray['id'];
                
                $rawAddressModel = \Yii::configure((new AddressModel()), \Yii::$app->params['customerArray'][AddressModel::tableName()]);
                if (!(new AddressExistsCreateValidator())->validate($rawAddressModel->address)) {
                    if (!$rawAddressModel->save(false)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'AddressModel::save']));
                    }
                }
                $addressQuery = AddressModel::find();
                $addressQuery->extendSelect(['id', 'address']);
                $addressQuery->where(['[[address.address]]'=>$rawAddressModel->address]);
                $addressQuery->asArray();
                $addressArray = $addressQuery->one();
                if (!is_array($addressArray) || empty($addressArray) || $addressArray['address'] != $rawAddressModel->address) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'addressArray']));
                }
                $address = $addressArray['id'];
                
                $rawCitiesModel = \Yii::configure((new CitiesModel()), \Yii::$app->params['customerArray'][CitiesModel::tableName()]);
                if (!(new CityExistsCreateValidator())->validate($rawCitiesModel->city)) {
                    if (!$rawCitiesModel->save(false)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'CitiesModel::save']));
                    }
                }
                $citiesQuery = CitiesModel::find();
                $citiesQuery->extendSelect(['id', 'city']);
                $citiesQuery->where(['[[cities.city]]'=>$rawCitiesModel->city]);
                $citiesQuery->asArray();
                $cityArray = $citiesQuery->one();
                if (!is_array($cityArray) || empty($cityArray) || $cityArray['city'] != $rawCitiesModel->city) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'cityArray']));
                }
                $city = $cityArray['id'];
                
                $rawCountriesModel = \Yii::configure((new CountriesModel()), \Yii::$app->params['customerArray'][CountriesModel::tableName()]);
                if (!(new CountryExistsCreateValidator())->validate($rawCountriesModel->country)) {
                    if (!$rawCountriesModel->save(false)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'CountriesModel::save']));
                    }
                }
                $countriesQuery = CountriesModel::find();
                $countriesQuery->extendSelect(['id', 'country']);
                $countriesQuery->where(['[[countries.country]]'=>$rawCountriesModel->country]);
                $countriesQuery->asArray();
                $countryArray = $countriesQuery->one();
                if (!is_array($countryArray) || empty($countryArray) || $countryArray['country'] != $rawCountriesModel->country) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'countryArray']));
                }
                $country = $countryArray['id'];
                
                $rawPostcodesModel = \Yii::configure((new PostcodesModel()), \Yii::$app->params['customerArray'][PostcodesModel::tableName()]);
                if (!(new PostcodeExistsCreateValidator())->validate($rawPostcodesModel->postcode)) {
                    if (!$rawPostcodesModel->save(false)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'PostcodesModel::save']));
                    }
                }
                $postcodesQuery = PostcodesModel::find();
                $postcodesQuery->extendSelect(['id', 'postcode']);
                $postcodesQuery->where(['[[postcodes.postcode]]'=>$rawPostcodesModel->postcode]);
                $postcodesQuery->asArray();
                $postcodeArray = $postcodesQuery->one();
                if (!is_array($postcodeArray) || empty($postcodeArray) || $postcodeArray['postcode'] != $rawPostcodesModel->postcode) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'postcodeArray']));
                }
                $postcode = $postcodeArray['id'];
                
                $deliveryArray = \Yii::$app->params['customerArray'][DeliveriesModel::tableName()];
                if (!is_array($deliveryArray) || empty($deliveryArray)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'deliveryArray']));
                }
                $delivery = $deliveryArray['id'];
                
                $paymentArray = \Yii::$app->params['customerArray'][PaymentsModel::tableName()];
                if (!is_array($paymentArray) || empty($paymentArray)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'paymentArray']));
                }
                $payment = $paymentArray['id'];
                
                if (\Yii::$app->user->isGuest == false && !empty(\Yii::$app->user->id)) {
                    $user = \Yii::$app->user->id;
                }
                
                $count = PurchasesModel::batchInsert(\Yii::$app->params['cartArray'], $name, $surname, $email, $phone, $address, $city, $country, $postcode, $delivery, $payment, $user ?? 0);
                if ($count < 1) {
                    throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'PurchasesModel::batchInsert']));
                }
                
                $productsQuery = ProductsModel::find();
                $productsQuery->extendSelect(['id', 'name', 'short_description', 'price', 'images', 'seocode']);
                $productsQuery->where(['[[products.id]]'=>ArrayHelper::getColumn(\Yii::$app->params['cartArray'], 'id_product')]);
                $productsQuery->asArray();
                $productsArray = $productsQuery->all();
                if (!is_array($productsArray) || empty($productsArray)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $productsArray']));
                }
                $productsArray = ArrayHelper::index($productsArray, 'id');
                
                $colorsQuery = ColorsModel::find();
                $colorsQuery->extendSelect(['id', 'color']);
                $colorsQuery->where(['[[colors.id]]'=>ArrayHelper::getColumn(\Yii::$app->params['cartArray'], 'id_color')]);
                $colorsQuery->asArray();
                $colorsArray = $colorsQuery->all();
                if (!is_array($colorsArray) || empty($colorsArray)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $colorsArray']));
                }
                $colorsArray = ArrayHelper::index($colorsArray, 'id');
                
                $sizesQuery = SizesModel::find();
                $sizesQuery->extendSelect(['id', 'size']);
                $sizesQuery->where(['[[sizes.id]]'=>ArrayHelper::getColumn(\Yii::$app->params['cartArray'], 'id_size')]);
                $sizesQuery->asArray();
                $sizesArray = $sizesQuery->all();
                if (!is_array($sizesArray) || empty($sizesArray)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $sizesArray']));
                }
                $sizesArray = ArrayHelper::index($sizesArray, 'id');
                
                $deliveriesQuery = DeliveriesModel::find();
                $deliveriesQuery->extendSelect(['id', 'name', 'description', 'price']);
                $deliveriesQuery->where(['[[deliveries.id]]'=>\Yii::$app->params['customerArray'][DeliveriesModel::tableName()]]);
                $deliveriesQuery->asArray();
                $deliveriesArray = $deliveriesQuery->one();
                if (!is_array($deliveriesArray) || empty($deliveriesArray)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'deliveriesArray']));
                }
                
                $paymentsQuery = PaymentsModel::find();
                $paymentsQuery->extendSelect(['id', 'name', 'description']);
                $paymentsQuery->where(['[[payments.id]]'=>\Yii::$app->params['customerArray'][PaymentsModel::tableName()]]);
                $paymentsQuery->asArray();
                $paymentsArray = $paymentsQuery->one();
                if (!is_array($paymentsArray) || empty($paymentsArray)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'paymentsArray']));
                }
            
                foreach (\Yii::$app->params['cartArray'] as $purchase) {
                    $purchasesArray[] = [
                        'purchase'=>$purchase, 
                        'product'=>$productsArray[$purchase['id_product']],
                        'color'=>$colorsArray[$purchase['id_color']],
                        'size'=>$sizesArray[$purchase['id_size']],
                    ];
                }
                
                $sent = MailHelper::send([
                    [
                        'template'=>'@theme/mail/complete-mail.twig', 
                        'setFrom'=>['admin@shop.com'=>'Shop'], 
                        'setTo'=>['timofey@localhost'=>'Timofey'], 
                        'setSubject'=>\Yii::t('base', 'Order confirmation on shop.com'), 
                        'dataForTemplate'=>[
                            'customerArray'=>\Yii::$app->params['customerArray'],
                            'purchasesList'=>$purchasesArray,
                            'deliveryArray'=>$deliveriesArray,
                            'paymentArray'=>$paymentsArray
                        ],
                    ]
                ]);
                if ($sent < 1) {
                    throw new ExecutionException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'MailHelper::send']));
                }
                
                $cartKey = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
                $customerKey = HashHelper::createHash([\Yii::$app->params['customerKey'], \Yii::$app->user->id ?? '']);
                SessionHelper::remove([$cartKey, $customerKey]);
                \Yii::$app->params['cartArray'] = [];
                \Yii::$app->params['customerArray'] = [];
                
                $transaction->commit();
                
            } catch (\Throwable $t) {
                $transaction->rollBack();
                throw $t;
            }
            
            return $this->redirect(Url::to(['/cart/complete']));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Подтверждает успешную отправку
     * @return string
     */
    public function actionComplete()
    {
        try {
            $renderArray = InstancesHelper::getInstances();
            if (!is_array($renderArray) || empty($renderArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray']));
            }
            
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/cart/index'], 'label'=>\Yii::t('base', 'Cart')];
            
            Url::remember(Url::current(), 'shop');
            
            return $this->render('complete.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    
    
    public function behaviors()
    {
        return [
            [
                'class'=>'app\filters\CurrencyFilter',
                'only'=>['index', 'set', 'customer', 'check', 'send', 'complete']
            ],
            [
                'class'=>'app\filters\CustomerFilter',
                'only'=>['customer', 'check', 'send']
            ],
            [
                'class'=>'app\filters\CartFilter',
            ],
        ];
    }
}
