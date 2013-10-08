<?php
namespace app\commands;

use yii\gii\CodeFile;
use \Yii;

class Generator extends \yii\gii\generators\model\Generator {

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $files = array();
        $relations = $this->generateRelations();
        $db = $this->getDbConnection();
        foreach ($this->getTableNames() as $tableName) {
            $className = $this->generateClassName($tableName);
            $tableSchema = $db->getTableSchema($tableName);
            $params = array(
                    'tableName' => $tableName,
                    'className' => $className,
                    'tableSchema' => $tableSchema,
                    'labels' => $this->generateLabels($tableSchema),
                    'rules' => $this->generateRules($tableSchema),
                    'relations' => isset($relations[$className]) ? $relations[$className] : array(),
            );
            $files[] = new CodeFile(
                    Yii::getAlias('@' . str_replace('\\', '/', $this->ns . '\\base')) . '/' . $className . '.php',
                    $this->render('base.php', $params)
            );
            $files[] = new CodeFile(
                    Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $className . '.php',
                    $this->render('model.php', $params)
            );
        }

        return $files;
    }
}