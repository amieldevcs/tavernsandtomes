<?php

namespace App\Console\Commands;

use App\Services\Dnd\EquipmentCategoryImporter;
use App\Services\Dnd\ItemImporter;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('dnd:sync-magic-items')]
#[Description('Command Import and synchronize D&D magic items')]
class SyncMagicItems extends Command
{
    protected $signature = 'dnd:sync-magic-items';

    protected $description = 'Import and synchronize D&D magic items';

    /**
     * Execute the console command.
     */
    public function handle(EquipmentCategoryImporter $categoryImporter, ItemImporter $itemImporter)
    {
        $this->info('Importing equipment categories...');

        $categories = $categoryImporter->import();

        $this->info("Imported {$categories} categories.");

        $this->info('Importing magic items...');

        $items = $itemImporter->import();

        $this->info("Imported {$items} magic items.");

        $this->info('D&D item synchronization completed!');

        return self::SUCCESS;
    }
}
