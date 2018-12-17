<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Money extends Model
{
    protected $table = 'money';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'jumlah', 'operator', 'keterangan', 'waktu',
    ];
    protected $hidden = [
    	'created_at', 'updated_at',
    ];
}
