<?php

namespace Portal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{

    use Uuids;
    use SoftDeletes;

    /*
     * A Location is a physical location of a property.
     */

    protected $fillable = [
        'name', 'address', 'city', 'region', 'code'
    ];

    protected $dates = ['deleted_at'];
}
