<?php

namespace Portal;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{

    use Uuids;

    /*
     * A Document is a PDF file generated from a Contract.
     * They are stored on the S3 cloud.
     */


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location', 'user_id'
    ];

    public function user()
    {
        return $this->hasOne('Portal\User');
    }
}
