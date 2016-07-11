<?php

namespace app\controllers;

use yii\web\Controller;
use app\traits\ExceptionsTrait;
use yii\base\ErrorException;
use app\helpers\UserAuthenticationHelper;
use app\mappers\CategoriesMapper;
use app\mappers\UsersInsertMapper;
use app\mappers\EmailsByEmailMapper;
use app\mappers\EmailsInsertMapper;
use app\mappers\UsersUpdateMapper;
use app\mappers\UsersRulesInsertMapper;
use app\models\ProductsModel;
use app\models\UsersModel;
use app\models\EmailsModel;
use app\models\CurrencyModel;


/**
 * Определяет функции, общие для разных типов контроллеров
 */
abstract class AbstractBaseController extends Controller
{
    use ExceptionsTrait;
    
    /**
     * Получает данные, необходимые в нескольких типах контроллеров 
     * @return array
     */
    /*protected function getDataForRender()
    {
        try {
            $result = array();
            
            # Получаю массив объектов категорий
            $categoriesMapper = new CategoriesMapper([
                'tableName'=>'categories',
                'fields'=>['id', 'name', 'seocode'],
                'orderByField'=>'name'
            ]);
            $result['categoriesList'] = $categoriesMapper->getGroup();
            $result['clearCartModel'] = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_CLEAR_CART]);
            $result['usersModelForLogout'] = new UsersModel(['scenario'=>UsersModel::GET_FROM_LOGOUT_FORM]);
            $result['currencyModel'] = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_FORM_SET]);
            return $result;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }*/
    
    /**
     * Обновляет или создает UsersModel
     * Проверяет, авторизирован ли user в системе, если да, обновляет данные,
     * если нет, создает новую запись в БД
     * @param object $usersModel экземпляр UsersModel
     * @return int
     */
    /*protected function setUsersModel(UsersModel $usersModel)
    {
        try {
            if (\Yii::$app->user->login != \Yii::$app->params['nonAuthenticatedUserLogin'] && !empty(\Yii::$app->user->id)) {
                \Yii::configure($usersModel, ['id'=>\Yii::$app->user->id]);
                if (!empty(array_diff_assoc($usersModel->getDataForСomparison(), \Yii::$app->user->getDataForСomparison()))) {
                    $usersUpdateMapper = new UsersUpdateMapper([
                        'tableName'=>'users',
                        'fields'=>['name', 'surname', 'id_emails', 'id_phones', 'id_address'],
                        'model'=>$usersModel,
                    ]);
                    if (!$result = $usersUpdateMapper->setGroup()) {
                        throw new ErrorException('Не удалось обновить данные в БД!');
                    }
                    if (!UserAuthenticationHelper::fill($usersModel)) {
                        throw new ErrorException('Ошибка при обновлении данных \Yii::$app->user!');
                    }
                }
            } else {
                $usersInsertMapper = new UsersInsertMapper([
                    'tableName'=>'users',
                    'fields'=>['login', 'password', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'],
                    'objectsArray'=>[$usersModel],
                ]);
                if (!$usersInsertMapper->setGroup()) {
                    throw new ErrorException('Не удалось добавить данные в БД!');
                }
                if (!$this->setUsersRulesModel($usersModel)) {
                    throw new ErrorException('Ошибка при сохранении связи пользователя с правами доступа!');
                }
            }
            return $usersModel;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }*/
    
    /**
     * Получает EmailsModel для переданного в форму email
     * Проверяет, существет ли запись в БД для такого email, если да, возвращает ее,
     * если нет, создает новую запись в БД
     * @param object $emailsModel экземпляр EmailsModel
     * @return object
     */
    /*protected function getEmailsModel(EmailsModel $emailsModel)
    {
        try {
            $emailsByEmailMapper = new EmailsByEmailMapper([
                'tableName'=>'emails',
                'fields'=>['id', 'email'],
                'model'=>$emailsModel
            ]);
            $result = $emailsByEmailMapper->getOneFromGroup();
            if (is_object($result) && $result instanceof EmailsModel) {
                $emailsModel = $result;
            } else {
                $emailsInsertMapper = new EmailsInsertMapper([
                    'tableName'=>'emails',
                    'fields'=>['email'],
                    'objectsArray'=>[$emailsModel],
                ]);
                if (!$emailsInsertMapper->setGroup()) {
                    throw new ErrorException('Не удалось обновить данные в БД!');
                }
            }
            return $emailsModel;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }*/
    
    /**
     * Создает новую запись в БД, мвязывающую пользователя с правами доступа
     * @param object $usersModel экземпляр UsersModel
     * @return int
     */
    /*protected function setUsersRulesModel(UsersModel $usersModel)
    {
        try {
            $usersRulesInsertMapper = new UsersRulesInsertMapper([
                'tableName'=>'users_rules',
                'fields'=>['id_users', 'id_rules'],
                'model'=>$usersModel
            ]);
            if (!$result = $usersRulesInsertMapper->setGroup()) {
                throw new ErrorException('Не удалось добавить данные в БД!');
            }
            return $result;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }*/
}
