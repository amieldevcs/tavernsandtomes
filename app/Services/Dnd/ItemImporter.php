<?php

namespace App\Services\Dnd;

use App\Models\EquipmentCategory;
use App\Models\Item;

class ItemImporter
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected DndApiClient $api)
    {
        //
    }

    public function import()
    {
        $items = $this->api->getAll('magic-items');

        $variantReferences = [];

        $imported = 0;

        foreach ($items as $reference) {

            // Get the full item details
            $detail = $this->api->get('/magic-items/'.$reference['index']);

            // Get the category
            $category = EquipmentCategory::where('index', $detail['equipment_category']['index'] ?? '')
                ->first();

            // Get the description and convert into text
            $description = implode(', ', $detail['desc'] ?? []);

            // Get image url
            $imageUrl = isset($detail['image']) ? 'https://www.dnd5eapi.co'.$detail['image'] : null;

            // Save item
            $item = Item::updateOrCreate(
                ['slug' => $detail['index']],
                [
                    'name' => $detail['name'],
                    'description' => implode("\n\n", $detail['desc'] ?? []),
                    'rarity' => $detail['rarity']['name'] ?? null,
                    'equipment_category' => $detail['equipment_category']['name'] ?? null,
                    'image_url' => $imageUrl,
                    'source_url' => 'https://www.dnd5eapi.co'.$detail['url'],
                ]);

            $imported++;
        }

        return $imported;
    }
}
