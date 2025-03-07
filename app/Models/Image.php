<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'file_name',
        'imageable_id',
        'imageable_type',
    ];

    /**
     * Get the parent imageable model (either Blog or other models).
     */
    public function imageable()
    {
        return $this->morphTo();
    }
}
