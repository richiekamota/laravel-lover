<?php

namespace Portal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{

    use Uuids;
    use SoftDeletes;

    /*
     * The contract is the formal agreement between
     * the company and the lease holder. The details
     * for the contract come from the application and
     * the items are attached by the company admin staff
     */

    protected $fillable = [
        'start_date', 'end_date', 'user_id', 'unit_id', 'document_id'
    ];

    protected $dates = ['deleted_at'];

    public $incrementing = false;

    public function user()
    {
        return $this->hasOne('Portal\User');
    }

    public function unit()
    {
        return $this->hasOne('Portal\Unit');
    }

    public function document()
    {
        return $this->hasOne('Portal\Document');
    }
}
