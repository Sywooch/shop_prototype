<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use app\exceptions\ExceptionsTrait;
use app\repository\GetOneRepositoryInterface;

/**
 * Формирует HTML строку с информацией о текущем статусе аутентификации
 */
class UserInfoWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object GetOneRepositoryInterface для поиска данных по запросу
     */
    private $repository;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    /**
     * Конструирует HTML строку с информацией о текущем пользователе
     * @return string
     */
    public function run()
    {
        try {
            if (\Yii::$app->user->isGuest == false) {
                if ($user = $this->repository->getOne(\Yii::$app->params['userKey'])) {
                    $authenticated = true;
                }
            }
            
            return $this->render($this->view, ['user'=>$user ?? \Yii::t('base', 'Guest'), 'authenticated'=>$authenticated ?? false]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function setRepository(GetOneRepositoryInterface $repository)
    {
        try {
            $this->repository = $repository;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
