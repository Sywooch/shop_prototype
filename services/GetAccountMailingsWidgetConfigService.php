<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\MailingsEmailFinder;

/**
 * Возвращает массив конфигурации для виджета MailingsWidget
 */
class GetAccountMailingsWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета MailingsWidget
     */
    private $mailingsWidgetArray = [];
    
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
            
            if (empty($this->mailingsWidgetArray)) {
                $dataArray = [];
                
                $user = \Yii::$app->user->identity;
                
                $finder = \Yii::$app->registry->get(MailingsEmailFinder::class, ['email'=>$user->email->email]);
                $dataArray['mailings'] = $finder->find();
                
                $dataArray['header'] = \Yii::t('base', 'Current subscriptions');
                
                $dataArray['view'] = 'mailings.twig';
                
                $this->mailingsWidgetArray = $dataArray;
            }
            
            return $this->mailingsWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
