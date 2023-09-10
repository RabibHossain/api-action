<?php

namespace Rabibgalib\ApiAction\ActionExecute\Traits;

use Illuminate\Support\Str;

trait SourceFileTrait
{

    /**
     * @param $app_name
     * @return array|string|string[]|null
     */
    public function removePunctuation($app_name): array|string|null
    {
        $name = preg_replace("/(?![.=$'€%-])\p{P}/u", "", $app_name);
        return str_replace(' ', '', $name);
    }

    /**
     **
     * Map the stub variables present in stub to its value
     *
     * @param $name
     * @param $type
     * @return array
     */
    public function getStubActionVariables($name, $type): array
    {
        $arr = explode("/", $name);
        $count = count($arr);
        $app_name = "ApiAction";
        switch ($type) {
            case 'controller':
                $namespace = "App\\Http\\Controllers{$this->breakData($arr)}";
                $form_request = "App\\Http\\Requests\\{$this->getSingularClassName($arr[$count-1])}Request";
                $helper_class = "App\\{$app_name}Packages\\{$this->getSingularClassName($arr[$count-1])}\\Helpers\\{$this->getSingularClassName($arr[$count-1])}Helper";
                $create_action = "App\\{$app_name}Packages\\{$this->getSingularClassName($arr[$count-1])}\\Actions\\Create{$this->getSingularClassName($arr[$count-1])}";
                $update_action = "App\\{$app_name}Packages\\{$this->getSingularClassName($arr[$count-1])}\\Actions\\Update{$this->getSingularClassName($arr[$count-1])}";
                $list_action = "App\\{$app_name}Packages\\{$this->getSingularClassName($arr[$count-1])}\\Actions\\List{$this->getSingularClassName($arr[$count-1])}";
                $find_action = "App\\{$app_name}Packages\\{$this->getSingularClassName($arr[$count-1])}\\Actions\\Find{$this->getSingularClassName($arr[$count-1])}";
                $delete_action = "App\\{$app_name}Packages\\{$this->getSingularClassName($arr[$count-1])}\\Actions\\Delete{$this->getSingularClassName($arr[$count-1])}";
                break;
            case 'request':
                $namespace = "App\\Http\\Requests";
                break;
            case 'trait':
                $namespace = "App\\{$app_name}Packages\\Traits";
                break;
            case 'helper':
                $namespace = "App\\{$app_name}Packages\\{$this->getSingularClassName($arr[$count-1])}\\Helpers";
                $trait_response = "App\\{$app_name}Packages\\Traits\\ApiResponse";
                $helper_name = $this->getSingularClassName($arr[$count-1]);
                $model_name_first = lcfirst($this->getSingularClassName($arr[$count-1]));
                $model_dir = "App\\Models\\" . $this->getSingularClassName($arr[$count-1]);
                break;
            case 'model':
                $namespace = "App\\Models";
                break;
            case 'migration':
                $make_plural = Str::plural($this->getSingularClassName($arr[$count-1]));
                $table_name = strtolower(preg_replace('/(.)([A-Z])/', '$1_$2', $make_plural));
                break;
            case 'action-create':
            case 'action-update':
            case 'action-list':
            case 'action-find':
            case 'action-delete':
                $namespace = "App\\{$app_name}Packages\\{$this->getSingularClassName($arr[$count-1])}\\Actions";
                $trait_response = "App\\{$app_name}Packages\\Traits\\ApiResponse";
                break;
        }

        return [
            'NAME_SPACE'         => $namespace ?? null,
            'CLASS_NAME'        => $this->getSingularClassName($arr[$count-1]),
            'TRAIT_RESPONSE'    => $trait_response ?? null,
            'FORM_REQUEST'      => $form_request ?? null,
            'HELPER_SOURCE'     => $helper_class ?? null,
            'CREATE_ACTION'     => $create_action ?? null,
            'UPDATE_ACTION'     => $update_action ?? null,
            'LIST_ACTION'       => $list_action ?? null,
            'FIND_ACTION'       => $find_action ?? null,
            'DELETE_ACTION'     => $delete_action ?? null,
            'HELPER_NAME'       => $helper_name ?? null,
            'MODEL_NAME'        => $helper_name ?? null,
            'MODEL_NAME_FIRST'  => $model_name_first ?? null,
            'MODEL_DIR'         => $model_dir ?? null,
            'TABLE_NAME'        => $table_name ?? null,
        ];
    }

    /**
     * Return the stub file path
     * @param $type
     * @return string|null
     */
    public function getStubPath($type): ?string
    {
        switch ($type) {
            case 'controller':
                $path = __DIR__ . '/../../Skeleton/controller.actionable.stub';
                break;
            case 'request':
                $path = __DIR__ . '/../../Skeleton/create.request.stub';
                break;
            case 'trait':
                $path = __DIR__ . '/../../Skeleton/trait.api.response.stub';
                break;
            case 'helper':
                $path = __DIR__ . '/../../Skeleton/helper.stub';
                break;
            case 'model':
                $path = __DIR__ . '/../../Skeleton/create.model.stub';
                break;
            case 'migration':
                $path = __DIR__ . '/../../Skeleton/create.migration.stub';
                break;
            case 'action-create':
                $path = __DIR__ . '/../../Skeleton/create.action.stub';
                break;
            case 'action-update':
                $path = __DIR__ . '/../../Skeleton/update.action.stub';
                break;
            case 'action-list':
                $path = __DIR__ . '/../../Skeleton/list.action.stub';
                break;
            case 'action-find':
                $path = __DIR__ . '/../../Skeleton/find.action.stub';
                break;
            case 'action-delete':
                $path = __DIR__ . '/../../Skeleton/delete.action.stub';
                break;
        }

        return $path ?? null;
    }

    /**
     * Replace the stub variables(key) with the desire value
     *
     * @param $stub
     * @param array $stubVariables
     * @return array|false|string|string[]
     */
    public function getStubContents($stub , array $stubVariables = []): array|bool|string
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace)
        {
            $contents = str_replace('$'.$search.'$' , $replace, $contents);
        }

        return $contents;
    }

    /**
     * Get the stub path and the stub variables
     *
     * @param $name
     * @param $type
     * @return string|array|bool
     */
    public function getSourceFile($name, $type): string|array|bool
    {
        return $this->getStubContents($this->getStubPath($type), $this->getStubActionVariables($name, $type));
    }
}
