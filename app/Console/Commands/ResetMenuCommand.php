<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetMenuCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu:reset';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Wipe all menu categories and items from the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->confirm('This will delete ALL menu categories and items. Do you wish to continue?')) {
            $this->info('Resetting menu...');

            // Disable foreign key checks to allow truncation
            Schema::disableForeignKeyConstraints();

            DB::table('menu_items')->truncate();
            $this->info('Menu items wiped.');

            DB::table('menu_categories')->truncate();
            $this->info('Menu categories wiped.');

            // Re-enable foreign key checks
            Schema::enableForeignKeyConstraints();

            $this->info('Menu reset successfully!');
        } else {
            $this->info('Operation cancelled.');
        }

        return 0;
    }
}
