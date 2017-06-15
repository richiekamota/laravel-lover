<?php

namespace Portal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use Uuids;
    use SoftDeletes;

    /*
     * A Unit is a physical room. A Contract is taken out on a Unit.
     */

    protected $fillable = [
        'location_id', 'code', 'type_id', 'user_id', 'contract_id'
    ];

    protected $dates = ['deleted_at'];

    public $incrementing = false;

    public function location()
    {
        return $this->belongsTo('Portal\Location');
    }

    public function unitType()
    {
        return $this->belongsTo('Portal\UnitType', 'type_id');
    }

    public function user()
    {
        return $this->hasOne('Portal\User');
    }

    public function contract()
    {
        return $this->hasOne('Portal\Contract');
    }
}
