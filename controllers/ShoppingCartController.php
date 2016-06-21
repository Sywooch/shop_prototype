<?php

namespace app\controllers;

use yii\helpers\Url;
use app\controllers\AbstractBaseController;
use app\models\ProductsModel;
use app\models\UsersModel;
use app\models\EmailsModel;
use app\models\AddressModel;
use app\models\PhonesModel;
use app\mappers\UsersInsertMapper;
use app\mappers\EmailsByEmailMapper;
use app\mappers\EmailsInsertMapper;
use app\mappers\UsersEmailsInsertMapper;
use app\mappers\UsersEmailsByUsersEmailsMapper;
use app\mappers\AddressByAddressMapper;
use app\mappers\AddressInsertMapper;
use app\mappers\UsersAddressByUsersAddressMapper;
use app\mappers\UsersAddressInsertMapper;
use app\mappers\PhonesByPhoneMapper;
use app\mappers\PhonesInsertMapper;
use app\mappers\UsersPhonesByUsersPhonesMapper;
use app\mappers\UsersPhonesInsertMapper;

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
                    \Yii::$app->cart->addProduct($model);
                    $productData = \Yii::$app->request->post('ProductsModel');
                    $this->redirect(Url::to(['product-detail/index', 'categories'=>$productData['categories'], 'subcategory'=>$productData['subcategory'], 'id'=>$productData['id']]));
                }
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
                    \Yii::$app->cart->clearProductsArray();
                    $productData = \Yii::$app->request->post('ProductsModel');
                    if (!empty($productData['productId'])) {
                        $this->redirect(Url::to(['product-detail/index', 'categories'=>$productData['categories'], 'subcategory'=>$productData['subcategory'], 'id'=>$productData['productId']]));
                    } else {
                        $urlArray = ['products-list/index'];
                        if (!empty($productData['categories'])) {
                            $urlArray = array_merge($urlArray, ['categories'=>$productData['categories']]);
                        }
                        if (!empty($productData['subcategory'])) {
                            $urlArray = array_merge($urlArray, ['subcategory'=>$productData['subcategory']]);
                        }
                        $this->redirect(Url::to($urlArray));
                    }
                }
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
            $dataForRender = $this->getDataForRender();
            return $this->render('shopping-cart.twig', $dataForRender);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом удаления из корзины определенного продукта
     * @return string
     */
    public function actionRemoveProduct()
    {
        try {
            $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_REMOVE]);
            
            if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
                if ($model->validate()) {
                    \Yii::$app->cart->removeProduct($model);
                    if (!empty(\Yii::$app->cart->getProductsArray())) {
                        $this->redirect(Url::to(['shopping-cart/index']));
                    } else {
                        $this->redirect(Url::to(['products-list/index']));
                    }
                }
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом обновления данных определенного продукта
     * @return string
     */
    public function actionUpdateProduct()
    {
        try {
            $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
            
            if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
                if ($model->validate()) {
                    \Yii::$app->cart->updateProduct($model);
                    $this->redirect(Url::to(['shopping-cart/index']));
                }
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
            $usersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_CART_FORM]);
            $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
            $addressModel = new AddressModel(['scenario'=>AddressModel::GET_FROM_FORM]);
            $phonesModel = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_FORM]);
            
            if (\Yii::$app->request->isPost && $usersModel->load(\Yii::$app->request->post()) && $emailsModel->load(\Yii::$app->request->post()) && $addressModel->load(\Yii::$app->request->post()) && $phonesModel->load(\Yii::$app->request->post())) {
                if ($usersModel->validate()) {
                    \Yii::$app->cart->user = $this->getUsersModel($usersModel);
                }
                
                if ($emailsModel->validate()) {
                    \Yii::$app->cart->user->emails = $this->getEmailsModel($emailsModel);
                    $this->setUsersEmailsModel($usersModel, $emailsModel);
                }
                
                if ($addressModel->validate()) {
                    \Yii::$app->cart->user->address = $this->getAddressModel($addressModel);
                    $this->setUsersAddressModel($usersModel, $addressModel);
                }
                
                if ($phonesModel->validate()) {
                    \Yii::$app->cart->user->phones = $this->getPhonesModel($phonesModel);
                    $this->setUsersPhonesModel($usersModel, $phonesModel);
                }
            }
            
            $dataForRender = $this->getDataForRender();
            $dataForRender = array_merge($dataForRender, ['usersModel'=>$usersModel, 'emailsModel'=>$emailsModel, 'addressModel'=>$addressModel, 'phonesModel'=>$phonesModel]);
            return $this->render('address-contacts-form.twig', $dataForRender);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Получает UsersModel для переданного в форму users
     * Проверяет, авторизирован ли user в системе, если да, обновляет данные,
     * если нет, создает новую запись в БД
     * @param object $usersModel экземпляр UsersModel
     * @return object
     */
    private function getUsersModel(UsersModel $usersModel)
    {
        try {
            $usersInsertMapper = new UsersInsertMapper([
                'tableName'=>'users',
                'fields'=>['login', 'password', 'name', 'surname'],
                'objectsArray'=>[$usersModel],
            ]);
            $usersInsertMapper->setGroup();
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $usersModel;
    }
    
    /**
     * Получает EmailsModel для переданного в форму email
     * Проверяет, существет ли запись в БД для такого email, если да, возвращает ее,
     * если нет, создает новую запись в БД
     * @param object $emailsModel экземпляр EmailsModel
     * @return object
     */
    private function getEmailsModel(EmailsModel $emailsModel)
    {
        try {
            $emailsByEmailMapper = new EmailsByEmailMapper([
                'tableName'=>'emails',
                'fields'=>['id', 'email'],
                'model'=>$emailsModel
            ]);
            if ($result = $emailsByEmailMapper->getOneFromGroup()) {
                $emailsModel = $result;
            } else {
                $emailsInsertMapper = new EmailsInsertMapper([
                    'tableName'=>'emails',
                    'fields'=>['email'],
                    'objectsArray'=>[$emailsModel],
                ]);
                $emailsInsertMapper->setGroup();
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $emailsModel;
    }
    
    /**
     * Проверяет, существет ли запись в БД для user and email, если да, прекращает выполнение,
     * если нет, создает новую запись в БД
     * @param object $usersModel экземпляр UsersModel
     * @param object $emailsModel экземпляр EmailsModel
     * @return boolean
     */
    private function setUsersEmailsModel(UsersModel $usersModel, EmailsModel $emailsModel)
    {
        try {
            $usersEmailsByUsersEmailsMapper = new UsersEmailsByUsersEmailsMapper([
                'tableName'=>'users_emails',
                'fields'=>['id_users', 'id_emails'],
                'params'=>[':id_users'=>$usersModel->id, ':id_emails'=>$emailsModel->id],
            ]);
            if (!$usersEmailsByUsersEmailsMapper->getOneFromGroup()) {
                $usersEmailsInsertMapper = new UsersEmailsInsertMapper([
                    'tableName'=>'users_emails',
                    'fields'=>['id_users', 'id_emails'],
                    'DbArray'=>[['id_users'=>$usersModel->id, 'id_emails'=>$emailsModel->id]],
                ]);
                $usersEmailsInsertMapper->setGroup();
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return true;
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
            if ($result = $addressByAddressMapper->getOneFromGroup()) {
                $addressModel = $result;
            } else {
                $addressInsertMapper = new AddressInsertMapper([
                    'tableName'=>'address',
                    'fields'=>['address', 'city', 'country', 'postcode'],
                    'objectsArray'=>[$addressModel],
                ]);
                $addressInsertMapper->setGroup();
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $addressModel;
     }
     
    /**
     * Проверяет, существет ли запись в БД для user and address, если да, прекращает выполнение,
     * если нет, создает новую запись в БД
     * @param object $usersModel экземпляр UsersModel
     * @param object $addressModel экземпляр AddressModel
     * @return boolean
     */
    private function setUsersAddressModel(UsersModel $usersModel, AddressModel $addressModel)
    {
        try {
            $usersAddressByUsersAddressMapper = new UsersAddressByUsersAddressMapper([
                'tableName'=>'users_address',
                'fields'=>['id_users', 'id_address'],
                'params'=>[':id_users'=>$usersModel->id, ':id_address'=>$addressModel->id],
            ]);
            if (!$usersAddressByUsersAddressMapper->getOneFromGroup()) {
                $usersAddressInsertMapper = new UsersAddressInsertMapper([
                    'tableName'=>'users_address',
                    'fields'=>['id_users', 'id_address'],
                    'DbArray'=>[['id_users'=>$usersModel->id, 'id_address'=>$addressModel->id]],
                ]);
                $usersAddressInsertMapper->setGroup();
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return true;
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
            if ($result = $phonesByPhoneMapper->getOneFromGroup()) {
                $phonesModel = $result;
            } else {
                $phonesInsertMapper = new PhonesInsertMapper([
                    'tableName'=>'phones',
                    'fields'=>['phone'],
                    'objectsArray'=>[$phonesModel],
                ]);
                $phonesInsertMapper->setGroup();
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $phonesModel;
    }
    
    /**
     * Проверяет, существет ли запись в БД для user and phones, если да, прекращает выполнение,
     * если нет, создает новую запись в БД
     * @param object $usersModel экземпляр UsersModel
     * @param object $phonesModel экземпляр PhonesModel
     * @return boolean
     */
    private function setUsersPhonesModel(UsersModel $usersModel, PhonesModel $phonesModel)
    {
        try {
            $usersPhonesByUsersPhonesMapper = new UsersPhonesByUsersPhonesMapper([
                'tableName'=>'users_phones',
                'fields'=>['id_users', 'id_phones'],
                'params'=>[':id_users'=>$usersModel->id, ':id_phones'=>$phonesModel->id]
            ]);
            if (!$usersPhonesByUsersPhonesMapper->getOneFromGroup()) {
                $usersPhonesInsertMapper = new UsersPhonesInsertMapper([
                    'tableName'=>'users_phones',
                    'fields'=>['id_users', 'id_phones'],
                    'DbArray'=>[['id_users'=>$usersModel->id, 'id_phones'=>$phonesModel->id]],
                ]);
                $usersPhonesInsertMapper->setGroup();
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return true;
    }
}
