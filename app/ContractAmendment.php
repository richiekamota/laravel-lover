<?php

namespace Portal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractAmendment extends Model
{
    use Uuids;
    use SoftDeletes;

    protected $fillable = [
        'contract_id', 'document_id',
    ];

    protected $dates = ['deleted_at'];

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('Portal\User');
    }

    public function contract()
    {
    	return $this->belongsTo('Portal\Contract');
    }

    public function document()
    {
        return $this->hasMany('Portal\Document');
    }
}
