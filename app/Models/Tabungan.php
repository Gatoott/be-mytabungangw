<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'user_id', 'nama_tabungan', 'target', 'perhari', 'terkumpul'])]
class Tabungan extends Model
{
    public function users() {
        return $this->belongsTo(User::class);
    }
}
