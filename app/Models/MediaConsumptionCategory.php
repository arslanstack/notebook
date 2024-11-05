<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaConsumptionCategory extends Model
{
    use HasFactory;

    protected $table = 'media_consumption_categories';

    protected $fillable = [
        'title',
        'slug'
    ];

    public function mediaConsumptions()
    {
        return $this->hasMany(MediaConsumption::class, 'category_id');
    }
}
