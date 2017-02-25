<?php

namespace app\finders;

use yii\base\ErrorException;
use yii\db\ActiveQuery;
use app\finders\AbstractBaseFinder;
use app\models\UsersModel;
use app\filters\UsersFiltersInterface;

/**
 * Возвращает заказы из СУБД
 */
class AdminUsersCsvFinder extends AbstractBaseFinder
{
    /**
     * @var UsersFiltersInterface
     */
    private $filters;
    /**
     * @var PurchasesCollection
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return ActiveQuery
     */
    public function find(): ActiveQuery
    {
        try {
            if (empty($this->filters)) {
                throw new ErrorException($this->emptyError('filters'));
            }
            
            if (empty($this->storage)) {
                $query = UsersModel::find();
                $query->select(['[[users.id]]', '[[users.id_email]]', '[[users.id_name]]', '[[users.id_surname]]', '[[users.id_phone]]', '[[users.id_address]]', '[[users.id_city]]', '[[users.id_country]]', '[[users.id_postcode]]']);
                $query->addSelect(['name'=>'names.name', 'surname'=>'surnames.surname']);
                $query->addSelect(['orders'=>'(SELECT COUNT(*) FROM {{purchases}} WHERE [[purchases.id_user]]=[[users.id]])']);
                $query->leftJoin('{{names}}', '[[users.id_name]]=[[names.id]]');
                $query->leftJoin('{{surnames}}', '[[users.id_surname]]=[[surnames.id]]');
                $query->with('email', 'name', 'surname', 'phone', 'address', 'city', 'country', 'postcode', 'orders');
                
                if (!empty($this->filters->ordersStatus)) {
                    switch ($this->filters->ordersStatus) {
                        case 1:
                            $query->having(['>', '[[orders]]', 0]);
                            break;
                        case 2:
                            $query->having(['=', '[[orders]]', 0]);
                            break;
                    }
                }
                
                $sortingField = $this->filters->sortingField ?? \Yii::$app->params['sortingFieldUsers'];
                $sortingType = $this->filters->sortingType ?? \Yii::$app->params['sortingType'];
                $query->orderBy(['[[' . $sortingField . ']]'=>(int) $sortingType]);
                
                $this->storage = $query;
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает UsersFiltersInterface ProductsFinder::filters
     * @param UsersFiltersInterface $filters
     */
    public function setFilters(UsersFiltersInterface $filters)
    {
        try {
            $this->filters = $filters;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
