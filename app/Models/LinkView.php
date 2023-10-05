<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkView extends Model
{
    use HasFactory;
    protected $fillable = [
        'visitor_address',
        'link_id'
    ];

    public function link(){
        return $this->belongsTo(Link::class);
    }
}
