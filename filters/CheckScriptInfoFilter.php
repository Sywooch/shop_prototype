<?php

namespace app\filters;

use yii\base\ActionFilter;

/**
 * Фиксирует время генерации страницы, объем выделенной памяти и кол-во запросов к БД
 */
class CheckScriptInfoFilter extends ActionFilter
{
    private $_startTime;
    
    public function beforeAction($action)
    {
        $this->_startTime = microtime(true);
        return parent::beforeAction($action);
    }
    
    public function afterAction($action, $result)
    {
        $pageGenerated = microtime(true) - $this->_startTime;
        $memoryUsage = memory_get_usage(true);
        $sentRequests = \Yii::$app->params['fixSentRequests'];
        
        if (strpos($result, '</body>')) {
            $string = '<p>Page generated: ' . round($pageGenerated, 3) . ' sec. / Memory usage: ' . ($memoryUsage / (1024 * 1024)) . ' Mb / Sent Requests: ' . $sentRequests . '</p>';
            $result = str_replace('</body>', $string . '</body>', $result);
        }
        return $result;
    }
}
