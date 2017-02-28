<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\db\ActiveRecord;
use yii\helpers\{Html,
    Url};
use app\handlers\AbstractBaseHandler;
use app\finders\{AdminCommentsCsvFinder,
    CommentsFiltersSessionFinder};
use app\helpers\HashHelper;

/**
 * Обрабатывает запрос на сохранение пользователей в формате csv
 */
class CsvGetCommentsRequestHandler extends AbstractBaseHandler
{
    /**
     * @var string путь к файлу
     */
    private $path;
    /**
     * @var дескриптор файла
     */
    private $file;
    
    /**
     * Обрабатывает запрос
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if ($request->isAjax === true) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                
                $filename = sprintf('comments%s.csv', time());
                $this->path = \Yii::getAlias('@csvroot/comments/' . $filename);
                $this->file = fopen($this->path, 'w');
                
                $finder = \Yii::$app->registry->get(CommentsFiltersSessionFinder::class, [
                    'key'=>HashHelper::createHash([\Yii::$app->params['commentsFilters']])
                ]);
                $filtersModel = $finder->find();
                if (empty($filtersModel)) {
                    throw new ErrorException($this->emptyError('filtersModel'));
                }
                
                $finder = \Yii::$app->registry->get(AdminCommentsCsvFinder::class, [
                    'filters'=>$filtersModel
                ]);
                $commentsQuery = $finder->find();
                if (empty($commentsQuery)) {
                    throw new ErrorException($this->emptyError('commentsQuery'));
                }
                
                $this->writeHeaders();
                
                foreach ($commentsQuery->each() as $comment) {
                    $this->writeComment($comment);
                }
                
                fclose($this->file);
                
                return Html::a($filename, Url::to(sprintf('@csvweb/comments/%s', $filename)));
            }
        } catch (\Throwable $t) {
            $this->cleanCsv();
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Пишет строку заголовков
     */
    private function writeHeaders()
    {
        try {
            $array = [
                \Yii::t('base', 'Comment Id'),
                \Yii::t('base', 'Date added'),
                \Yii::t('base', 'Comment text'),
                \Yii::t('base', 'Commentator'),
                \Yii::t('base', 'Email'),
                \Yii::t('base', 'Product'),
                \Yii::t('base', 'Active'),
            ];
            
            $this->write($array);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Пишет данные комментария
     * @param ActiveRecord $comment
     */
    private function writeComment(ActiveRecord $comment)
    {
        try {
            $array = [];
            
            $array[] = $comment->id;
            $array[] = \Yii::$app->formatter->asDate($comment->date);
            $array[] = $comment->text;
            $array[] = $comment->name->name;
            $array[] = $comment->email->email;
            $array[] = Url::to(['/product-detail/index', \Yii::$app->params['productKey']=>$comment->product->seocode], true);
            $array[] = $comment->active;
            
            $this->write($array);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Пишет данные
     * @param array $array 
     */
    private function write(array $array)
    {
        try {
            if (fputcsv($this->file, $array) === false) {
                throw new ErrorException($this->methodError('fputcsv'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Удаляет созданный файл в случае ошибки
     * @param string $path путь к файлу
     */
    private function cleanCsv()
    {
        try {
            if (file_exists($this->path)) {
                unlink($this->path);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
