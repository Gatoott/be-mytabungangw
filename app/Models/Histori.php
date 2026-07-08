<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'nominal', 'type'])]
class Histori extends Model
{
    public function users() {
        return $this->belongsTo(User::class);
    }
}
