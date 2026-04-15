<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    public $fillable = ['nama', '', 'slug'];

    protected $table = 'kategoris';

    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_kategori');
    }
    public function submission()
    {
        return $this->hasMany(ItemSubmission::class, 'id_kategori');
    }
}
