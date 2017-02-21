<?php

namespace Portal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OccupationDate extends Model
{

    use Uuids;
    use SoftDeletes;

    /*
     * Contains the start and end occupation dates for all approved contracts.
     * They are stored on the S3 cloud.
     */

    protected $fillable = [
        'contract_id', 'application_id', 'unit_id', 'start_date', 'end_date','status'
    ];

    protected $dates = ['deleted_at'];

    public $incrementing = false;

    public function contract()
    {
        return $this->hasOne( 'Portal\Contract', 'id', 'contract_id' );
    }

    public function unit()
    {
        return $this->hasOne( 'Portal\Unit', 'id', 'unit_id' );
    }
}
