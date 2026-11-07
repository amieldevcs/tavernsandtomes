<?php

namespace App\Services\Dnd;

use App\Models\EquipmentCategory;

class EquipmentCategoryImporter
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected DndApiClient $api)
    {
        // Add stuff here
    }

    public function import(): int
    {
        $categories = $this->api->getAll('equipment-categories');

        $imported = 0;

        foreach ($categories as $category) {
            EquipmentCategory::updateOrCreate(
                ['index' => $category['index']],
                ['name' => $category['name']]
            );

            $imported++;
        }

        return $imported;
    }
}
