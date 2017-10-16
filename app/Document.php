<?php

namespace Portal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{

    use Uuids;
    use SoftDeletes;

    /*
     * A Document is a PDF file generated from a Contract.
     * They are stored on the S3 cloud.
     */

    protected $fillable = [
        'location', 'user_id', 'type', 'file_name'
    ];

    protected $dates = ['deleted_at'];

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('Portal\User');
    }
}
