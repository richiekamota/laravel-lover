<?php

namespace Portal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitType extends Model
{

    use Uuids;
    use SoftDeletes;

    /*
     * A UnitType refers to a layout of unit. UnitType can have a number of Items
     * based on its attributes.
     */

    protected $fillable = [
        'name', 'description', 'location_id', 'cost', 'wifi', 'electricity', 'dstv',
        'parking_car', 'parking_bike', 'storeroom'
    ];

    protected $dates = ['deleted_at'];

    public $incrementing = false;

    public function location()
    {
        return $this->hasOne('Portal\Location');
    }
}
