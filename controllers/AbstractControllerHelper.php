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
    BrandsModel,
    CategoriesModel,
    CitiesModel,
    ColorsModel,
    CountriesModel,
    CurrencyModel,
    EmailsModel,
    MailingsModel,
    NamesModel,
    PhonesModel,
    PostcodesModel,
    ProductsModel,
    SizesModel,
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
     * Возвращает массив UsersModel со всеми связанными данными
     * @param int $id UsersModel::id
     * @param int $id_email EmailsModel::id
     * @param bool $plus нужно ли возвращать дополнительные данные
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return mixed
     */
    protected static function getUserPlus(int $id, int $id_email, bool $plus=false, bool $asArray=false)
    {
        try {
            $usersQuery = UsersModel::find();
            $usersQuery->extendSelect(['id', 'id_name', 'id_surname', 'id_email', 'id_phone', 'id_address', 'id_city', 'id_country', 'id_postcode']);
            if (!empty($plus)) {
                $usersQuery->addSelect(['[[userName]]'=>'[[names.name]]', '[[userSurname]]'=>'[[surnames.surname]]', '[[userEmail]]'=>'[[emails.email]]', '[[userPhone]]'=>'[[phones.phone]]', '[[userAddress]]'=>'[[address.address]]', '[[userCity]]'=>'[[cities.city]]', '[[userCountry]]'=>'[[countries.country]]', '[[userPostcode]]'=>'[[postcodes.postcode]]']);
                $usersQuery->innerJoin('{{emails}}', '[[emails.id]]=[[users.id_email]]');
                $usersQuery->leftJoin('{{names}}', '[[names.id]]=[[users.id_name]]');
                $usersQuery->leftJoin('{{surnames}}', '[[surnames.id]]=[[users.id_surname]]');
                $usersQuery->leftJoin('{{phones}}', '[[phones.id]]=[[users.id_phone]]');
                $usersQuery->leftJoin('{{address}}', '[[address.id]]=[[users.id_address]]');
                $usersQuery->leftJoin('{{cities}}', '[[cities.id]]=[[users.id_city]]');
                $usersQuery->leftJoin('{{countries}}', '[[countries.id]]=[[users.id_country]]');
                $usersQuery->leftJoin('{{postcodes}}', '[[postcodes.id]]=[[users.id_postcode]]');
            }
            if (!empty($id)) {
                $usersQuery->where(['[[users.id]]'=>$id]);
            }
            if (!empty($id_email)) {
                $usersQuery->where(['[[users.id_email]]'=>$id_email]);
            }
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
    protected static function getUserByEmail(string $email, bool $asArray=false): UsersModel
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
     * Возвращает объект EmailsModel
     * @param string $email
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return mixed
     */
    protected static function getEmail(string $email, bool $asArray=false)
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
     * Возвращает массив SubcategoryModel
     * @param int $id_category Categories::id
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    protected static function getSubcategory(int $id_category, bool $asArray=false): array
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
    protected static function getName(string $name, bool $asArray=false)
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
    protected static function getSurname(string $surname, bool $asArray=false)
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
    protected static function getPhone(string $phone, bool $asArray=false)
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
    protected static function getAddress(string $address, bool $asArray=false)
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
    protected static function getCity(string $city, bool $asArray=false)
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
    protected static function getCountry(string $country, bool $asArray=false)
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
    protected static function getPostcode(string $postcode, bool $asArray=false)
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
    
    /**
     * Возвращает массив ProductsModel
     * @param array $id_products массив ProductsModel::id
     * @param bool $with флаг, определяющий необходимость загрузки связанные данные
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    protected static function getProducts(array $id_products=[], bool $with=false, bool $asArray=false): array
    {
        try {
            $productsQuery = ProductsModel::find();
            $productsQuery->extendSelect(['id', 'name', 'short_description', 'price', 'images', 'seocode']);
            if (!empty($id_products)) {
                $productsQuery->where(['[[products.id]]'=>$id_products]);
            }
            if (!empty($with)) {
                $productsQuery->with(['colors', 'sizes']);
            }
            if (!empty($asArray)) {
                $productsQuery->asArray();
            }
            $productsArray = $productsQuery->all();
            
            return $productsArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект ProductsModel
     * @param string $seocode ProductsModel::seocode
     * @param bool $with флаг, определяющий необходимость загрузки связанные данные
     * @param bool $plus нужно ли возвращать дополнительные данные
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return mixed
     */
    protected static function getProduct(string $seocode, bool $with=false, bool $plus=false, bool $asArray=false)
    {
        try {
            $productsQuery = ProductsModel::find();
            $productsQuery->extendSelect(['id', 'code', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'seocode']);
            if (!empty($plus)) {
                $productsQuery->addSelect(['[[categorySeocode]]'=>'[[categories.seocode]]', '[[categoryName]]'=>'[[categories.name]]', '[[subcategorySeocode]]'=>'[[subcategory.seocode]]', '[[subcategoryName]]'=>'[[subcategory.name]]']);
                $productsQuery->innerJoin('{{categories}}', '[[categories.id]]=[[products.id_category]]');
                $productsQuery->innerJoin('{{subcategory}}', '[[subcategory.id]]=[[products.id_subcategory]]');
            }
            $productsQuery->where(['[[products.seocode]]'=>$seocode]);
            if (!empty($with)) {
                $productsQuery->with(['colors', 'sizes']);
            }
            if (!empty($asArray)) {
                $productsQuery->asArray();
            }
            $productsModel = $productsQuery->one();
            
            return $productsModel;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив ProductsModel, имеющих схожие характеристики с переданным в параметрах
     * @param array/object ProductsModel
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    protected static function getSimilarProducts($productsModel, bool $asArray=false): array
    {
        try {
            $similarQuery = ProductsModel::find();
            $similarQuery->extendSelect(['name', 'price', 'images', 'seocode']);
            $similarQuery->distinct();
            $similarQuery->where(['!=', '[[products.id]]', $productsModel['id']]);
            $similarQuery->andWhere(['[[products.id_category]]'=>$productsModel['id_category']]);
            $similarQuery->andWhere(['[[products.id_subcategory]]'=>$productsModel['id_subcategory']]);
            $similarQuery->innerJoin('{{products_colors}}', '[[products.id]]=[[products_colors.id_product]]');
            $similarQuery->andWhere(['[[products_colors.id_color]]'=>ArrayHelper::getColumn($productsModel['colors'], 'id')]);
            $similarQuery->innerJoin('{{products_sizes}}', '[[products.id]]=[[products_sizes.id_product]]');
            $similarQuery->andWhere(['[[products_sizes.id_size]]'=>ArrayHelper::getColumn($productsModel['sizes'], 'id')]);
            $similarQuery->limit(\Yii::$app->params['similarLimit']);
            $similarQuery->orderBy(['[[products.date]]'=>SORT_DESC]);
            if (!empty($asArray)) {
                $similarQuery->asArray();
            }
            $similarArray = $similarQuery->all();
            
            return $similarArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив ProductsModel, связанных с переданным в параметрах
     * @param int $id ProductsModel::id
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    protected static function getRelatedProducts(int $id, bool $asArray=false): array
    {
        try {
            $relatedQuery = ProductsModel::find();
            $relatedQuery->extendSelect(['name', 'price', 'images', 'seocode']);
            $relatedQuery->innerJoin('{{related_products}}', '[[products.id]]=[[related_products.id_related_product]]');
            $relatedQuery->where(['[[related_products.id_product]]'=>$id]);
            if (!empty($asArray)) {
                $relatedQuery->asArray();
            }
            $relatedArray = $relatedQuery->all();
            ArrayHelper::multisort($relatedArray, 'date', SORT_DESC);
            
            return $relatedArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив ColorsModel
     * @param array $id_colors массив ColorsModel::id
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    protected static function getColors(array $id_colors=[], bool $asArray=false): array
    {
        try {
            $colorsQuery = ColorsModel::find();
            $colorsQuery->extendSelect(['id', 'color']);
            if (!empty($id_colors)) {
                $colorsQuery->where(['[[colors.id]]'=>$id_colors]);
            }
            if (!empty($asArray)) {
                $colorsQuery->asArray();
            }
            $colorsArray = $colorsQuery->all();
            
            return $colorsArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив ColorsModel
     * @params array $sphinxArray id товаров, найденные sphinx
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    protected static function getColorsJoinProducts(array $sphinxArray=[], bool $asArray=false): array
    {
        try {
            $colorsQuery = ColorsModel::find();
            $colorsQuery->extendSelect(['id', 'color']);
            $colorsQuery->distinct();
            $colorsQuery->innerJoin('{{products_colors}}', '[[colors.id]]=[[products_colors.id_color]]');
            $colorsQuery->innerJoin('{{products}}', '[[products_colors.id_product]]=[[products.id]]');
            $colorsQuery->where(['[[products.active]]'=>true]);
            
            if (!empty($sphinxArray)) {
                $colorsQuery->andWhere(['[[products.id]]'=>ArrayHelper::getColumn($sphinxArray, 'id')]);
            } else {
                if (\Yii::$app->request->get(\Yii::$app->params['categoryKey'])) {
                    $colorsQuery->innerJoin('{{categories}}', '[[products.id_category]]=[[categories.id]]');
                    $colorsQuery->andWhere(['[[categories.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
                    if (\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])) {
                        $colorsQuery->innerJoin('{{subcategory}}', '[[products.id_subcategory]]=[[subcategory.id]]');
                        $colorsQuery->andWhere(['[[subcategory.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
                    }
                }
            }
            
            if (!empty($asArray)) {
                $colorsQuery->asArray();
            }
            $colorsArray = $colorsQuery->all();
            
            return $colorsArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив SizesModel
     * @param array $id_sizes массив SizesModel::id
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    protected static function getSizes(array $id_sizes=[], bool $asArray=false): array
    {
        try {
            $sizesQuery = SizesModel::find();
            $sizesQuery->extendSelect(['id', 'size']);
            if (!empty($id_sizes)) {
                $sizesQuery->where(['[[sizes.id]]'=>$id_sizes]);
            }
            if (!empty($asArray)) {
                $sizesQuery->asArray();
            }
            $sizesArray = $sizesQuery->all();
            
            return $sizesArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив SizesModel
     * @params array $sphinxArray id товаров, найденные sphinx
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    protected static function getSizesJoinProducts(array $sphinxArray=[], bool $asArray=false): array
    {
        try {
            $sizesQuery = SizesModel::find();
            $sizesQuery->extendSelect(['id', 'size']);
            $sizesQuery->distinct();
            $sizesQuery->innerJoin('{{products_sizes}}', '[[sizes.id]]=[[products_sizes.id_size]]');
            $sizesQuery->innerJoin('{{products}}', '[[products_sizes.id_product]]=[[products.id]]');
            $sizesQuery->where(['[[products.active]]'=>true]);
            
            if (!empty($sphinxArray)) {
                $sizesQuery->andWhere(['[[products.id]]'=>ArrayHelper::getColumn($sphinxArray, 'id')]);
            } else {
                if (\Yii::$app->request->get(\Yii::$app->params['categoryKey'])) {
                    $sizesQuery->innerJoin('{{categories}}', '[[products.id_category]]=[[categories.id]]');
                    $sizesQuery->andWhere(['[[categories.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
                    if (\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])) {
                        $sizesQuery->innerJoin('{{subcategory}}', '[[products.id_subcategory]]=[[subcategory.id]]');
                        $sizesQuery->andWhere(['[[subcategory.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
                    }
                }
            }
            
            if (!empty($asArray)) {
                $sizesQuery->asArray();
            }
            $sizesArray = $sizesQuery->all();
            
            return $sizesArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект DeliveriesModel
     * @param int $id DeliveriesModel::id
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return mixed
     */
    protected static function getDelivery(int $id, bool $asArray=false)
    {
        try {
            $deliveriesQuery = DeliveriesModel::find();
            $deliveriesQuery->extendSelect(['id', 'name', 'description', 'price']);
            $deliveriesQuery->where(['[[deliveries.id]]'=>$id]);
            if (!empty($asArray)) {
                $deliveriesQuery->asArray();
            }
            $deliveriesModel = $deliveriesQuery->one();
            
            return $deliveriesModel;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект PaymentsModel
     * @param int $id PaymentsModel::id
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return mixed
     */
    protected static function getPayment(int $id, bool $asArray=false)
    {
        try {
            $paymentsQuery = PaymentsModel::find();
            $paymentsQuery->extendSelect(['id', 'name', 'description']);
            $paymentsQuery->where(['[[payments.id]]'=>$id]);
            if (!empty($asArray)) {
                $paymentsQuery->asArray();
            }
            $paymentsModel = $paymentsQuery->one();
            
            return $paymentsModel;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив DeliveriesModel
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    protected static function getDeliveries(bool $asArray=false): array
    {
        try {
            $deliveriesQuery = DeliveriesModel::find();
            $deliveriesQuery->extendSelect(['id', 'name', 'description', 'price']);
            if (!empty($asArray)) {
                $deliveriesQuery->asArray();
            }
            $deliveriesArray = $deliveriesQuery->all();
            ArrayHelper::multisort($deliveriesArray, 'name', SORT_ASC);
            
            return $deliveriesArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив PaymentsModel 
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    protected static function getPayments(bool $asArray=false): array
    {
        try {
            $paymentsQuery = PaymentsModel::find();
            $paymentsQuery->extendSelect(['id', 'name', 'description']);
            if (!empty($asArray)) {
                $paymentsQuery->asArray();
            }
            $paymentsArray = $paymentsQuery->all();
            ArrayHelper::multisort($paymentsArray, 'name', SORT_ASC);
            
            return $paymentsArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект CurrencyModel
     * @param int $id CurrencyModel::id
     * @return mixed
     */
    protected static function getCurrency(int $id, bool $asArray=false)
    {
        try {
            $currencyQuery = CurrencyModel::find();
            $currencyQuery->extendSelect(['id', 'code', 'exchange_rate']);
            $currencyQuery->where(['[[currency.id]]'=>$id]);
            if (!empty($asArray)) {
                $currencyQuery->asArray();
            }
            $currencyModel = $currencyQuery->one();
            
            return $currencyModel;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив BrandsModel
     * @params array $sphinxArray id товаров, найденные sphinx
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    protected static function getBrandsJoinProducts(array $sphinxArray=[], bool $asArray=false): array
    {
        try {
            $brandsQuery = BrandsModel::find();
            $brandsQuery->extendSelect(['id', 'brand']);
            $brandsQuery->distinct();
            $brandsQuery->innerJoin('{{products}}', '[[products.id_brand]]=[[brands.id]]');
            $brandsQuery->where(['[[products.active]]'=>true]);
            
            if (!empty($sphinxArray)) {
                $brandsQuery->andWhere(['[[products.id]]'=>ArrayHelper::getColumn($sphinxArray, 'id')]);
            } else {
                if (\Yii::$app->request->get(\Yii::$app->params['categoryKey'])) {
                    $brandsQuery->innerJoin('{{categories}}', '[[products.id_category]]=[[categories.id]]');
                    $brandsQuery->andWhere(['[[categories.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
                    if (\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])) {
                        $brandsQuery->innerJoin('{{subcategory}}', '[[products.id_subcategory]]=[[subcategory.id]]');
                        $brandsQuery->andWhere(['[[subcategory.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
                    }
                }
            }
            
            if (!empty($asArray)) {
                $brandsQuery->asArray();
            }
            $brandsArray = $brandsQuery->all();
            
            return $brandsArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив CategoriesModel
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    protected static function getCategories(bool $asArray=false): array
    {
        try {
            $categoriesQuery = CategoriesModel::find();
            $categoriesQuery->extendSelect(['id', 'name']);
            if (!empty($asArray)) {
                $categoriesQuery->asArray();
            }
            $categoriesArray = $categoriesQuery->all();
            
            return $categoriesArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив BrandsModel
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    protected static function getBrands(bool $asArray=false): array
    {
        try {
            $brandsQuery = BrandsModel::find();
            $brandsQuery->extendSelect(['id', 'brand']);
            if (!empty($asArray)) {
                $brandsQuery->asArray();
            }
            $brandsArray = $brandsQuery->all();
            
            return $brandsArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив MailingsModel
     * @param array $diff массив MailingsModel::id
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    protected static function getMailings(array $diff=[], bool $asArray=false): array
    {
        try {
            $mailingsQuery = MailingsModel::find();
            $mailingsQuery->extendSelect(['id', 'name', 'description']);
            if (!empty($diff)) {
                $mailingsQuery->where(['[[mailings.id]]'=>$diff]);
            }
            if (!empty($asArray)) {
                $mailingsQuery->asArray();
            }
            $mailingsArray = $mailingsQuery->all();
            
            return $mailingsArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
