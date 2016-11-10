<?php

namespace Portal;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{

    use Uuids;

    /*
     * A Location is a physical location of a property.
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address', 'city', 'region', 'code'
    ];
}
