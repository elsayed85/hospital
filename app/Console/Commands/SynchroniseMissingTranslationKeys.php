<?php

namespace App\Console\Commands;

use App\Services\Translation\Drivers\Translation;
use Illuminate\Console\Command;

class SynchroniseMissingTranslationKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'local:sync {language?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add all of the missing translation keys for all languages or a single language';

    protected $translation;

    public function __construct(Translation $translation)
    {
        parent::__construct();
        $this->translation = $translation;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $language = $this->argument('language') ?: false;

        try {
            // if we have a language, pass it in, if not the method will
            // automagically sync all languages
            $this->translation->saveMissingTranslations($language);

            return $this->info("Missing keys synchronised successfully 🎊");
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
