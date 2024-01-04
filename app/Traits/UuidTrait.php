<?php

namespace App\Traits;

use Hidehalo\Nanoid\Client;

trait UuidTrait {
    protected static function boot(){
        parent::boot();
        static::creating(function($model){
            if(empty($model->{$model->getKeyName()})){
                $client = new Client();
                // $model->{$model->getKeyName()} = Str::uuid()->toString();
                $model->{$model->getKeyName()} = $client->generateId();
            }
        });
    }

    public function getIncrementing(){
        return false;
    }

    public function getKeyType(){
        return 'string';
    }
}