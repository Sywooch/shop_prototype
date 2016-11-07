<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\db\Transaction;
use app\controllers\AbstractControllerHelper;
use app\exceptions\ExceptionsTrait;
use app\models\{AbstractBaseModel,
    AddressModel,
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
            
            self::customerTuning($usersModel ?? []);
            
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
            $renderArray['dataChange'] = !empty(\Yii::$app->params['customerArray']['dataChange']) ? true : false;
            $renderArray['createAccount'] = !empty(\Yii::$app->params['customerArray']['createAccount']) ? true : false;
            $renderArray['deliveriesList'] = self::getDeliveries();
            $renderArray['paymentsList'] = self::getPayments();
            
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
                    
                    $customerKey = HashHelper::createHash([\Yii::$app->params['customerKey'], \Yii::$app->user->id ?? '']);
                    SessionHelper::write($customerKey, \Yii::$app->params['customerArray']);
                    
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
                $resultArray = self::purchsePrepare();
                
                $count = PurchasesModel::batchInsert(\Yii::$app->params['cartArray'], $resultArray['name'], $resultArray['surname'], $resultArray['email_id'], $resultArray['phone'], $resultArray['address'], $resultArray['city'], $resultArray['country'], $resultArray['postcode'], $resultArray['delivery'], $resultArray['payment'], $resultArray['user'] ?? 0);
                if ($count < 1) {
                    throw new ErrorException(ExceptionsTrait::methodError('PurchasesModel::batchInsert'));
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
                            'email'=>$resultArray['email']
                        ],
                    ]
                ]);
                if ($sent < 1) {
                    throw new ErrorException(ExceptionsTrait::methodError('MailHelper::send'));
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
     * Конфигурирует модели данными пользователя
     * @param array $usersModel массив данных пользователя
     */
    public static function customerTuning(array $usersModel)
    {
        try {
            self::$_rawNamesModel = self::customerConfigObj(self::$_rawNamesModel, $usersModel, 'name', 'userName');
            self::$_rawSurnamesModel = self::customerConfigObj(self::$_rawSurnamesModel, $usersModel, 'surname', 'userSurname');
            
            if (\Yii::$app->user->isGuest == false && !empty($usersModel['userEmail'])) {
                self::$_rawEmailsModel = \Yii::configure(self::$_rawEmailsModel, ['email'=>$usersModel['userEmail']]);
            } elseif (!empty(\Yii::$app->params['customerArray'][EmailsModel::tableName()])) {
                self::$_rawEmailsModel = self::customerConfigObj(self::$_rawEmailsModel);
            }
            
            self::$_rawPhonesModel = self::customerConfigObj(self::$_rawPhonesModel, $usersModel, 'phone', 'userPhone');
            self::$_rawAddressModel = self::customerConfigObj(self::$_rawAddressModel, $usersModel, 'address', 'userAddress');
            self::$_rawCitiesModel = self::customerConfigObj(self::$_rawCitiesModel, $usersModel, 'city', 'userCity');
            self::$_rawCountriesModel = self::customerConfigObj(self::$_rawCountriesModel, $usersModel, 'country', 'userCountry');
            self::$_rawPostcodesModel = self::customerConfigObj(self::$_rawPostcodesModel, $usersModel, 'postcode', 'userPostcode');
            self::$_rawDeliveriesModel = self::customerConfigObj(self::$_rawDeliveriesModel);
            self::$_rawPaymentsModel = self::customerConfigObj(self::$_rawPaymentsModel);
            self::$_rawUsersModel = self::customerConfigObj(self::$_rawUsersModel);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Подготавливает данные для сохранения покупки
     * @return array
     */
    public static function purchsePrepare(): array
    {
        try {
            $resultArray = [];
            
            $rawNamesModel = self::customerConfig(NamesModel::class);
            self::saveCheckName($rawNamesModel);
            $namesModel = self::getName($rawNamesModel['name'], true);
            $resultArray['name'] = $namesModel['id'];
            
            $rawSurnamesModel = self::customerConfig(SurnamesModel::class);
            self::saveCheckSurname($rawSurnamesModel);
            $surnamesModel = self::getSurname($rawSurnamesModel['surname'], true);
            $resultArray['surname'] = $surnamesModel['id'];
            
            $rawEmailsModel = self::customerConfig(EmailsModel::class);
            self::saveCheckEmail($rawEmailsModel);
            $emailsModel = self::getEmail($rawEmailsModel['email'], true);
            $resultArray['email_id'] = $emailsModel['id'];
            $resultArray['email'] = $emailsModel['email'];
            
            $rawPhonesModel = self::customerConfig(PhonesModel::class);
            self::saveCheckPhone($rawPhonesModel);
            $phonesModel = self::getPhone($rawPhonesModel['phone'], true);
            $resultArray['phone'] = $phonesModel['id'];
            
            $rawAddressModel = self::customerConfig(AddressModel::class);
            self::saveCheckAddress($rawAddressModel);
            $addressModel = self::getAddress($rawAddressModel['address'], true);
            $resultArray['address'] = $addressModel['id'];
            
            $rawCitiesModel = self::customerConfig(CitiesModel::class);
            self::saveCheckCity($rawCitiesModel);
            $citiesModel = self::getCity($rawCitiesModel['city'], true);
            $resultArray['city'] = $citiesModel['id'];
            
            $rawCountriesModel = self::customerConfig(CountriesModel::class);
            self::saveCheckCountry($rawCountriesModel);
            $countriesModel = self::getCountry($rawCountriesModel['country'], true);
            $resultArray['country'] = $countriesModel['id'];
            
            $rawPostcodesModel = self::customerConfig(PostcodesModel::class);
            self::saveCheckPostcode($rawPostcodesModel);
            $postcodesModel = self::getPostcode($rawPostcodesModel['postcode'], true);
            $resultArray['postcode'] = $postcodesModel['id'];
            
            if (\Yii::$app->params['customerArray']['createAccount']) {
                $rawUsersModel = self::customerConfig(UsersModel::class);
                if (!empty($rawUsersModel->password)) {
                    $rawUsersModel->id_email = $emailsModel['id'];
                    $rawUsersModel->password = password_hash($rawUsersModel->password, PASSWORD_DEFAULT);
                    $rawUsersModel->id_name = $namesModel['id'];
                    $rawUsersModel->id_surname = $surnamesModel['id'];
                    $rawUsersModel->id_phone = $phonesModel['id'];
                    $rawUsersModel->id_address = $addressModel['id'];
                    $rawUsersModel->id_city = $citiesModel['id'];
                    $rawUsersModel->id_country = $countriesModel['id'];
                    $rawUsersModel->id_postcode = $postcodesModel['id'];
                    if (!$rawUsersModel->save(false)) {
                        throw new ErrorException(ExceptionsTrait::methodError('UsersModel::save'));
                    }
                    $usersModel = self::getUser($emailsModel['id'], true);
                    $resultArray['user'] = $usersModel['id'];
                }
            }
            
            if (\Yii::$app->user->isGuest == false && !empty(\Yii::$app->user->id)) {
                $resultArray['user'] = \Yii::$app->user->id;
                if (\Yii::$app->params['customerArray']['dataChange']) {
                    \Yii::$app->user->identity->id_name = $namesModel['id'];
                    \Yii::$app->user->identity->id_surname = $surnamesModel['id'];
                    \Yii::$app->user->identity->id_phone = $phonesModel['id'];
                    \Yii::$app->user->identity->id_address = $addressModel['id'];
                    \Yii::$app->user->identity->id_city = $citiesModel['id'];
                    \Yii::$app->user->identity->id_country = $countriesModel['id'];
                    \Yii::$app->user->identity->id_postcode = $postcodesModel['id'];
                    if (!\Yii::$app->user->identity->save(false)) {
                        throw new ErrorException(ExceptionsTrait::methodError('UsersModel::save'));
                    }
                }
            }
            
            $resultArray['delivery'] =  \Yii::$app->params['customerArray'][DeliveriesModel::tableName()]['id'];
            $resultArray['payment'] = \Yii::$app->params['customerArray'][PaymentsModel::tableName()]['id'];
            
            return $resultArray;
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
    private static function getDeliveries(): array
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
    private static function getPayments(): array
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
     * Создает и конфигурирует модель данными \Yii::$app->params['customerArray'] 
     * @param string $model имя модели
     */
    private static function customerConfig(string $model): AbstractBaseModel
    {
        try {
            return \Yii::configure((new $model()), \Yii::$app->params['customerArray'][$model::tableName()]);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конфигурирует модель данными \Yii::$app->params['customerArray'] 
     * @param object $model объект модели
     * @param array $usersModel массив данных UsersModel
     * @param string $toKey имя свойства, которое будет заполнено
     * @param string $fromKey ключ массива $usersModel
     * @return object
     */
    private static function customerConfigObj(AbstractBaseModel $model, array $usersModel=[], string $toKey='', string $fromKey=''): AbstractBaseModel
    {
        try {
            if (!empty(\Yii::$app->params['customerArray'][$model->tableNameObj()])) {
                $model = \Yii::configure($model, \Yii::$app->params['customerArray'][$model->tableNameObj()]);
            } elseif (\Yii::$app->user->isGuest == false && !empty($usersModel[$fromKey])) {
                $model = \Yii::configure($model, [$toKey=>$usersModel[$fromKey]]);
            }
            
            return $model;
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
