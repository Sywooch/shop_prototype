<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\MailingsEmailFinder;

/**
 * Возвращает массив конфигурации для виджета AccountMailingsWidget
 */
class GetAccountMailingsWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AccountMailingsWidget
     */
    private $accountMailingsWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (\Yii::$app->user->isGuest === true) {
                throw new ErrorException($this->emptyError('user'));
            }
            
            if (empty($this->accountMailingsWidgetArray)) {
                $dataArray = [];
                
                $user = \Yii::$app->user->identity;
                
                $finder = \Yii::$app->registry->get(MailingsEmailFinder::class, ['email'=>$user->email->email]);
                $dataArray['mailings'] = $finder->find();
                
                $dataArray['view'] = 'account-mailings.twig';
                
                $this->accountMailingsWidgetArray = $dataArray;
            }
            
            return $this->accountMailingsWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
