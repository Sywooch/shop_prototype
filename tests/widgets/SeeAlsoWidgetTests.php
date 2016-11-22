<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\widgets\{SeeAlsoWidget,
    SeeAlsoRelatedWidget,
    SeeAlsoSimilarWidget};
use app\repositories\{AbstractBaseRepository,
    RepositoryInterface};
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture,
    ProductsColorsFixture,
    ProductsSizesFixture,
    RelatedProductsFixture};
use app\models\{ProductsModel,
    QueryCriteria};

class SeeAlsoWidgetTests extends TestCase
{
    private static $dbClass;
    private $repository;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'related'=>RelatedProductsFixture::class,
                'currency'=>CurrencyFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
                'products_sizes'=>ProductsSizesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->repository = new class () extends AbstractBaseRepository implements RepositoryInterface {
            private $criteria;
            
            public function getGroup($request=null)
            {
                $query = ProductsModel::find();
                $query = $this->addCriteria($query);
                $data = $query->all();
                return $data;
            }
            public function getOne($request=null)
            {
                
            }
            public function getCriteria()
            {
                if (empty($this->criteria)) {
                    $this->criteria = new QueryCriteria();
                }
                return $this->criteria;
            }
        };
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::setRepository
     * вызываю с пустым $repository
     * @expectedException yii\base\ErrorException
     */
    public function testSetRepositoryEmpty()
    {
        $result = SeeAlsoWidget::widget([]);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::setRepository
     * передаю не поддерживающий RepositoryInterface объект
     * @expectedException TypeError
     */
    public function testSetRepositoryError()
    {
        $result = new SeeAlsoWidget([
            'repository'=>new class() {},
        ]);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::setModel
     * вызываю с пустым $model
     * @expectedException yii\base\ErrorException
     */
    public function testSetModelEmpty()
    {
        $result = SeeAlsoWidget::widget([
            'repository'=>$this->repository,
        ]);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::setModel
     * передаю не наследующий Model объект
     * @expectedException TypeError
     */
    public function testSetModelError()
    {
        $result = new SeeAlsoWidget([
            'repository'=>$this->repository,
            'model'=>new class() {},
        ]);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::widget
     * вызываю с пустым $text
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetTextEmpty()
    {
        $result = SeeAlsoWidget::widget([
            'repository'=>$this->repository,
            'model'=>new class() extends Model {},
        ]);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::widget
     * вызываю с пустым $view
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetViewEmpty()
    {
        $result = SeeAlsoWidget::widget([
            'repository'=>$this->repository,
            'model'=>new class() extends Model {},
            'text'=>'Text'
        ]);
    }
    
    /**
     * Тестирует метод SeeAlsoRelatedWidget::widget
     */
    public function testSeeAlsoRelatedWidget()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set(\Yii::$app->params['currencyKey'], self::$dbClass->currency['currency_1']);
        $session->close();
        
        $result = SeeAlsoRelatedWidget::widget([
            'repository'=>$this->repository,
            'model'=>ProductsModel::find()->where(['[[id]]'=>1])->one(),
            'text'=>\Yii::t('base', 'Related products:'),
            'view'=>'see-also.twig'
        ]);
        
        $this->assertEquals(1, preg_match('/<p><strong>'. \Yii::t('base', 'Related products:') . '<\/strong><\/p>/', $result));
        $this->assertEquals(1, preg_match('/<a href=".+">.+<\/a>/', $result));
        $this->assertEquals(1, preg_match('/'. \Yii::t('base', 'Price:') . ' [0-9,]+ ' . self::$dbClass->currency['currency_1']['code'] . '/', $result));
    }
    
    /**
     * Тестирует метод SeeAlsoSimilarWidget::widget
     */
    public function testSeeAlsoSimilarWidget()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set(\Yii::$app->params['currencyKey'], self::$dbClass->currency['currency_1']);
        $session->close();
        
        $result = SeeAlsoSimilarWidget::widget([
            'repository'=>$this->repository,
            'model'=>ProductsModel::find()->where(['[[id]]'=>2])->one(),
            'text'=>\Yii::t('base', 'Similar products:'),
            'view'=>'see-also.twig'
        ]);
        
        $this->assertEquals(1, preg_match('/<p><strong>'. \Yii::t('base', 'Similar products:') . '<\/strong><\/p>/', $result));
        $this->assertEquals(1, preg_match('/<a href=".+">.+<\/a>/', $result));
        $this->assertEquals(1, preg_match('/'. \Yii::t('base', 'Price:') . ' [0-9,]+ ' . self::$dbClass->currency['currency_1']['code'] . '/', $result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
