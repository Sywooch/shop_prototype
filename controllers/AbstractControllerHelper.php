<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\validators\{AddressExistsCreateValidator,
    CityExistsCreateValidator,
    CountryExistsCreateValidator,
    EmailExistsCreateValidator,
    NameExistsCreateValidator,
    PhoneExistsCreateValidator,
    PostcodeExistsCreateValidator,
    SurnameExistsCreateValidator};
use app\models\{AddressModel,
    CitiesModel,
    CountriesModel,
    EmailsModel,
    MailingListModel,
    NamesModel,
    PhonesModel,
    PostcodesModel,
    SubcategoryModel,
    SurnamesModel,
    UsersModel};

/**
 * Определяет методы, общие для разных типов сервисных классов, 
 * обслужи вающих контроллеры
 */
class AbstractControllerHelper
{
    /**
     * Сохраняет email в случае, если его нет в БД
     * @param object $email EmailsModel
     */
    protected static function saveCheckEmail(EmailsModel $email)
    {
        try {
            if (!(new EmailExistsCreateValidator())->validate($email['email'])) {
                if (!$email->save(false)) {
                    throw new ErrorException(ExceptionsTrait::methodError('EmailsModel::save'));
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Сохраняет name в случае, если его нет в БД
     * @param object $name NamesModel
     */
    protected static function saveCheckName(NamesModel $name)
    {
        try {
            if (!(new NameExistsCreateValidator())->validate($name['name'])) {
                if (!$name->save(false)) {
                    throw new ErrorException(ExceptionsTrait::methodError('NamesModel::save'));
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Сохраняет surname в случае, если его нет в БД
     * @param object $surname SurnamesModel
     */
    protected static function saveCheckSurname(SurnamesModel $surname)
    {
        try {
            if (!(new SurnameExistsCreateValidator())->validate($surname['surname'])) {
                if (!$surname->save(false)) {
                    throw new ErrorException(ExceptionsTrait::methodError('SurnamesModel::save'));
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Сохраняет phone в случае, если его нет в БД
     * @param object $phone PhonesModel
     */
    protected static function saveCheckPhone(PhonesModel $phone)
    {
        try {
            if (!(new PhoneExistsCreateValidator())->validate($phone['phone'])) {
                if (!$phone->save(false)) {
                    throw new ErrorException(ExceptionsTrait::methodError('PhonesModel::save'));
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Сохраняет address в случае, если его нет в БД
     * @param object $address AddressModel
     */
    protected static function saveCheckAddress(AddressModel $address)
    {
        try {
            if (!(new AddressExistsCreateValidator())->validate($address['address'])) {
                if (!$address->save(false)) {
                    throw new ErrorException(ExceptionsTrait::methodError('AddressModel::save'));
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Сохраняет city в случае, если его нет в БД
     * @param object $city CitiesModel
     */
    protected static function saveCheckCity(CitiesModel $city)
    {
        try {
            if (!(new CityExistsCreateValidator())->validate($city['city'])) {
                if (!$city->save(false)) {
                    throw new ErrorException(ExceptionsTrait::methodError('CitiesModel::save'));
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Сохраняет country в случае, если его нет в БД
     * @param object $country CountriesModel
     */
    protected static function saveCheckCountry(CountriesModel $country)
    {
        try {
            if (!(new CountryExistsCreateValidator())->validate($country['country'])) {
                if (!$country->save(false)) {
                    throw new ErrorException(ExceptionsTrait::methodError('CountriesModel::save'));
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Сохраняет postcode в случае, если его нет в БД
     * @param object $postcode PostcodesModel
     */
    protected static function saveCheckPostcode(PostcodesModel $postcode)
    {
        try {
            if (!(new PostcodeExistsCreateValidator())->validate($postcode['postcode'])) {
                if (!$postcode->save(false)) {
                    throw new ErrorException(ExceptionsTrait::methodError('PostcodesModel::save'));
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив MailingListModel
     * @param array $diff массив MailingListModel::id
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    protected static function getMailing(array $diff, $asArray=false): array
    {
        try {
            $mailingListQuery = MailingListModel::find();
            $mailingListQuery->extendSelect(['name']);
            $mailingListQuery->where(['[[mailing_list.id]]'=>$diff]);
            if (!empty($asArray)) {
                $mailingListQuery->asArray();
            }
            $mailingListArray = $mailingListQuery->all();
            
            return $mailingListArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает форматированный массив MailingListModel, где 
     * id записи является ключом, а name значением массива
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    protected static function getMailingListMap($asArray=false): array
    {
        try {
            $mailingListQuery = MailingListModel::find();
            $mailingListQuery->extendSelect(['id', 'name']);
            if (!empty($asArray)) {
                $mailingListQuery->asArray();
            }
            $mailingListArray = $mailingListQuery->all();
            $mailingListArray = ArrayHelper::map($mailingListArray, 'id', 'name');
            asort($mailingListArray, SORT_STRING);
            
            return $mailingListArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект/массив UsersModel
     * @param int $id_email
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return mixed
     */
    protected static function getUser(int $id_email, $asArray=false)
    {
        try {
            $usersQuery = UsersModel::find();
            $usersQuery->extendSelect(['id', 'id_email']);
            $usersQuery->where(['[[users.id_email]]'=>$id_email]);
            if (!empty($asArray)) {
                $usersQuery->asArray();
            }
            $usersModel = $usersQuery->one();
            
            return $usersModel;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект UsersModel
     * @param string $email
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return object UsersModel
     */
    protected static function getUserJoin(string $email, $asArray=false): UsersModel
    {
        try {
            $usersQuery = UsersModel::find();
            $usersQuery->extendSelect(['id', 'id_name', 'id_email', 'password']);
            $usersQuery->innerJoin('{{emails}}', '[[emails.id]]=[[users.id_email]]');
            $usersQuery->where(['[[emails.email]]'=>$email]);
            if (!empty($asArray)) {
                $usersQuery->asArray();
            }
            $usersModel = $usersQuery->one();
            
            return $usersModel;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект/массив EmailsModel
     * @param string $email
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return mixed
     */
    protected static function getEmail(string $email, $asArray=false)
    {
        try {
            $emailsQuery = EmailsModel::find();
            $emailsQuery->extendSelect(['id', 'email']);
            $emailsQuery->where(['[[emails.email]]'=>$email]);
            if (!empty($asArray)) {
                $emailsQuery->asArray();
            }
            $emailsModel = $emailsQuery->one();
            
            return $emailsModel;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив данных SubcategoryModel
     * @param int $id_category Categories::id
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    protected static function getSubcategory(int $id_category, $asArray=false): array
    {
        try {
            $subcategoryQuery = SubcategoryModel::find();
            $subcategoryQuery->extendSelect(['id', 'name']);
            $subcategoryQuery->where(['[[subcategory.id_category]]'=>$id_category]);
            if (!empty($asArray)) {
                $subcategoryQuery->asArray();
            }
            $subcategoryArray = $subcategoryQuery->all();
            $subcategoryArray = ArrayHelper::map($subcategoryArray, 'id', 'name');
            asort($subcategoryArray, SORT_STRING);
            
            return $subcategoryArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект NamesModel
     * @param string $name
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return mixed
     */
    protected static function getName(string $name, $asArray=false)
    {
        try {
            $namesQuery = NamesModel::find();
            $namesQuery->extendSelect(['id', 'name']);
            $namesQuery->where(['[[names.name]]'=>$name]);
            if (!empty($asArray)) {
                $namesQuery->asArray();
            }
            $namesModel = $namesQuery->one();
            
            return $namesModel;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект SurnamesModel
     * @param string $surname
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return mixed
     */
    protected static function getSurname(string $surname, $asArray=false)
    {
        try {
            $surnamesQuery = SurnamesModel::find();
            $surnamesQuery->extendSelect(['id', 'surname']);
            $surnamesQuery->where(['[[surnames.surname]]'=>$surname]);
            if (!empty($asArray)) {
                $surnamesQuery->asArray();
            }
            $surnamesModel = $surnamesQuery->one();
            
            return $surnamesModel;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект PhonesModel
     * @param string $phone
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return mixed
     */
    protected static function getPhone(string $phone, $asArray=false)
    {
        try {
            $phonesQuery = PhonesModel::find();
            $phonesQuery->extendSelect(['id', 'phone']);
            $phonesQuery->where(['[[phones.phone]]'=>$phone]);
            if (!empty($asArray)) {
                $phonesQuery->asArray();
            }
            $phonesModel = $phonesQuery->one();
            
            return $phonesModel;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект AddressModel
     * @param string $address
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return mixed
     */
    protected static function getAddress(string $address, $asArray=false)
    {
        try {
            $addressQuery = AddressModel::find();
            $addressQuery->extendSelect(['id', 'address']);
            $addressQuery->where(['[[address.address]]'=>$address]);
            if (!empty($asArray)) {
                $addressQuery->asArray();
            }
            $addressModel = $addressQuery->one();
            
            return $addressModel;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект CitiesModel
     * @param string $city
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return mixed
     */
    protected static function getCity(string $city, $asArray=false)
    {
        try {
            $citiesQuery = CitiesModel::find();
            $citiesQuery->extendSelect(['id', 'city']);
            $citiesQuery->where(['[[cities.city]]'=>$city]);
            if (!empty($asArray)) {
                $citiesQuery->asArray();
            }
            $citiesModel = $citiesQuery->one();
            
            return $citiesModel;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект CountriesModel
     * @param string $country
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return mixed
     */
    protected static function getCountry(string $country, $asArray=false)
    {
        try {
            $countriesQuery = CountriesModel::find();
            $countriesQuery->extendSelect(['id', 'country']);
            $countriesQuery->where(['[[countries.country]]'=>$country]);
            if (!empty($asArray)) {
                $countriesQuery->asArray();
            }
            $countriesModel = $countriesQuery->one();
            
            return $countriesModel;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект PostcodesModel
     * @param string $postcode
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return mixed
     */
    protected static function getPostcode(string $postcode, $asArray=false)
    {
        try {
            $postcodesQuery = PostcodesModel::find();
            $postcodesQuery->extendSelect(['id', 'postcode']);
            $postcodesQuery->where(['[[postcodes.postcode]]'=>$postcode]);
            $postcodesQuery->asArray();
            $postcodesModel = $postcodesQuery->one();
            
            return $postcodesModel;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
