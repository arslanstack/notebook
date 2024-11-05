<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaConsumption extends Model
{
    use HasFactory;

    protected $table = 'media_consumptions';

    protected $fillable = [
        'title',
        'achievement',
        'date',
        'category_type',
        'category_id',
        'category_text',
        'user_id',
        'times_completed',
        'status'
    ];


    public function mediaCategory()
    {
        return $this->belongsTo(MediaConsumptionCategory::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
