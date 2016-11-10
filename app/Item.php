<?php

namespace Portal;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    use Uuids;

    /*
     * An Item is a description of a cost to the leaseholder.
     * There can be many Items on a Contract.
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'value'
    ];
}
