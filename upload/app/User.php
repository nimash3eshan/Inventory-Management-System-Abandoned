<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Traits\HasRoles;

class User extends AppModel implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable;
    use CanResetPassword;
    use HasRoles;
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $guard_name = 'web';
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function checkSpPermission($permission_name) {
        $all_permissions = Session::get('all_permissions_'.$this->id);
        if(empty($all_permissions)) {
            $all_permissions = $this->getAllPermissions()->pluck('name');
            $all_permissions = $all_permissions->toArray();
            Session::put('all_permissions_'.$this->id, $all_permissions);
        }
        if(in_array($permission_name, $all_permissions)) {
            return true;
        }
        return false;
    }

    public function getAll($opt=null, $search = null)
    {
        $results = $this->with('roles')->latest();
        $per_page = !empty($search['per_page']) ? $search['per_page'] : 10;
        if(!empty($search)) {
            if(!empty($search['search'])) {
                $results = $results->where('name', 'LIKE', '%'.$search['search'].'%')
                ->orWhere('email', 'LIKE', '%'.$search['search'].'%');
            }
        }
        if ($opt == 'paginate') {
            return $results->paginate($per_page);
        }
        return $results->get();
    }

    
}
