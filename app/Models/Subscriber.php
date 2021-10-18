<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'subscriptions', 'subscriber_id','topic_id');
    }
}
