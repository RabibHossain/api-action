<?php

namespace Rabibgalib\ApiAction\ActionExecute\Traits;

use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;

trait SourcePathTrait
{

    /**
     * @param array $arr
     * @return string
     */
    public function breakData(array $arr): string
    {
        array_pop($arr);
        $string = "";
        foreach ($arr as $item) {
            if (!empty($item)) {
                $string = $string . "\\$item";
            }
        }

        return $string;
    }

    /**
     * Return the Singular Capitalize Name
     * @param $name
     * @return string
     */
    public function getSingularClassName($name): string
    {
        return ucwords(Pluralizer::singular($name));
    }

    /**
     * @param $table_name
     * @return string|null
     */
    public function checkDuplicateTableNames($table_name): ?string
    {
        $files = scandir('database/migrations');

        foreach ($files as $file) {
            if (str_contains($file, $table_name)) {
                return $file;
            }
        }
        return null;
    }

    /**
     * Get the full path of generate class
     *
     * @param $name
     * @param $type
     * @return string|null
     */
    public function getSourceFilePath($name, $type): ?string
    {
        $arr = explode("/", $name);
        $count = count($arr);
        $app_name = "ApiAction";
        switch ($type) {
            case 'controller':
                $file_path = base_path("App\\Http\\Controllers{$this->breakData($arr)}") . '\\' .$this->getSingularClassName($arr[$count-1]) . 'Controller.php';
                break;
            case 'request':
                $file_path = base_path("App\\Http\\Requests\\{$this->getSingularClassName($arr[$count-1])}Request.php");
                break;
            case 'trait':
                $file_path = base_path("App\\{$app_name}Packages\\Traits") . '\\ApiResponse.php';
                break;
            case 'helper':
                $file_path = base_path("App\\{$app_name}Packages\\{$this->getSingularClassName($arr[$count-1])}\\Helpers") .'\\' .$this->getSingularClassName($arr[$count-1]) . 'Helper.php';
                break;
            case 'model':
                $file_path = base_path("App\\Models") .'\\' .$this->getSingularClassName($arr[$count-1]) . '.php';
                break;
            case 'migration':
                $prefix = date('Y_m_d_hms', strtotime(NOW()));
                $make_plural = Str::plural($this->getSingularClassName($arr[$count-1]));
                $table_name = strtolower(preg_replace('/(.)([A-Z])/', '$1_$2', $make_plural));
                if (!empty($this->checkDuplicateTableNames($table_name)))
                    $file_path = $this->checkDuplicateTableNames($table_name);
                else
                    $file_path = base_path("database\\migrations") .'\\' .$prefix . '_create_' . $table_name . '_table.php';
                break;
            case 'action-create':
                $file_path = base_path("App\\{$app_name}Packages\\{$this->getSingularClassName($arr[$count-1])}\\Actions") .'\\Create' .$this->getSingularClassName($arr[$count-1]) . '.php';
                break;
            case 'action-update':
                $file_path = base_path("App\\{$app_name}Packages\\{$this->getSingularClassName($arr[$count-1])}\\Actions") .'\\Update' .$this->getSingularClassName($arr[$count-1]) . '.php';
                break;
            case 'action-list':
                $file_path = base_path("App\\{$app_name}Packages\\{$this->getSingularClassName($arr[$count-1])}\\Actions") .'\\List' .$this->getSingularClassName($arr[$count-1]) . '.php';
                break;
            case 'action-find':
                $file_path = base_path("App\\{$app_name}Packages\\{$this->getSingularClassName($arr[$count-1])}\\Actions") .'\\Find' .$this->getSingularClassName($arr[$count-1]) . '.php';
                break;
            case 'action-delete':
                $file_path = base_path("App\\{$app_name}Packages\\{$this->getSingularClassName($arr[$count-1])}\\Actions") .'\\Delete' .$this->getSingularClassName($arr[$count-1]) . '.php';
                break;
        }

        return $file_path ?? null;
    }
}
