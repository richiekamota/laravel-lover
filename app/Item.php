<?php

namespace Portal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{

    use Uuids;
    use SoftDeletes;

    /*
     * An Item is a description of a cost to the leaseholder.
     * There can be many Items on a Contract.
     */

    protected $fillable = [
        'name', 'description', 'cost', 'payment_type'
    ];

    protected $dates = ['deleted_at'];

    public $incrementing = false;

    /**
     * The unit types that belongs to the item.
     */
    public function unitTypes()
    {
        return $this->belongsToMany('Portal\UnitType');
    }
}
