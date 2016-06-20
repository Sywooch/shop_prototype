<?php

namespace app\controllers;

use yii\helpers\Url;
use app\controllers\AbstractBaseController;
use app\cart\ShoppingCart;
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
                    ShoppingCart::addProduct($model);
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
                    ShoppingCart::clearProductsArray();
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
                    ShoppingCart::removeProduct($model);
                    if (!empty(ShoppingCart::getProductsArray())) {
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
                    ShoppingCart::updateProduct($model);
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
            
            if (\Yii::$app->request->isPost && $usersModel->load(\Yii::$app->request->post()) && $emailsModel->load(\Yii::$app->request->post()) && $addressModel->load(\Yii::$app->request->post())) {
                if ($usersModel->validate()) {
                    $usersInsertMapper = new UsersInsertMapper([
                        'tableName'=>'users',
                        'fields'=>['login', 'password', 'name', 'surname'],
                        'objectsArray'=>[$usersModel],
                    ]);
                    $usersInsertMapper->setGroup();
                }
                
                if ($emailsModel->validate()) {
                    $usersModel->emails = $this->getEmailsModel($emailsModel);
                    $this->setUsersEmailsModel($usersModel, $emailsModel);
                }
                
                if ($addressModel->validate()) {
                    $usersModel->address = $this->getAddressModel($addressModel);
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
}
