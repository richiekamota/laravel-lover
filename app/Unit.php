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

    public function location()
    {
        return $this->hasOne('Portal\Location');
    }

    public function type()
    {
        return $this->hasOne('Portal\Type');
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
