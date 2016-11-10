<?php

namespace Portal;

use Illuminate\Database\Eloquent\Model;

class UnitType extends Model
{
    use Uuids;

    /*
     * A UnitType refers to a layout of unit. UnitType can have a number of Items
     * based on its attributes.
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'location_id', 'cost', 'wifi', 'electricity', 'dstv',
        'parking_car', 'parking_bike', 'storeroom'
    ];

    public function location()
    {
        return $this->hasOne('Portal\Location');
    }
}
