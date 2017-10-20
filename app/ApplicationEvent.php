<?php

namespace Portal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationEvent extends Model
{

    use Uuids;
    use SoftDeletes;

    protected $fillable = ['id','user_id','application_id','action', 'note'];

    protected $dates = ['deleted_at'];

    public $incrementing = false;

}
