<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\db\ActiveRecord;
use yii\helpers\{Html,
    Url};
use app\handlers\AbstractBaseHandler;
use app\finders\{AdminUsersCsvFinder,
    UsersFiltersSessionFinder};
use app\helpers\HashHelper;

/**
 * Обрабатывает запрос на сохранение пользователей в формате csv
 */
class CsvGetUsersRequestHandler extends AbstractBaseHandler
{
    /**
     * @var string путь к файлу
     */
    private $path;
    /**
     * @var дескриптор файла
     */
    private $file;
    
    /**
     * Обрабатывает запрос
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if ($request->isAjax === true) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                
                $filename = sprintf('users%s.csv', time());
                $this->path = \Yii::getAlias('@csvroot/users/' . $filename);
                $this->file = fopen($this->path, 'w');
                
                $finder = \Yii::$app->registry->get(UsersFiltersSessionFinder::class, [
                    'key'=>HashHelper::createHash([\Yii::$app->params['usersFilters']])
                ]);
                $filtersModel = $finder->find();
                if (empty($filtersModel)) {
                    throw new ErrorException($this->emptyError('filtersModel'));
                }
                
                $finder = \Yii::$app->registry->get(AdminUsersCsvFinder::class, [
                    'filters'=>$filtersModel
                ]);
                $usersQuery = $finder->find();
                
                $this->writeHeaders();
                
                foreach ($usersQuery->each(10) as $user) {
                    $this->writeUser($user);
                }
                
                fclose($this->file);
                
                return Html::a($filename, Url::to(sprintf('@csvweb/users/%s', $filename)));
            }
        } catch (\Throwable $t) {
            $this->cleanCsv();
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Пишет строку заголовков
     */
    private function writeHeaders()
    {
        try {
            $array = [
                \Yii::t('base', 'User Id'),
                \Yii::t('base', 'Email'),
                \Yii::t('base', 'Name'),
                \Yii::t('base', 'Surname'),
                \Yii::t('base', 'Phone'),
                \Yii::t('base', 'Address'),
                \Yii::t('base', 'City'),
                \Yii::t('base', 'Country'),
                \Yii::t('base', 'Postcode'),
                \Yii::t('base', 'Orders'),
            ];
            
            $this->write($array);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Пишет данные пользователя
     * @param ActiveRecord $user
     */
    private function writeUser(ActiveRecord $user)
    {
        try {
            $array = [];
            
            $array[] = $user->id;
            $array[] = $user->email->email;
            $array[] = $user->name->name;
            $array[] = $user->surname->surname;
            $array[] = $user->phone->phone;
            $array[] = $user->address->address;
            $array[] = $user->city->city;
            $array[] = $user->country->country;
            $array[] = $user->postcode->postcode;
            $array[] = count($user->orders);
            
            $this->write($array);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Пишет данные
     * @param array $array 
     */
    private function write(array $array)
    {
        try {
            if (fputcsv($this->file, $array) === false) {
                throw new ErrorException($this->methodError('fputcsv'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Удаляет созданный файл в случае ошибки
     * @param string $path путь к файлу
     */
    private function cleanCsv()
    {
        try {
            if (file_exists($this->path)) {
                unlink($this->path);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
