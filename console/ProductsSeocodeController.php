<?php

namespace app\console;

use yii\console\Controller;
use yii\helpers\Console;
use app\exceptions\ExceptionsTrait;
use app\models\ProductsModel;
use app\helpers\TransliterationHelper;

/**
 * Записывает и удаляет значение поля seocode таблицы products
 */
class ProductsSeocodeController extends Controller
{
    use ExceptionsTrait;
    
    /**
     * Заполняет значение поля seocode для всех записей
     */
    public function actionSet()
    {
        try {
            $productsArray = ProductsModel::find()->all();
            $this->stdout(\Yii::t('base/console', 'Fount {count} objects...' . PHP_EOL, ['count'=>count($productsArray)]));
            $this->stdout(\Yii::t('base/console', 'Begin update...' . PHP_EOL));
            foreach ($productsArray as $product) {
                $product->scenario = ProductsModel::GET_FROM_DB;
                $seocode = TransliterationHelper::getTransliterationSeparate($product->name);
                if (ProductsModel::find()->where(['seocode'=>$seocode])->exists()) {
                    $seocode .= '-' . $product->id;
                }
                $product->seocode = $seocode;
                $this->stdout('id: ' . $product->id . ', seocode: ' . $seocode . '' . PHP_EOL);
                $product->update();
            }
            $this->stdout(\Yii::t('base/console', 'Update successful!' . PHP_EOL));
            return parent::EXIT_CODE_NORMAL;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->stderr(\Y::t('base/console', 'Update error!' . PHP_EOL), Console::FG_RED);
            return parent::EXIT_CODE_ERROR;
        }
    }
    
    /**
     * Удаляет значение поля seocode для всех записей в БД
     */
    public function actionUnset()
    {
        try {
            $this->stdout(\Yii::t('base/console', 'Begin delete...' . PHP_EOL));
            \Yii::$app->db->createCommand()->update('products', ['seocode'=>''])->execute();
            $this->stdout(\Yii::t('base/console', 'Delete successful!' . PHP_EOL));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->stderr(\Y::t('base/console', 'Delete error!' . PHP_EOL), Console::FG_RED);
            return parent::EXIT_CODE_ERROR;
        }
    }
}
