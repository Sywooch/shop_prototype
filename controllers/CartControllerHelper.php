<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\db\Transaction;
use app\controllers\AbstractControllerHelper;
use app\exceptions\ExceptionsTrait;
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
    SessionHelper};
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
 * Коллекция сервис-методов CartController
 */
class CartControllerHelper extends AbstractControllerHelper
{
    /**
     * @var object NamesModel
     */
    private static $_rawNamesModel;
    /**
     * @var object SurnamesModel
     */
    private static $_rawSurnamesModel;
    /**
     * @var object EmailsModel
     */
    private static $_rawEmailsModel;
    /**
     * @var object PhonesModel
     */
    private static $_rawPhonesModel;
    /**
     * @var object AddressModel
     */
    private static $_rawAddressModel;
    /**
     * @var object CitiesModel
     */
    private static $_rawCitiesModel;
    /**
     * @var object CountriesModel
     */
    private static $_rawCountriesModel;
    /**
     * @var object PostcodesModel
     */
    private static $_rawPostcodesModel;
    /**
     * @var object UsersModel
     */
    private static $_rawUsersModel;
    /**
     * @var object DeliveriesModel
     */
    private static $_rawDeliveriesModel;
    /**
     * @var object PaymentsModel
     */
    private static $_rawPaymentsModel;
    
