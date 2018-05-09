<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class JWTGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'jwt:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the JWTAuth secret key used to sign the tokens';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $key = $this->getRandomKey();

        if ($this->option('show')) {
            return $this->line('<comment>'.$key.'</comment>');
        }

        $path = config_path('jwt.php');

        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                $this->laravel['config']['jwt.secret'], $key, file_get_contents($path)
            ));
        }

        $this->laravel['config']['jwt.secret'] = $key;

        $this->info("jwt-auth secret [$key] set successfully.");
    }

    /**
     * Generate a random key for the JWT Auth secret.
     *
     * @return string
     */
    protected function getRandomKey()
    {
        return Str::random(32);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['show', null, InputOption::VALUE_NONE, 'Simply display the key instead of modifying files.'],
        ];
    }
}
