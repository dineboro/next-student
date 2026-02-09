<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestCategory extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'color',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function helpRequests()
    {
        return $this->hasMany(HelpRequest::class, 'category_id');
    }
}
