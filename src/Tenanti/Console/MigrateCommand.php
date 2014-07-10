<?php namespace Orchestra\Tenanti\Console;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Input\InputOption;

class MigrateCommand extends BaseCommand
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tenanti:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the database migrations';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        if (! $this->confirmToProceed()) {
            return;
        }

        $driver  = $this->argument('driver');
        $pretend = $this->option('pretend');

        $this->prepareDatabase($driver);

        $migrator = $this->tenant->driver($driver);

        $migrator->run($pretend);
    }

    /**
     * Prepare the migration database for running.
     *
     * @param  string   $driver
     * @return void
     */
    protected function prepareDatabase($driver)
    {
        $database = $this->option('database');

        $this->call("tenanti:install", array(
            $driver,
            '--database' => $database,
        ));

    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge(array(
            array('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'),
            array('force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'),
        ), parent::getOptions());
    }
}
