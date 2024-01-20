<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $returnArray = [
            'id' => $this->id,
            'name' => $this->name,
            'latin_name' => $this->latin_name,
            'water_frequency' => $this->water_frequency,
            'sunlight' => $this->sunlight,
        ];

        return $returnArray;
    }
}
