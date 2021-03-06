<?php

namespace Portal;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use Uuids;
    use Notifiable;
    use SoftDeletes;

    /*
     * A User is the basis for the whole site. A User can have a number
     * of Roles with each Role providing access to sections of the site
     */

    protected $fillable = [
        'id', 'first_name', 'last_name', 'email', 'password', 'tenant_code', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public $incrementing = false;

    protected $dates = ['deleted_at'];

    public function name()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function applications()
    {
        return $this->hasMany( 'Portal\Application' );
    }

    public function fullApplications() {
        $applications = $this->applications()->where('status', '!=', 'cancelled')->with('user', 'location')->get();
        return $applications;
    }

    public function contracts()
    {
        return $this->hasMany('Portal\Contract');
    }


}