    /**
     * Конструирует данные для CartController::actionIndex()
     * @return array
     */
    public static function indexGet(): array
    {
        try {
            $renderArray = InstancesHelper::getInstances();
            
            $productsArray = self::getProducts(true);
            
            foreach (\Yii::$app->params['cartArray'] as $hash=>$purchase) {
                $renderArray['purchasesList'][$hash] = [
                    'purchase'=>\Yii::configure((new PurchasesModel()), $purchase), 
                    'product'=>$productsArray[$purchase['id_product']],
                ];
            }
            
            self::breadcrumbs();
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает POST запрос для CartController::actionSet()
     */
    public static function setPost()
    {
        try {
            $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_ADD_TO_CART]);
            
            if ($rawPurchasesModel->load(\Yii::$app->request->post())) {
                if ($rawPurchasesModel->validate()) {
                    self::write($rawPurchasesModel->toArray());
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает Ajax запрос для CartController::actionSet()
     * @return bool
     */
    public static function setAjax(): string
    {
        try {
            $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_ADD_TO_CART]);
            
            if ($rawPurchasesModel->load(\Yii::$app->request->post())) {
                if ($rawPurchasesModel->validate()) {
                    self::write($rawPurchasesModel->toArray());
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return CartWidget::widget();
                }
            }
            
            return '';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает POST запрос для CartController::actionClean()
     */
    public static function cleanPost()
    {
        try {
            $cartKey = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
            $customerKey = HashHelper::createHash([\Yii::$app->params['customerKey'], \Yii::$app->user->id ?? '']);
            SessionHelper::remove([$cartKey, $customerKey]);
            if (SessionHelper::has($cartKey) === false && SessionHelper::has($customerKey) === false) {
                \Yii::$app->params['cartArray'] = [];
                \Yii::$app->params['customerArray'] = [];
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает POST запрос для CartController::actionUpdate()
     */
    public static function updatePost()
    {
        try {
            $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_ADD_TO_CART]);
            
            if ($rawPurchasesModel->load(\Yii::$app->request->post()) && !empty(\Yii::$app->request->post('hash'))) {
                if ($rawPurchasesModel->validate()) {
                    $hash = \Yii::$app->request->post('hash');
                    if (array_key_exists($hash, (\Yii::$app->params['cartArray']))) {
                        unset(\Yii::$app->params['cartArray'][$hash]);
                        self::write($rawPurchasesModel->toArray());
                    }
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает POST запрос для CartController::actionDelete()
     */
    public static function deletePost()
    {
        try {
            if (!empty(\Yii::$app->request->post('hash'))) {
                $hash = \Yii::$app->request->post('hash');
                if (array_key_exists($hash, (\Yii::$app->params['cartArray']))) {
                    unset(\Yii::$app->params['cartArray'][$hash]);
                    if (empty(\Yii::$app->params['cartArray'])) {
                        self::cleanPost();
                    } else {
                        $cartKey = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
                        SessionHelper::write($cartKey, \Yii::$app->params['cartArray']);
                    }
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует данные для CartController::actionCustomer()
     * @return array
     */
    public static function customerGet(): array
    {
        try {
            $renderArray = InstancesHelper::getInstances();
            
            self::models();
            
            if (\Yii::$app->user->isGuest == false) {
                $usersModel = self::getUserPlus();
            }
            
            if (!empty(\Yii::$app->params['customerArray'][NamesModel::tableName()])) {
                self::$_rawNamesModel = \Yii::configure(self::$_rawNamesModel, \Yii::$app->params['customerArray'][NamesModel::tableName()]);
            } elseif (\Yii::$app->user->isGuest == false && !empty($usersModel['userName'])) {
                self::$_rawNamesModel = \Yii::configure(self::$_rawNamesModel, ['name'=>$usersModel['userName']]);
            }
            
            if (!empty(\Yii::$app->params['customerArray'][SurnamesModel::tableName()])) {
                self::$_rawSurnamesModel = \Yii::configure(self::$_rawSurnamesModel, \Yii::$app->params['customerArray'][SurnamesModel::tableName()]);
            } elseif (\Yii::$app->user->isGuest == false && !empty($usersModel['userSurname'])) {
                self::$_rawSurnamesModel = \Yii::configure(self::$_rawSurnamesModel, ['surname'=>$usersModel['userSurname']]);
            }
            
            if (\Yii::$app->user->isGuest == false && !empty($usersModel['userEmail'])) {
                self::$_rawEmailsModel = \Yii::configure(self::$_rawEmailsModel, ['email'=>$usersModel['userEmail']]);
            } elseif (!empty(\Yii::$app->params['customerArray'][EmailsModel::tableName()])) {
                self::$_rawEmailsModel = \Yii::configure(self::$_rawEmailsModel, \Yii::$app->params['customerArray'][EmailsModel::tableName()]);
            }
            
            if (!empty(\Yii::$app->params['customerArray'][PhonesModel::tableName()])) {
                self::$_rawPhonesModel = \Yii::configure(self::$_rawPhonesModel, \Yii::$app->params['customerArray'][PhonesModel::tableName()]);
            } elseif (\Yii::$app->user->isGuest == false && !empty($usersModel['userPhone'])) {
                self::$_rawPhonesModel = \Yii::configure(self::$_rawPhonesModel, ['phone'=>$usersModel['userPhone']]);
            }
            
            if (!empty(\Yii::$app->params['customerArray'][AddressModel::tableName()])) {
                self::$_rawAddressModel = \Yii::configure(self::$_rawAddressModel, \Yii::$app->params['customerArray'][AddressModel::tableName()]);
            } elseif (\Yii::$app->user->isGuest == false && !empty($usersModel['userAddress'])) {
                self::$_rawAddressModel = \Yii::configure(self::$_rawAddressModel, ['address'=>$usersModel['userAddress']]);
            }
            
            if (!empty(\Yii::$app->params['customerArray'][CitiesModel::tableName()])) {
                self::$_rawCitiesModel = \Yii::configure(self::$_rawCitiesModel, \Yii::$app->params['customerArray'][CitiesModel::tableName()]);
            } elseif (\Yii::$app->user->isGuest == false && !empty($usersModel['userCity'])) {
                self::$_rawCitiesModel = \Yii::configure(self::$_rawCitiesModel, ['city'=>$usersModel['userCity']]);
            }
            
            if (!empty(\Yii::$app->params['customerArray'][CountriesModel::tableName()])) {
                self::$_rawCountriesModel = \Yii::configure(self::$_rawCountriesModel, \Yii::$app->params['customerArray'][CountriesModel::tableName()]);
            } elseif (\Yii::$app->user->isGuest == false && !empty($usersModel['userCountry'])) {
                self::$_rawCountriesModel = \Yii::configure(self::$_rawCountriesModel, ['country'=>$usersModel['userCountry']]);
            }
            
            if (!empty(\Yii::$app->params['customerArray'][PostcodesModel::tableName()])) {
                self::$_rawPostcodesModel = \Yii::configure(self::$_rawPostcodesModel, \Yii::$app->params['customerArray'][PostcodesModel::tableName()]);
            } elseif (\Yii::$app->user->isGuest == false && !empty($usersModel['userPostcode'])) {
                self::$_rawPostcodesModel = \Yii::configure(self::$_rawPostcodesModel, ['postcode'=>$usersModel['userPostcode']]);
            }
            
            if (!empty(\Yii::$app->params['customerArray'][DeliveriesModel::tableName()])) {
                self::$_rawDeliveriesModel = \Yii::configure(self::$_rawDeliveriesModel, \Yii::$app->params['customerArray'][DeliveriesModel::tableName()]);
            }
            
            if (!empty(\Yii::$app->params['customerArray'][PaymentsModel::tableName()])) {
                self::$_rawPaymentsModel = \Yii::configure(self::$_rawPaymentsModel, \Yii::$app->params['customerArray'][PaymentsModel::tableName()]);
            }
            
            if (!empty(\Yii::$app->params['customerArray'][UsersModel::tableName()])) {
                self::$_rawUsersModel = \Yii::configure(self::$_rawUsersModel, \Yii::$app->params['customerArray'][UsersModel::tableName()]);
            }
            
            $renderArray['namesModel'] = self::$_rawNamesModel;
            $renderArray['surnamesModel'] = self::$_rawSurnamesModel;
            $renderArray['emailsModel'] = self::$_rawEmailsModel;
            $renderArray['phonesModel'] = self::$_rawPhonesModel;
            $renderArray['addressModel'] = self::$_rawAddressModel;
            $renderArray['citiesModel'] = self::$_rawCitiesModel;
            $renderArray['countriesModel'] = self::$_rawCountriesModel;
            $renderArray['postcodesModel'] = self::$_rawPostcodesModel;
            $renderArray['usersModel'] = self::$_rawUsersModel;
            $renderArray['deliveriesModel'] = self::$_rawDeliveriesModel;
            $renderArray['paymentsModel'] = self::$_rawPaymentsModel;
            
            if (\Yii::$app->params['customerArray']['dataChange']) {
                $renderArray['dataChange'] = true;
            }
            
            if (\Yii::$app->params['customerArray']['createAccount']) {
                $renderArray['createAccount'] = true;
            }
            
            $renderArray['deliveriesList'] = self::getDeliveriesList();
            $renderArray['paymentsList'] = self::getPaymentsList();
            
            self::breadcrumbsCustomer();
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает POST запрос для CartController::actionCustomer()
     * @return bool
     */
    public static function customerPost(): bool
    {
        try {
            self::models();
            
            if (self::$_rawNamesModel->load(\Yii::$app->request->post()) && self::$_rawSurnamesModel->load(\Yii::$app->request->post()) && self::$_rawEmailsModel->load(\Yii::$app->request->post()) && self::$_rawPhonesModel->load(\Yii::$app->request->post()) && self::$_rawAddressModel->load(\Yii::$app->request->post()) && self::$_rawCitiesModel->load(\Yii::$app->request->post()) && self::$_rawCountriesModel->load(\Yii::$app->request->post()) && self::$_rawPostcodesModel->load(\Yii::$app->request->post()) && self::$_rawUsersModel->load(\Yii::$app->request->post()) && self::$_rawDeliveriesModel->load(\Yii::$app->request->post()) && self::$_rawPaymentsModel->load(\Yii::$app->request->post())) {
                if (self::$_rawNamesModel->validate() && self::$_rawSurnamesModel->validate() && self::$_rawEmailsModel->validate() && self::$_rawPhonesModel->validate() && self::$_rawAddressModel->validate() && self::$_rawCitiesModel->validate() && self::$_rawCountriesModel->validate() && self::$_rawPostcodesModel->validate() && self::$_rawUsersModel->validate() && self::$_rawDeliveriesModel->validate() && self::$_rawPaymentsModel->validate()) {
                    \Yii::$app->params['customerArray'][NamesModel::tableName()] = self::$_rawNamesModel->toArray();
                    \Yii::$app->params['customerArray'][SurnamesModel::tableName()] = self::$_rawSurnamesModel->toArray();
                    \Yii::$app->params['customerArray'][EmailsModel::tableName()] = self::$_rawEmailsModel->toArray();
                    \Yii::$app->params['customerArray'][PhonesModel::tableName()] = self::$_rawPhonesModel->toArray();
                    \Yii::$app->params['customerArray'][AddressModel::tableName()] = self::$_rawAddressModel->toArray();
                    \Yii::$app->params['customerArray'][CitiesModel::tableName()] = self::$_rawCitiesModel->toArray();
                    \Yii::$app->params['customerArray'][CountriesModel::tableName()] = self::$_rawCountriesModel->toArray();
                    \Yii::$app->params['customerArray'][PostcodesModel::tableName()] = self::$_rawPostcodesModel->toArray();
                    \Yii::$app->params['customerArray'][UsersModel::tableName()] = self::$_rawUsersModel->toArray([], ['password']);
                    \Yii::$app->params['customerArray'][DeliveriesModel::tableName()] = self::$_rawDeliveriesModel->toArray();
                    \Yii::$app->params['customerArray'][PaymentsModel::tableName()] = self::$_rawPaymentsModel->toArray();
                    \Yii::$app->params['customerArray']['dataChange'] = \Yii::$app->request->post('dataChange');
                    \Yii::$app->params['customerArray']['createAccount'] = \Yii::$app->request->post('createAccount');
                    
                    $hash = HashHelper::createHash([\Yii::$app->params['customerKey'], \Yii::$app->user->id ?? '']);
                    SessionHelper::write($hash, \Yii::$app->params['customerArray']);
                    
                    return true;
                }
            }
            
            return false;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует данные для CartController::actionCheck()
     * @return array
     */
    public static function checkGet(): array
    {
        try {
            $renderArray = InstancesHelper::getInstances();
            
            $productsArray = self::getProducts();
            $colorsArray = self::getColors();
            $sizesArray = self::getSizes();
            
            foreach (\Yii::$app->params['cartArray'] as $purchase) {
                $renderArray['purchasesList'][] = [
                    'purchase'=>$purchase, 
                    'product'=>$productsArray[$purchase['id_product']],
                    'color'=>$colorsArray[$purchase['id_color']],
                    'size'=>$sizesArray[$purchase['id_size']],
                ];
            }
            
            $renderArray['customerArray'] = \Yii::$app->params['customerArray'];
            
            $renderArray['deliveriesModel'] = self::getDelivery();
            $renderArray['paymentsModel'] = self::getPayment();
            
            self::breadcrumbsCheck();
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует данные для CartController::actionSend()
     */
    public static function sendPost()
    {
        try {
            $renderArray = InstancesHelper::getInstances();
            
            $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
            
             try {
                $rawNamesModel = \Yii::configure((new NamesModel()), \Yii::$app->params['customerArray'][NamesModel::tableName()]);
                if (!(new NameExistsCreateValidator())->validate($rawNamesModel['name'])) {
                    if (!$rawNamesModel->save(false)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'NamesModel::save']));
                    }
                }
                $namesQuery = NamesModel::find();
                $namesQuery->extendSelect(['id', 'name']);
                $namesQuery->where(['[[names.name]]'=>$rawNamesModel['name']]);
                $namesQuery->asArray();
                $nameArray = $namesQuery->one();
                $name = $nameArray['id'];
                
                $rawSurnamesModel = \Yii::configure((new SurnamesModel()), \Yii::$app->params['customerArray'][SurnamesModel::tableName()]);
                if (!(new SurnameExistsCreateValidator())->validate($rawSurnamesModel['surname'])) {
                    if (!$rawSurnamesModel->save(false)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'SurnamesModel::save']));
                    }
                }
                $surnamesQuery = SurnamesModel::find();
                $surnamesQuery->extendSelect(['id', 'surname']);
                $surnamesQuery->where(['[[surnames.surname]]'=>$rawSurnamesModel['surname']]);
                $surnamesQuery->asArray();
                $surnameArray = $surnamesQuery->one();
                $surname = $surnameArray['id'];
                
                $rawEmailsModel = \Yii::configure((new EmailsModel()), \Yii::$app->params['customerArray'][EmailsModel::tableName()]);
                self::saveEmail($rawEmailsModel);
                $emailsQuery = EmailsModel::find();
                $emailsQuery->extendSelect(['id', 'email']);
                $emailsQuery->where(['[[emails.email]]'=>$rawEmailsModel['email']]);
                $emailsQuery->asArray();
                $emailArray = $emailsQuery->one();
                $email = $emailArray['id'];
                
                $rawPhonesModel = \Yii::configure((new PhonesModel()), \Yii::$app->params['customerArray'][PhonesModel::tableName()]);
                if (!(new PhoneExistsCreateValidator())->validate($rawPhonesModel['phone'])) {
                    if (!$rawPhonesModel->save(false)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'PhonesModel::save']));
                    }
                }
                $phonesQuery = PhonesModel::find();
                $phonesQuery->extendSelect(['id', 'phone']);
                $phonesQuery->where(['[[phones.phone]]'=>$rawPhonesModel['phone']]);
                $phonesQuery->asArray();
                $phoneArray = $phonesQuery->one();
                $phone = $phoneArray['id'];
                
                $rawAddressModel = \Yii::configure((new AddressModel()), \Yii::$app->params['customerArray'][AddressModel::tableName()]);
                if (!(new AddressExistsCreateValidator())->validate($rawAddressModel['address'])) {
                    if (!$rawAddressModel->save(false)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'AddressModel::save']));
                    }
                }
                $addressQuery = AddressModel::find();
                $addressQuery->extendSelect(['id', 'address']);
                $addressQuery->where(['[[address.address]]'=>$rawAddressModel['address']]);
                $addressQuery->asArray();
                $addressArray = $addressQuery->one();
                $address = $addressArray['id'];
                
                $rawCitiesModel = \Yii::configure((new CitiesModel()), \Yii::$app->params['customerArray'][CitiesModel::tableName()]);
                if (!(new CityExistsCreateValidator())->validate($rawCitiesModel['city'])) {
                    if (!$rawCitiesModel->save(false)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'CitiesModel::save']));
                    }
                }
                $citiesQuery = CitiesModel::find();
                $citiesQuery->extendSelect(['id', 'city']);
                $citiesQuery->where(['[[cities.city]]'=>$rawCitiesModel['city']]);
                $citiesQuery->asArray();
                $cityArray = $citiesQuery->one();
                $city = $cityArray['id'];
                
                $rawCountriesModel = \Yii::configure((new CountriesModel()), \Yii::$app->params['customerArray'][CountriesModel::tableName()]);
                if (!(new CountryExistsCreateValidator())->validate($rawCountriesModel['country'])) {
                    if (!$rawCountriesModel->save(false)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'CountriesModel::save']));
                    }
                }
                $countriesQuery = CountriesModel::find();
                $countriesQuery->extendSelect(['id', 'country']);
                $countriesQuery->where(['[[countries.country]]'=>$rawCountriesModel['country']]);
                $countriesQuery->asArray();
                $countryArray = $countriesQuery->one();
                $country = $countryArray['id'];
                
                $rawPostcodesModel = \Yii::configure((new PostcodesModel()), \Yii::$app->params['customerArray'][PostcodesModel::tableName()]);
                if (!(new PostcodeExistsCreateValidator())->validate($rawPostcodesModel['postcode'])) {
                    if (!$rawPostcodesModel->save(false)) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'PostcodesModel::save']));
                    }
                }
                $postcodesQuery = PostcodesModel::find();
                $postcodesQuery->extendSelect(['id', 'postcode']);
                $postcodesQuery->where(['[[postcodes.postcode]]'=>$rawPostcodesModel['postcode']]);
                $postcodesQuery->asArray();
                $postcodeArray = $postcodesQuery->one();
                $postcode = $postcodeArray['id'];
                
