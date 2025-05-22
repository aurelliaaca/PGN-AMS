<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeJaringan extends Model
{
    use HasFactory;

    protected $table = 'tipejaringan';
    protected $primaryKey = 'kode_tipejaringan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_tipejaringan',
        'nama_tipejaringan',
    ];
}