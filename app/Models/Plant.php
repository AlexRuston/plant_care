<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;

    /**
     * switch the water_frequency integer to a string
     *
     * @param  string $value
     * @return string
     */
    public function getWaterFrequencyAttribute($value)
    {
        return $value . ' days';
    }

    /**
     * switch the sunlight integer to a string
     *
     * @param  string $value
     * @return string
     */
    public function getSunlightAttribute($value)
    {
        switch($value) {
            case 0:
                return "Dark";
            case 1:
                return "Shade";
            case 2:
                return "Partial Shade";
            case 3:
                return "Partial Sunlight";
            case 4:
                return "Direct Sunlight";
        }
    }
}
