<?php

namespace app\controllers;

use yii\helpers\Url;
use yii\base\ErrorException;
use app\controllers\AbstractBaseController;
use app\helpers\MailHelper;
use app\models\ProductsModel;
use app\models\UsersModel;
use app\models\EmailsModel;
use app\models\AddressModel;
use app\models\PhonesModel;
use app\models\DeliveriesModel;
use app\models\PaymentsModel;
use app\models\UsersPurchasesModel;
use app\mappers\AddressByAddressMapper;
use app\mappers\AddressInsertMapper;
use app\mappers\PhonesByPhoneMapper;
use app\mappers\PhonesInsertMapper;
use app\mappers\DeliveriesByIdMapper;
use app\mappers\PaymentsMapper;
use app\mappers\PaymentsByIdMapper;
use app\mappers\UsersPurchasesInsertMapper;

/**
 * Управляет процессом добавления комментария
 */
class ShoppingCartController extends AbstractBaseController
{
    /**
     * Управляет процессом добавления товара в корзину
     * @return redirect
     */
    public function actionAddToCart()
    {
        try {
            $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
            
            if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
                if ($model->validate()) {
                    if (!\Yii::$app->cart->addProduct($model)) {
                        throw new ErrorException('Ошибка при добавлении товара в корзину!');
                    }
                    return $this->redirect(Url::to(['product-detail/index', 'categories'=>$model->categories, 'subcategory'=>$model->subcategory, 'id'=>$model->id]));
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом очистки корзины
     * @return redirect
     */
    public function actionClearCart()
    {
        try {
            $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_CLEAR_CART]);
            
            if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
                if ($model->validate()) {
                    if (!\Yii::$app->cart->clearProductsArray()) {
                        throw new ErrorException('Ошибка при очистке корзины!');
                    }
                    if (!empty($model->id)) {
                        return $this->redirect(Url::to(['product-detail/index', 'categories'=>$model->categories, 'subcategory'=>$model->subcategory, 'id'=>$model->id]));
                    } else {
                        $urlArray = ['products-list/index'];
                        if (!empty($model->categories)) {
                            $urlArray = array_merge($urlArray, ['categories'=>$model->categories]);
                        }
                        if (!empty($model->subcategory)) {
                            $urlArray = array_merge($urlArray, ['subcategory'=>$model->subcategory]);
                        }
                        return $this->redirect(Url::to($urlArray));
                    }
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом вывода полной информации о покупках на странице корзины
     * @return string
     */
    public function actionIndex()
    {
        try {
            if (empty(\Yii::$app->cart)) {
                return $this->redirect(Url::to(['products-list/index']));
            }
            if (!is_array($dataForRender = $this->getDataForRender())) {
                throw new ErrorException('Ошибка при формировании массива данных!');
            }
            return $this->render('shopping-cart.twig', $dataForRender);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом удаления из корзины определенного продукта
     * @return redirect
     */
    public function actionRemoveProduct()
    {
        try {
            $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_REMOVE]);
            
            if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
                if ($model->validate()) {
                    if (!\Yii::$app->cart->removeProduct($model)) {
                        throw new ErrorException('Ошибка при удалении товара из корзины!');
                    }
                    if (!empty(\Yii::$app->cart->getProductsArray())) {
                        return $this->redirect(Url::to(['shopping-cart/index']));
                    } else {
                        return $this->redirect(Url::to(['products-list/index']));
                    }
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом обновления данных определенного продукта
     * @return redirect
     */
    public function actionUpdateProduct()
    {
        try {
            $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
            
            if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
                if ($model->validate()) {
                    if (!\Yii::$app->cart->updateProduct($model)) {
                        throw new ErrorException('Ошибка при обновлении данных о товаре в корзине!');
                    }
                    return $this->redirect(Url::to(['shopping-cart/index']));
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом добавления адреса доставки, контактных данных
     * @return string
     */
    public function actionAddressContacts()
    {
        try {
            if (empty(\Yii::$app->cart)) {
                return $this->redirect(Url::to(['products-list/index']));
            }
            
            $usersModel = \Yii::$app->cart->user;
            $emailsModel = \Yii::$app->cart->user->emails;
            $addressModel = \Yii::$app->cart->user->address;
            $phonesModel = \Yii::$app->cart->user->phones;
            $deliveriesModel = \Yii::$app->cart->user->deliveries;
            $paymentsModel = \Yii::$app->cart->user->payments;
            
            if (\Yii::$app->user->login != \Yii::$app->params['nonAuthenticatedUserLogin']) {
                if (empty(\Yii::$app->cart->user->name) && !empty(\Yii::$app->user->name)) {
                    $usersModel->name = \Yii::$app->user->name;
                }
                if (empty(\Yii::$app->cart->user->surname) && !empty(\Yii::$app->user->surname)) {
                    $usersModel->surname = \Yii::$app->user->surname;
                }
                if (empty(\Yii::$app->cart->user->emails->email) && !empty(\Yii::$app->user->emails->email)) {
                    $emailsModel->email = \Yii::$app->user->emails->email;
                }
                if (empty(\Yii::$app->cart->user->phones->phone) && !empty(\Yii::$app->user->phones->phone)) {
                    $phonesModel->phone = \Yii::$app->user->phones->phone;
                }
                if (empty(\Yii::$app->cart->user->address->address) && !empty(\Yii::$app->user->address->address)) {
                    $addressModel->address = \Yii::$app->user->address->address;
                }
                if (empty(\Yii::$app->cart->user->address->city) && !empty(\Yii::$app->user->address->city)) {
                    $addressModel->city = \Yii::$app->user->address->city;
                }
                if (empty(\Yii::$app->cart->user->address->postcode) && !empty(\Yii::$app->user->address->postcode)) {
                    $addressModel->postcode = \Yii::$app->user->address->postcode;
                }
                if (empty(\Yii::$app->cart->user->address->country) && !empty(\Yii::$app->user->address->country)) {
                    $addressModel->country = \Yii::$app->user->address->country;
                }
            }
            
            if (\Yii::$app->request->isPost && $usersModel->load(\Yii::$app->request->post()) && $emailsModel->load(\Yii::$app->request->post()) && $addressModel->load(\Yii::$app->request->post()) && $phonesModel->load(\Yii::$app->request->post()) && $deliveriesModel->load(\Yii::$app->request->post()) && $paymentsModel->load(\Yii::$app->request->post())) {
                if ($usersModel->validate() && $emailsModel->validate() && $addressModel->validate() && $phonesModel->validate() && $deliveriesModel->validate() && $paymentsModel->validate()) {
                    
                }
                return $this->redirect(Url::to(['shopping-cart/check-pay']));
            }
            
            if (!is_array($dataForRender = $this->getDataForRender())) {
                throw new ErrorException('Ошибка при получении данных для рендеринга!');
            }
            $dataForRender = array_merge($dataForRender, ['usersModel'=>$usersModel, 'emailsModel'=>$emailsModel, 'addressModel'=>$addressModel, 'phonesModel'=>$phonesModel, 'deliveriesModel'=>$deliveriesModel, 'paymentsModel'=>$paymentsModel]);
            return $this->render('address-contacts.twig', $dataForRender);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом подтверждения заказа
     * @return string
     */
    public function actionCheckPay()
    {
        try {
            if (empty(\Yii::$app->cart)) {
                return $this->redirect(Url::to(['products-list/index']));
            } elseif (empty(\Yii::$app->cart->user)) {
                return $this->redirect(Url::to(['shopping-cart/address-contacts']));
            }
            if (!is_array($dataForRender = $this->getDataForRender())) {
                throw new ErrorException('Ошибка при получении данных для рендеринга!');
            }
            return $this->render('check-pay.twig', $dataForRender);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом загрузки данных в БД после подтверждения покупки,
     * отправки сообщений покупателю и менеджеру магазина,
     * оплаты в случае, если выбрана онлайн-оплата
     * @return string
     */
    public function actionPay()
    {
        try {
            if (empty(\Yii::$app->cart)) {
                return $this->redirect(Url::to(['products-list/index']));
            } elseif (empty(\Yii::$app->cart->user)) {
                return $this->redirect(Url::to(['shopping-cart/address-contacts']));
            }
            
            if (!empty(\Yii::$app->cart->user->emails) && is_object(\Yii::$app->cart->user->emails)) {
                if (!$emailsModel = $this->getEmailsModel(\Yii::$app->cart->user->emails)) {
                    throw new ErrorException('Ошибка при сохранении E-mail!');
                }
                \Yii::$app->cart->user->id_emails = $emailsModel->id;
            } else {
                throw new ErrorException('Недоступны данные для сохранения сведений о покупке!');
            }
            
            if (!empty(\Yii::$app->cart->user->address) && is_object(\Yii::$app->cart->user->address)) {
                if (!$addressModel = $this->getAddressModel(\Yii::$app->cart->user->address)) {
                    throw new ErrorException('Ошибка при сохранении address!');
                }
                \Yii::$app->cart->user->id_address = $addressModel->id;
            } else {
                throw new ErrorException('Недоступны данные для сохранения сведений о покупке!');
            }
            
            if (!empty(\Yii::$app->cart->user->phones) && is_object(\Yii::$app->cart->user->phones)) {
                if (!$phonesModel = $this->getPhonesModel(\Yii::$app->cart->user->phones)) {
                    throw new ErrorException('Ошибка при сохранении phones!');
                }
                \Yii::$app->cart->user->id_phones = $phonesModel->id;
            } else {
                throw new ErrorException('Недоступны данные для сохранения сведений о покупке!');
            }
            
            if ($this->setUsersModel(\Yii::$app->cart->user)) {
                if ($this->setUsersPurchasesModel()) {
                    if (!MailHelper::send([['template'=>'@app/views/mail/customer.twig', 'setFrom'=>['test@test.com'=>'John'], 'setTo'=>['timofey@localhost.localdomain'=>'Timofey'], 'setSubject'=>'Hello!']])) {
                        throw new ErrorException('Ошибка при отправке E-mail сообщения!');
                    }
                    if (!\Yii::$app->cart->clearProductsArray()) {
                        throw new ErrorException('Ошибка при очистке корзины!');
                    }
                } else {
                    throw new ErrorException('Ошибка при сохранении связи пользователя с покупкой в процессе оформления заказа!');
                }
            } else {
                throw new ErrorException('Ошибка при сохранении данных пользователя в процессе оформления покупки!');
            }
            
            if (!is_array($dataForRender = $this->getDataForRender())) {
                throw new ErrorException('Ошибка при формировании массива данных!');
            }
            $resultArray = array_merge(['email'=>$emailsModel], $dataForRender);
            return $this->render('thank.twig', $resultArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
     /**
      * Получает AddressModel для переданного в форму address
     * Проверяет, существет ли запись в БД для address, если да, прекращает выполнение,
     * если нет, создает новую запись в БД
     * @param object $addressModel экземпляр AddressModel
     * @return object
     */
     private function getAddressModel(AddressModel $addressModel)
     {
        try {
            $addressByAddressMapper = new AddressByAddressMapper([
                'tableName'=>'address',
                'fields'=>['id', 'address', 'city', 'country', 'postcode'],
                'model'=>$addressModel
            ]);
            $result = $addressByAddressMapper->getOneFromGroup();
            if (is_object($result) && $result instanceof AddressModel) {
                $addressModel = $result;
            } else {
                $addressInsertMapper = new AddressInsertMapper([
                    'tableName'=>'address',
                    'fields'=>['address', 'city', 'country', 'postcode'],
                    'objectsArray'=>[$addressModel],
                ]);
                if (!$addressInsertMapper->setGroup()) {
                    throw new ErrorException('Не удалось сохранить данные в БД!');
                }
            }
            return $addressModel;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
     }
     
    /**
      * Получает PhonesModel для переданного в форму phone
     * Проверяет, существет ли запись в БД для phones, если да, прекращает выполнение,
     * если нет, создает новую запись в БД
     * @param object $phonesModel экземпляр PhonesModel
     * @return object
     */
    private function getPhonesModel(PhonesModel $phonesModel)
    {
        try {
            $phonesByPhoneMapper = new PhonesByPhoneMapper([
                'tableName'=>'phones',
                'fields'=>['id', 'phone'],
                'model'=>$phonesModel
            ]);
            $result = $phonesByPhoneMapper->getOneFromGroup();
            if (is_object($result) && $result instanceof PhonesModel) {
                $phonesModel = $result;
            } else {
                $phonesInsertMapper = new PhonesInsertMapper([
                    'tableName'=>'phones',
                    'fields'=>['phone'],
                    'objectsArray'=>[$phonesModel],
                ]);
                if (!$phonesInsertMapper->setGroup()) {
                    throw new ErrorException('Не удалось обновить данные в БД!');
                }
            }
            return $phonesModel;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
      * Получает DeliveriesModel для переданного в форму id
     * @param object $deliveriesModel экземпляр DeliveriesModel
     * @return object
     */
    private function getDeliveriesModel(DeliveriesModel $deliveriesModel)
    {
        try {
            $deliveriesByIdMapper = new DeliveriesByIdMapper([
                'tableName'=>'deliveries',
                'fields'=>['id', 'name', 'description', 'price'],
                'model'=>$deliveriesModel,
            ]);
            $result = $deliveriesByIdMapper->getOneFromGroup();
            if (!is_object($result) || !$result instanceof DeliveriesModel) {
                throw new ErrorException('Ошибка при получении данных из БД!');
            }
            $deliveriesModel = $result;
            return $deliveriesModel;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
      * Получает PaymentsModel для переданного в форму id
     * @param object $paymentsModel экземпляр PaymentsModel
     * @return object
     */
    private function getPaymentsModel(PaymentsModel $paymentsModel)
    {
        try {
            $paymentsByIdMapper = new PaymentsByIdMapper([
                'tableName'=>'payments',
                'fields'=>['id', 'name', 'description'],
                'model'=>$paymentsModel,
            ]);
            $result = $paymentsByIdMapper->getOneFromGroup();
            if (!is_object($result) || !$result instanceof PaymentsModel) {
                throw new ErrorException('Ошибка при получении данных из БД!');
            }
            $paymentsModel = $result;
            return $paymentsModel;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Создает UsersPurchasesModel
     * создает новую запись в БД, вязывающую пользователя с купленным товаром
     * @return boolean
     */
    private function setUsersPurchasesModel()
    {
        try {
            $id_users = \Yii::$app->cart->user->id;
            $productsArray = \Yii::$app->cart->getProductsArray();
            $id_deliveries = \Yii::$app->cart->user->deliveries->id;
            $id_payments = \Yii::$app->cart->user->payments->id;
            
            if (empty($id_users)) {
                throw new ErrorException('Отсутствует cart->user->id!');
            }
            if (!is_array($productsArray) || empty($productsArray)) {
                throw new ErrorException('Отсутствуют данные в массиве cart->productsArray!');
            }
            if (empty($id_deliveries)) {
                throw new ErrorException('Отсутствует user->deliveries->id!');
            }
            if (empty($id_payments)) {
                throw new ErrorException('Отсутствует user->payments->id!');
            }
            
            $arrayToDb = [];
            foreach ($productsArray as $product) {
                $arrayToDb[] = ['id_users'=>$id_users, 'id_products'=>$product->id, 'quantity'=>$product->quantity, 'id_colors'=>$product->colorToCart, 'id_sizes'=>$product->sizeToCart, 'id_deliveries'=>$id_deliveries, 'id_payments'=>$id_payments];
            }
            
            $usersPurchasesInsertMapper = new UsersPurchasesInsertMapper([
                'tableName'=>'users_purchases',
                'fields'=>['id_users', 'id_products', 'quantity', 'id_colors', 'id_sizes', 'id_deliveries', 'id_payments', 'received', 'received_date'],
                'DbArray'=>$arrayToDb,
            ]);
            if (!$result = $usersPurchasesInsertMapper->setGroup()) {
                throw new ErrorException('Не удалось сохранить данные в БД!');
            }
            return $result;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    public function behaviors()
    {
        return [
            ['class'=>'app\filters\ProductsListFilter'],
        ];
    }
}
