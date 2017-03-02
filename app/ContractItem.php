<?php

namespace Portal;

use Illuminate\Database\Eloquent\Model;

class ContractItem extends Model
{

    use Uuids;

    /*
     * A contract item is an item of a specified value
     * that is added to the contract as an addendum.
     * Contract items are also used in the formation
     * of the invoice. They are copied from Items but
     * do not reference them in case of change.
     */

    protected $fillable = [
        'contract_id', 'name', 'description', 'value', 'monthly_payment'
    ];

    public function contract()
    {
        return $this->hasOne('Portal\Contract');
    }

}
