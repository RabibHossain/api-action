<?php

namespace Rabibgalib\ApiAction\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Rabibgalib\ApiAction\ActionExecute\ExecuteCommands;

class MakeActionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:api-action {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make an actionable controller Class';

    /**
     * Filesystem instance
     * @var Filesystem
     */
    protected Filesystem $files;

    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $command_name = $this->argument('name');
        $execute_command = new ExecuteCommands();

        $this->releaseClasses($execute_command, $command_name, 'controller');
        $this->releaseClasses($execute_command, $command_name, 'request');
        $this->releaseClasses($execute_command, $command_name, 'trait');
        $this->releaseClasses($execute_command, $command_name, 'helper');
        $this->releaseClasses($execute_command, $command_name, 'model');
        $this->releaseClasses($execute_command, $command_name, 'migration');
        $this->releaseClasses($execute_command, $command_name, 'action-create');
        $this->releaseClasses($execute_command, $command_name, 'action-update');
        $this->releaseClasses($execute_command, $command_name, 'action-list');
        $this->releaseClasses($execute_command, $command_name, 'action-find');
        $this->releaseClasses($execute_command, $command_name, 'action-delete');
    }

    /**
     * @param $execute_command
     * @param $command_name
     * @param $type
     * @return void
     */
    public function releaseClasses($execute_command, $command_name, $type): void
    {
        $path = $execute_command->getSourceFilePath($command_name, $type);
        $this->makeDirectory(dirname($path));
        $contents = $execute_command->getSourceFile($command_name, $type);
        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $status = "<fg=green>Created</>";
        } else {
            $status = "<bg=yellow;fg=black>Already Created</>";
        }
        $component = ucfirst($type);
        $this->line("   <bg=cyan;fg=black>{$component}</> " . "<options=bold> [ $path ] </>" . $status );
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param string $path
     * @return string
     */
    protected function makeDirectory(string $path): string
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }
}