                if (\Yii::$app->params['customerArray'][UsersModel::tableName()]) {
                    $rawUsersModel = \Yii::configure((new UsersModel()), \Yii::$app->params['customerArray'][UsersModel::tableName()]);
                    if (!empty($rawUsersModel->password)) {
                        $rawUsersModel->id_email = $email;
                        $rawUsersModel->password = password_hash($rawUsersModel->password, PASSWORD_DEFAULT);
                        $rawUsersModel->id_name = $name;
                        $rawUsersModel->id_surname = $surname;
                        $rawUsersModel->id_phone = $phone;
                        $rawUsersModel->id_address = $address;
                        $rawUsersModel->id_city = $city;
                        $rawUsersModel->id_country = $country;
                        $rawUsersModel->id_postcode = $postcode;
                        if (!$rawUsersModel->save(false)) {
                            throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'UsersModel::save']));
                        }
                        $usersQuery = UsersModel::find();
                        $usersQuery->extendSelect(['id', 'id_email']);
                        $usersQuery->where(['[[users.id_email]]'=>$email]);
                        $usersQuery->asArray();
                        $usersModel = $usersQuery->one();
                        $user = $usersModel['id'];
                    }
                }
                
                if (\Yii::$app->user->isGuest == false && !empty(\Yii::$app->user->id)) {
                    $user = \Yii::$app->user->id;
                    if (\Yii::$app->params['customerArray']['dataChange']) {
                        \Yii::$app->user->identity->id_name = $name;
                        \Yii::$app->user->identity->id_surname = $surname;
                        \Yii::$app->user->identity->id_phone = $phone;
                        \Yii::$app->user->identity->id_address = $address;
                        \Yii::$app->user->identity->id_city = $city;
                        \Yii::$app->user->identity->id_country = $country;
                        \Yii::$app->user->identity->id_postcode = $postcode;
                        if (!\Yii::$app->user->identity->save(false)) {
                            throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'UsersModel::save']));
                        }
                    }
                }
                
                $delivery =  \Yii::$app->params['customerArray'][DeliveriesModel::tableName()]['id'];
                
                $payment = \Yii::$app->params['customerArray'][PaymentsModel::tableName()]['id'];
                
                $count = PurchasesModel::batchInsert(\Yii::$app->params['cartArray'], $name, $surname, $email, $phone, $address, $city, $country, $postcode, $delivery, $payment, $user ?? 0);
                if ($count < 1) {
                    throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'PurchasesModel::batchInsert']));
                }
                
                $productsArray = self::getProducts();
                $colorsArray = self::getColors();
                $sizesArray = self::getSizes();
                $deliveriesArray = self::getDelivery();
                $paymentsArray = self::getPayment();
                
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
                        'from'=>['admin@shop.com'=>'Shop'], 
                        'to'=>['timofey@localhost'=>'Timofey'], 
                        'subject'=>\Yii::t('base', 'Order confirmation on shop.com'), 
                        'templateData'=>[
                            'customerArray'=>\Yii::$app->params['customerArray'],
                            'purchasesList'=>$purchasesArray,
                            'deliveryArray'=>$deliveriesArray,
                            'paymentArray'=>$paymentsArray,
                            'createAccount'=>\Yii::$app->params['customerArray']['createAccount'],
                            'email'=>$rawEmailsModel['email']
                        ],
                    ]
                ]);
                if ($sent < 1) {
                    throw new ExecutionException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'MailHelper::send']));
                }
                
                self::cleanPost();
                
                $transaction->commit();
                
            } catch (\Throwable $t) {
                $transaction->rollBack();
                throw $t;
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует данные для CartController::actionComplete()
     * @return array
     */
    public static function completeGet(): array
    {
        try {
            $renderArray = InstancesHelper::getInstances();
            
            self::breadcrumbs();
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Получает из БД товары, находящиеся в корзине 
     * @param bool $with флаг, определяющий необходимость 
     * загрузки связанных данных
     * @return array
     */
    private static function getProducts($with=false): array
    {
        try {
            $productsQuery = ProductsModel::find();
            $productsQuery->extendSelect(['id', 'name', 'short_description', 'price', 'images', 'seocode']);
            $productsQuery->where(['[[products.id]]'=>ArrayHelper::getColumn(\Yii::$app->params['cartArray'], 'id_product')]);
            if ($with) {
                $productsQuery->with(['colors', 'sizes']);
            }
            $productsQuery->asArray();
            $productsArray = $productsQuery->all();
            $productsArray = ArrayHelper::index($productsArray, 'id');
            
            return $productsArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Получает из БД цвета товаров, находящихся в корзине 
     * @return array
     */
    private static function getColors(): array
    {
        try {
            $colorsQuery = ColorsModel::find();
            $colorsQuery->extendSelect(['id', 'color']);
            $colorsQuery->where(['[[colors.id]]'=>ArrayHelper::getColumn(\Yii::$app->params['cartArray'], 'id_color')]);
            $colorsQuery->asArray();
            $colorsArray = $colorsQuery->all();
            $colorsArray = ArrayHelper::index($colorsArray, 'id');
            
            return $colorsArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Получает из БД размеры товаров, находящихся в корзине 
     * @return array
     */
    private static function getSizes(): array
    {
        try {
            $sizesQuery = SizesModel::find();
            $sizesQuery->extendSelect(['id', 'size']);
            $sizesQuery->where(['[[sizes.id]]'=>ArrayHelper::getColumn(\Yii::$app->params['cartArray'], 'id_size')]);
            $sizesQuery->asArray();
            $sizesArray = $sizesQuery->all();
            $sizesArray = ArrayHelper::index($sizesArray, 'id');
            
            return $sizesArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Получает из БД информацию о выбранной доставке 
     * @return array
     */
    private static function getDelivery(): array
    {
        try {
            $deliveriesQuery = DeliveriesModel::find();
            $deliveriesQuery->extendSelect(['id', 'name', 'description', 'price']);
            $deliveriesQuery->where(['[[deliveries.id]]'=>\Yii::$app->params['customerArray'][DeliveriesModel::tableName()]]);
            $deliveriesQuery->asArray();
            $deliveriesArray = $deliveriesQuery->one();
            
            return $deliveriesArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Получает из БД информацию о выбранной форме оплаты 
     * @return array
     */
    private static function getPayment(): array
    {
        try {
            $paymentsQuery = PaymentsModel::find();
            $paymentsQuery->extendSelect(['id', 'name', 'description']);
            $paymentsQuery->where(['[[payments.id]]'=>\Yii::$app->params['customerArray'][PaymentsModel::tableName()]]);
            $paymentsQuery->asArray();
            $paymentsArray = $paymentsQuery->one();
            
            return $paymentsArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Получает из БД информацию о пользователе вместе 
     * со всеми связанными данными
     * @return array
     */
    private static function getUserPlus(): array
    {
        try {
            $usersQuery = UsersModel::find();
            $usersQuery->extendSelect(['id', 'id_name', 'id_surname', 'id_email', 'id_phone', 'id_address', 'id_city', 'id_country', 'id_postcode']);
            $usersQuery->addSelect(['[[userName]]'=>'[[names.name]]', '[[userSurname]]'=>'[[surnames.surname]]', '[[userEmail]]'=>'[[emails.email]]', '[[userPhone]]'=>'[[phones.phone]]', '[[userAddress]]'=>'[[address.address]]', '[[userCity]]'=>'[[cities.city]]', '[[userCountry]]'=>'[[countries.country]]', '[[userPostcode]]'=>'[[postcodes.postcode]]']);
            $usersQuery->innerJoin('{{emails}}', '[[emails.id]]=[[users.id_email]]');
            $usersQuery->leftJoin('{{names}}', '[[names.id]]=[[users.id_name]]');
            $usersQuery->leftJoin('{{surnames}}', '[[surnames.id]]=[[users.id_surname]]');
            $usersQuery->leftJoin('{{phones}}', '[[phones.id]]=[[users.id_phone]]');
            $usersQuery->leftJoin('{{address}}', '[[address.id]]=[[users.id_address]]');
            $usersQuery->leftJoin('{{cities}}', '[[cities.id]]=[[users.id_city]]');
            $usersQuery->leftJoin('{{countries}}', '[[countries.id]]=[[users.id_country]]');
            $usersQuery->leftJoin('{{postcodes}}', '[[postcodes.id]]=[[users.id_postcode]]');
            $usersQuery->where(['[[users.id]]'=>\Yii::$app->user->id]);
            $usersQuery->asArray();
            $usersModel = $usersQuery->one();
            
            return $usersModel;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Пишет в сессию массив данных о товарах в корзине
     * @param array $purchaseArray массив данных для записи в сессию
     * @return bool
     */
    private static function write(array $purchaseArray): bool
    {
        try {
            $clonePurchaseArray = $purchaseArray;
            unset($clonePurchaseArray['quantity']);
            $hash = HashHelper::createHash($clonePurchaseArray);
            
            if (array_key_exists($hash, \Yii::$app->params['cartArray'])) {
                \Yii::$app->params['cartArray'][$hash]['quantity'] += $purchaseArray['quantity'];
            } else {
                \Yii::$app->params['cartArray'][$hash] = $purchaseArray;
            }
            
            $cartKey = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
            SessionHelper::write($cartKey, \Yii::$app->params['cartArray']);
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует модели
     */
    private static function models()
    {
        try {
            if (empty(self::$_rawNamesModel)) {
                self::$_rawNamesModel = new NamesModel(['scenario'=>NamesModel::GET_FROM_ORDER]);
            }
            if (empty(self::$_rawSurnamesModel)) {
                self::$_rawSurnamesModel = new SurnamesModel(['scenario'=>SurnamesModel::GET_FROM_ORDER]);
            }
            if (empty(self::$_rawEmailsModel)) {
                self::$_rawEmailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_ORDER]);
            }
            if (empty(self::$_rawPhonesModel)) {
                self::$_rawPhonesModel = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_ORDER]);
            }
            if (empty(self::$_rawAddressModel)) {
                self::$_rawAddressModel = new AddressModel(['scenario'=>AddressModel::GET_FROM_ORDER]);
            }
            if (empty(self::$_rawCitiesModel)) {
                self::$_rawCitiesModel = new CitiesModel(['scenario'=>CitiesModel::GET_FROM_ORDER]);
            }
            if (empty(self::$_rawCountriesModel)) {
                self::$_rawCountriesModel = new CountriesModel(['scenario'=>CountriesModel::GET_FROM_ORDER]);
            }
            if (empty(self::$_rawPostcodesModel)) {
                self::$_rawPostcodesModel = new PostcodesModel(['scenario'=>PostcodesModel::GET_FROM_ORDER]);
            }
            if (empty(self::$_rawUsersModel)) {
                self::$_rawUsersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_ORDER]);
            }
            if (empty(self::$_rawDeliveriesModel)) {
                self::$_rawDeliveriesModel = new DeliveriesModel(['scenario'=>DeliveriesModel::GET_FROM_ORDER]);
            }
            if (empty(self::$_rawPaymentsModel)) {
                self::$_rawPaymentsModel = new PaymentsModel(['scenario'=>PaymentsModel::GET_FROM_ORDER]);
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет массив $renderArray данными DeliveriesModel 
     * @return array
     */
    private static function getDeliveriesList(): array
    {
        try {
            $deliveriesQuery = DeliveriesModel::find();
            $deliveriesQuery->extendSelect(['id', 'name', 'description', 'price']);
            $deliveriesQuery->asArray();
            $deliveriesArray = $deliveriesQuery->all();
            ArrayHelper::multisort($deliveriesArray, 'name', SORT_ASC);
            
            return $deliveriesArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет массив $renderArray данными PaymentsModel 
     * @return array
     */
    private static function getPaymentsList(): array
    {
        try {
            $paymentsQuery = PaymentsModel::find();
            $paymentsQuery->extendSelect(['id', 'name', 'description']);
            $paymentsQuery->asArray();
            $paymentsArray = $paymentsQuery->all();
            ArrayHelper::multisort($paymentsArray, 'name', SORT_ASC);
            
            return $paymentsArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет данными массив \Yii::$app->params['breadcrumbs'] 
     */
    private static function breadcrumbs()
    {
        try {
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/cart/index'], 'label'=>\Yii::t('base', 'Cart')];
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет данными массив \Yii::$app->params['breadcrumbs'] 
     */
    private static function breadcrumbsCustomer()
    {
        try {
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/cart/index'], 'label'=>\Yii::t('base', 'Customer information')];
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет данными массив \Yii::$app->params['breadcrumbs'] 
     */
    private static function breadcrumbsCheck()
    {
        try {
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/cart/index'], 'label'=>\Yii::t('base', 'Check information')];
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
