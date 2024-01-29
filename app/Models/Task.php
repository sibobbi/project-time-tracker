<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{   
    use HasFactory;

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
    public function userWorking() 
    {
        return $this->HasOne(User::class,"id","user_working_id");
    }

}
