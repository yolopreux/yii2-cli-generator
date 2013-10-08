<?php
namespace app\commands;

use yii\console\Controller;
use \Yii;
use yii\base\Model;
use app\models\Page;

class GenerateController extends Controller {

    /**
     * The default sub-command action
     *
     * @param string $table_name The db table name
     * @param string $model_class The model class name
     * @param boolean $skip_on_update The flag that indicates overwrite model class,
     * base class is overwiten always
     */
    public function actionIndex($table_name, $model_class, $skip_on_update = TRUE) {
        $this->actionModel($table_name, $model_class);
    }

    /**
     * Generate model
     *
     * @param string $table_name The db table name
     * @param string $model_class The model class name
     * @param boolean $skip_on_update The flag that indicates overwrite model class,
     * base class is overwiten always
     */
    public function actionModel($table_name, $model_class, $skip_on_update = TRUE) {
        $generator = new Generator();
        $generator->init();
        $generator->setAttributes(array('tableName' => $table_name,
             'modelClass' => $model_class, 'template' => 'default'));

        $files = $generator->generate();
        foreach ($files as $file) {
            if (!strpos($file->path, 'base') != FALSE && $skip_on_update && is_file($file->path)) {
                continue;
            }
            $dirPath = str_replace($model_class . '.php', '', $file->path);
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0755, TRUE);
            }
            if (file_put_contents($file->path, $file->content)) {
                print sprintf('%s::save %s', get_class($this), $file->path) . "\n";
            }
        }
    }

    /**
     * Generate models for all db tables
     * The class name is clasify by table name
     *
     * @param string $skip_on_update The flag that indicates overwrite model class,
     * base class is overwiten always
     */
    public function actionModels($skip_on_update = TRUE) {
        foreach (Yii::$app->db->schema->tableNames as $table) {
            if (strpos($table, 'migration') === FALSE) {
                $this->actionModel($table, self::clasify($table), $skip_on_update);
            }
        }
    }

    protected static function clasify($className) {
        $s = preg_replace('/[_-]+/','_',trim($className));
        $className = str_replace(' ', '_', $className);
        $camelized = '';
        for ($i=0,$n=strlen($className); $i<$n; ++$i) {
            if ($className[$i] == '_' && $i+1 < $n) {
                $camelized .= strtoupper($className[++$i]);
            } else {
                $camelized .= $className[$i];
            }
        }
        $camelized = trim($camelized,' _');
        if (strlen($camelized) > 0) {
            $camelized[0] = strtolower($camelized[0]);
        }

        return ucfirst($camelized);
    }

}