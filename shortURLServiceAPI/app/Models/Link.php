<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = 'links';
    protected $fillable = ['url', 'token', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
