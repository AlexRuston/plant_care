<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MyPlantResource extends JsonResource
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
            'plant' => PlantResource::make($this->plant),
            'last_watered' => $this->last_watered,
        ];

        return $returnArray;
    }
}
