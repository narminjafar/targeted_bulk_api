<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
   protected $fillable = [
       'name',
       'filter_json',
   ];

   protected $casts = [
    'filter_json' => 'array',
];
}
