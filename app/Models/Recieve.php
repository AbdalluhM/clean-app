<?php

namespace App\Models;

use App\Models\User;
use App\Models\Admin;
use App\Models\RecieveDetails;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recieve extends Model
{
    use HasFactory;
    protected $fillable=['user_id','employee_id','address','time_start','status'];
    public function employee()
    {
        return $this->belongsTo(Admin::class);
    }
    public function customer(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function details()
    {
        return $this->hasMany(RecieveDetails::class,'recieve_id');
    }
}
