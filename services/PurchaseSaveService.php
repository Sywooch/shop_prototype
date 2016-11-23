<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use app\exceptions\ExceptionsTrait;
use app\repositories\RepositoryInterface;
use app\services\SaveServiceInterface;
use app\helpers\HashHelper;
use app\models\FormInterface;

class PurchaseSaveService extends Object implements SaveServiceInterface
{
    use ExceptionsTrait;
    
     /**
     * @var object RepositoryInterface для сохранения данных
     */
    private $repository;
    /**
     * @var object FormInterface модель формы с данными добавляемого в корзину товара
     */
    private $form;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->repository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('repository'));
            }
            if (empty($this->form)) {
                throw new ErrorException(ExceptionsTrait::emptyError('form'));
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
            if (empty($this->form->load($request))) {
                throw new ErrorException(ExceptionsTrait::emptyError('request'));
            }
            if ($this->form->validate() === false) {
                throw new ErrorException(ExceptionsTrait::emptyError('validate'));
            }
            
            $model = $this->form->getModel();
            
            $key = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
            $collection = $this->repository->getGroup($key);
            
            if ($collection->hasEntity($model) === true) {
                $collection->update($model);
            } else {
                $collection->add($model);
            }
            
            $this->repository->saveGroup($key);
            
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
     * Присваивает FormInterface свойству PurchaseSaveService::form
     * @param object $form FormInterface
     */
    public function setForm(FormInterface $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
