<?php

namespace Portal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemLeaseDate extends Model
{

    use Uuids;
    use SoftDeletes;

    /*
     * An Item is a description of a cost to the leaseholder.
     * There can be many Items on a Contract.
     */

    protected $fillable = [
        'leasee_name','item_name', 'status', 'start_date', 'end_date', 'item_id', 'user_id'
    ];

    protected $dates = ['deleted_at'];

    public $incrementing = false;

    /**
     * The unit types that belongs to the item.
     */
    public function items()
    {
        return $this->belongsToOne('Portal\Item');
    }
}
