<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use \Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use \Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Utils\Fpos;

class EmployeeController extends Controller
{
    use FileUploadTrait;

    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['roles'] = Role::all();
        $data['user'] = Auth::user();
        if ($request->ajax()) {
            $search = [];
            if (!empty($request->filter)) {
                $search = $request->filter;
                Session::put('employee_filter', $search);
            } else if (Session::get('employee_filter')) {
                $search = Session::get('employee_filter');
            }
            $data['employees'] = $this->user->getAll('paginate', $search);
            return $this->sendCommonResponse($data, null, 'index');
        }
        $data['employees'] = $this->user->getAll('paginate');
        return view('employee.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('employee.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:users',
            'name' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'role' => 'required'
        ]);
        // store
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        if (!empty($request->file('avatar'))) {
            $avatar_name = $this->uploadImage($request->file('avatar'), 'images/users');
            $input['avatar'] = $avatar_name;
        }
        $user->avatar = $input['avatar'];
        $user->save();
        $this->assignRoles($user, $request->role);

        $data['roles'] = Role::all();

        return $this->sendCommonResponse($data, __('You have successfully added employee'), 'add');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data['employee'] = User::find($id);
        $data['roles'] = Role::all();
        return $this->sendCommonResponse($data, null, 'edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if ($id == 1 && config('app.url') == Fpos::DEMO_URL) {
            return $this->sendCommonResponse([], ['danger' => __('You cannot edit super admin')]);
        } else if (auth()->user()->id == $id || auth()->user()->id == 1) {
            $rules = array(
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $id . '',
                'password' => 'nullable|min:6|max:30|confirmed',
            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return Redirect::to('employees/' . $id . '/edit')
                    ->withErrors($validator);
            } else {
                $user = User::find($id);
                $user->name = $request->name;
                $user->email = $request->email;
                if (!empty($request->password)) {
                    $user->password = Hash::make($request->password);
                }
                if (!empty($request->file('avatar'))) {
                    if (Storage::exists($user->avatar)) {
                        Storage::delete($user->avatar);
                    }
                    $input['avatar'] = $this->uploadImage($request->file('avatar'), 'images/users');
                } else {
                    $input['avatar'] = $user->avatar;
                }
                $user->avatar = $input['avatar'];
                $user->save();

                $this->assignRoles($user, $request->role);
                $data['roles'] = Role::all();
                $data['employee'] = $user;
                return $this->sendCommonResponse($data, __('You have successfully updated employee'), 'update');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if ($id == 1) {;
            return $this->sendCommonResponse([], ['danger' => __('You cannot delete super admin')]);
        } else {
            try {
                $users = User::find($id);
                $users->delete();
                return $this->sendCommonResponse([], __('You have successfully deleted employee'), 'delete');
            } catch (\Illuminate\Database\QueryException $e) {
                return $this->sendCommonResponse([], ['danger' => __('Integrity constraint violation: You Cannot delete a parent row')]);
            }
        }
    }

    public function assignRoles($user, $role)
    {
        if ($user->id == 1) {
            Session::flash('message', 'You can not assign admin role');
            Session::flash('alert-class', 'alert-danger');
            return back();
        }
        $all_past_roles = $user->getRoleNames();

        foreach ($all_past_roles as $value) {
            $user->removeRole($value);
        }
        $user->assignRole($role);
    }

    public function roleCreate(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        Role::create(['name' => Str::slug($request->name)]);
        $data['roles'] = Role::all();
        return $this->sendCommonResponse($data, __('Role created successfully!'), 'role-create');
    }

    public function permissionList($role_id = null)
    {
        $roles = Role::pluck('name', 'id');
        $all_permissions = [];
        $permissions = Permission::all();
        foreach ($permissions as $key => $value) {
            $permission_set = '';
            $permission_name = explode(' ', $value->label);
            if ($key == 0) {
                $permission_set = $permission_name[1];
            }
            if (strtolower($permission_set) == strtolower($permission_name[1])) {
                $all_permissions[$permission_set][] = $value;
            } else {
                $permission_set = $permission_name[1];
                $all_permissions[$permission_set][] = $value;
            }
        }
        $role = Role::oldest()->first();
        if (!empty($role_id)) {
            $role = Role::findById($role_id);
        }
        $data = compact('permissions', 'roles', 'role', 'role_id', 'all_permissions');
        if (request()->ajax()) {
            return $this->sendCommonResponse($data, null, 'permission-list');
        }
        return view('employee.permissions', $data);
    }

    public function createPermission(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'label' => 'required',
        ]);
        Permission::create(['label' => $request->label, 'name' => $request->name]);
        return back();
    }

    public function rolePermissionMapping(Request $request)
    {
        $this->validate($request, [
            'role_id' => 'required',
            'permissions' => 'required',
        ]);
        $role = Role::findById($request->role_id);
        if ($role->name == 'admin') {
            return $this->sendCommonResponse([], ['danger' => __('You can not edit admin permissions')]);
        }
        $permissions = $request->permissions;

        // Delete all Previous Permissions
        $this->deleteAllPrevPermissions($role->id);
        $all_permissions = Permission::pluck('name', 'id');
        foreach ($permissions as $value) {
            // $permission = Permission::findById($value);
            $role->givePermissionTo($all_permissions[$value]);
        }
        return $this->sendCommonResponse([], __('Permission given to role successfully!'));
    }

    public function deleteAllPrevPermissions($role_id)
    {
        DB::table('role_has_permissions')->where('role_id', $role_id)->delete();
    }

    private function sendCommonResponse($data = [], $notify = '', $option = null)
    {
        $response = $this->processNotification($notify);
        if ($option == 'add') {
            $data['employee'] = [];
            $response['replaceWith']['#addEmployee'] = view('employee.form', $data)->render();
        } else if ($option == 'edit' || $option == 'update') {
            $response['replaceWith']['#editEmployee'] = view('employee.form', $data)->render();
        } else if ($option == 'show') {
            $response['replaceWith']['#showCustomer'] = view('customer.profile', $data)->render();
        } else if ($option == 'permission-list') {
            $response['replaceWith']['#permissionList'] = view('employee.permission_list', $data)->render();
        }
        if ($option == 'index' || $option == 'add' || $option == 'update' || $option == 'delete' || $option == 'role-create') {
            if (empty($data['employees'])) {
                $data['employees'] = $this->user->getAll('paginate');
            }
            if (empty($data['roles'])) {
                $data['roles'] = Role::all();
            }
            if (empty($data['user'])) {
                $data['user'] = Auth::user();
            }
            $response['replaceWith']['#employeeTable'] = view('employee.table', $data)->render();
            $response['replaceWith']['#addEmployee'] = view('employee.form', $data)->render();
        }
        return $this->sendResponse($response);
    }
}
