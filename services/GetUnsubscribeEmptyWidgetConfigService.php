<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает массив данных для UnsubscribeEmptyWidget
 */
class GetUnsubscribeEmptyWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array данные для UnsubscribeEmptyWidget
     */
    private $unsubscribeEmptyWidgetArray = [];
    
    /**
     * Возвращает массив данных для UnsubscribeEmptyWidget
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $email = $request->get(\Yii::$app->params['emailKey']);
            
            if (empty($email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            
            if (empty($this->unsubscribeEmptyWidgetArray)) {
                $dataArray = [];
                
                $dataArray['email'] = $email;
                $dataArray['view'] = 'unsubscribe-empty.twig';
                
                $this->unsubscribeEmptyWidgetArray = $dataArray;
            }
            
            return $this->unsubscribeEmptyWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
