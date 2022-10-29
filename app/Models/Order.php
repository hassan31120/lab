<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded =[];

    public function doctor(){
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function type(){
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function color(){
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function edited(){
        return $this->belongsTo(User::class, 'edited_by');
    }

}
