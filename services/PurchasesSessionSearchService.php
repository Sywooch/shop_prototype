<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use app\exceptions\ExceptionsTrait;
use app\repository\GetGroupRepositoryInterface;
use app\services\SearchServiceInterface;
use app\helpers\HashHelper;

class PurchasesSessionSearchService extends Object implements SearchServiceInterface
{
    use ExceptionsTrait;
    
    private $repository;
    
    public function __construct(GetGroupRepositoryInterface $repository)
    {
        try {
            $this->repository = $repository;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function search($request=null)
    {
        try {
            $cartKey = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
            $data = $this->repository->getGroup($cartKey);
            
            return $data;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
