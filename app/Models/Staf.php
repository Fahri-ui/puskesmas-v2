<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staf extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'nip',
        'jabatan',
        'no_telepon',
        'email',
        'alamat',
        'foto',
        'tgl_lahir',
        'jenis_kelamin',
        'status'
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];
}