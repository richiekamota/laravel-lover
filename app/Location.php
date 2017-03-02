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

    public $incrementing = false;

    /**
     * The unit types at this location
     */
    public function unitTypes()
    {
        return $this->hasMany('Portal\UnitType');
    }

    /**
     * The units at this location
     */
    public function units()
    {
        return $this->hasMany('Portal\Unit');
    }

}
