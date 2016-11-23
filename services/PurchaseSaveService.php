<?php

namespace app\services;

use yii\base\{ErrorException,
    Model,
    Object};
use app\exceptions\ExceptionsTrait;
use app\repositories\RepositoryInterface;
use app\services\SaveServiceInterface;
use app\helpers\HashHelper;

class PurchaseSaveService extends Object implements SaveServiceInterface
{
    use ExceptionsTrait;
    
     /**
     * @var object RepositoryInterface для сохранения данных
     */
    private $repository;
    /**
     * @var object Model модель, которая примет данные добавляемого в корзину товара
     */
    private $model;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->repository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('repository'));
            }
            if (empty($this->model)) {
                throw new ErrorException(ExceptionsTrait::emptyError('model'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на добавление товара в корзину
     * @param object Request
     */
    public function save($request)
    {
        try {
            if (empty($this->model->load($request))) {
                throw new ErrorException(ExceptionsTrait::emptyError('request'));
            }
            if ($this->model->validate() === false) {
                throw new ErrorException(ExceptionsTrait::emptyError('validate'));
            }
            
            $key = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
            $collection = $this->repository->getGroup($key);
            
            if ($collection->isEmpty()) {
                $collection->add($this->model);
            } else {
                if (!empty($currentEntity = $collection->getByKey('id_product', $this->model->id_product))) {
                    $currentHash = HashHelper::createHash([$currentEntity->id_product, $currentEntity->id_color, $currentEntity->id_size]);
                    $newHash = HashHelper::createHash([$this->model->id_product, $this->model->id_color, $this->model->id_size]);
                    
                    if ($currentHash !== $newHash) {
                        $collection->add($this->model);
                    } else {
                        $currentEntity->quantity += $this->model->quantity;
                    }
                } else {
                    $collection->add($this->model);
                }
            }
            
            $this->repository->saveOne($key, $collection->getArray());
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RepositoryInterface свойству PurchaseSaveService::repository
     * @param object $repository RepositoryInterface
     */
    public function setRepository(RepositoryInterface $repository)
    {
        try {
            $this->repository = $repository;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Model свойству PurchaseSaveService::model
     * @param object $model Model
     */
    public function setModel(Model $model)
    {
        try {
            $this->model = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
