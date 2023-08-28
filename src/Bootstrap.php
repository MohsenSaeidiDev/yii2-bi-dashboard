<?php

namespace sadi01\bidashboard;

use sadi01\bidashboard\components\Env;
use sadi01\bidashboard\components\Pdate;
use WebApplication;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\i18n\PhpMessageSource;
use yii\web\HttpException;

class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        $app->set('pdate', [
            'class' => Pdate::class,
        ]);
        if (!(Yii::$app->params['bi_slave_id'] ?? null)) {
            throw new InvalidConfigException(Yii::t('biDashboard', 'The bi_slave_id parameter is not set or empty.'));
        }
        Yii::$app->params['bsVersion'] = 4;

        if (!isset($app->get('i18n')->translations['biDashboard*'])) {
            $app->get('i18n')->translations['biDashboard*'] = [
                'class' => PhpMessageSource::class,
                'basePath' => __DIR__ . '/messages',
                'sourceLanguage' => 'en-US',
                'fileMap' => [
                    'biDashboard' => 'main.php',
                ],
            ];
        }

        $parameter = null;
        if (!Env::get('BI_DB_DSN')) {
            $parameter = 'BI_DB_DSN';
        } elseif (!Env::get('BI_DB_USERNAME')) {
            $parameter = 'BI_DB_USERNAME';
        } elseif (!Env::get('BI_DB_PASSWORD')) {
            $parameter = 'BI_DB_PASSWORD';
        }
        if ($parameter) {
            throw new InvalidConfigException(Yii::t('biDashboard', 'The {env_parameter} parameter is not set, add the parameter in the env file of the project.', ['env_parameter' => $parameter]));
        }

        Yii::$app->setComponents([
            'biDB' => [
                'class' => 'yii\db\Connection',
                'dsn' => Env::get('BI_DB_DSN'),
                'username' => Env::get('BI_DB_USERNAME'),
                'password' => Env::get('BI_DB_PASSWORD'),
                'charset' => Env::get('BI_DB_CHARSET'),
                'tablePrefix' => Env::get('BI_DB_TABLE_PREFIX'),
                'enableQueryCache' => Env::get('BI_DB_ENABLE_QUERY_CACHE', true),
                'queryCacheDuration' => Env::get('BI_DB_QUERY_CACHE_DURATION', 5), // five seconds
                'enableSchemaCache' => Env::get('BI_DB_ENABLE_SCHEMA_CACHE', true),
                'schemaCacheDuration' => Env::get('BI_DB_SCHEMA_CACHE_DURATION', 86400), // 3600*24, 1DAY
                'schemaCache' => Env::get('BI_DB_SCHEMA_CACHE_COMPONENT', 'cache'),

            ],
        ]);

        Yii::$app->setModules([
            'gridview' => [
                'class' => 'kartik\grid\Module',
            ],
        ]);

    }
}