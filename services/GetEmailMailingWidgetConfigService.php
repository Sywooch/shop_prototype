<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\MailingsIdFinder;
use app\helpers\HashHelper;

/**
 * Возвращает конфигурацию для виджета EmailMailingWidget
 */
class GetEmailMailingWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета EmailMailingWidget
     */
    private $emailMailingWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета EmailMailingWidget
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            $email = $request['email'] ?? null;
            $diffIdArray = $request['diffIdArray'] ?? null;
            
            if (empty($email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            if (empty($diffIdArray)) {
                throw new ErrorException($this->emptyError('diffIdArray'));
            }
            
            if (empty($this->emailMailingWidgetArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(MailingsIdFinder::class, ['id'=>$diffIdArray]);
                $dataArray['mailings'] = $finder->find();
                
                $dataArray['email'] = $email;
                $dataArray['key'] = HashHelper::createHash([$email]);
                $dataArray['view'] = 'email-mailings-subscribe-success.twig';
                
                $this->emailMailingWidgetArray = $dataArray;
            }
            
            return $this->emailMailingWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
