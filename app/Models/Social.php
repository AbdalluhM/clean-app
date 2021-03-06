<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    use HasFactory;
    protected $fillable=['name','email','type_social','social_id','user_id','phone'];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
