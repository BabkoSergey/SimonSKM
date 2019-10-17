<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController as Controller;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;


class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:show roles');
        $this->middleware('permission:add roles', ['only' => ['create','store']]);
        $this->middleware('permission:edit roles', ['only' => ['edit','update']]);
        $this->middleware('permission:delete roles', ['only' => ['destroy']]);        
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.users_block.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get();
        
        return view('admin.users_block.roles.create',compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name'
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
                        ->with('success','Role created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = $role->permissions()->get();
                
        return view('admin.users_block.roles.show',compact('role','rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permissions = Permission::get();        
        $rolePermissions = $role->permissions()->pluck('id','id')->toArray();

        return view('admin.users_block.roles.edit',compact('role','permissions','rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
                
        if($role->name != $request->input('name')){
            $this->validate($request, ['name' => 'required|unique:roles,name']);
            $role->name = $request->input('name');
            $role->save();
        }
        
        $role->syncPermissions($request->input('permissions') ?? []);
        
        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        $role->syncPermissions([]);
        
        $users = User::whereHas("roles", function($q) use ($id){ $q->where("id", $id); })->get();
        foreach($users as $user){
            $user->removeRole($role->name);    
        }                
        
        $role->delete();
        
        return redirect()->route('roles.index')
                        ->with('success','Role deleted successfully!');
    }
    
    /**
     * Datatable Ajax fetch
     *
     * @return
     */
    public function rolesDTAjax() {

        $roles = Role::get();
        
        $out = datatables()->of($roles)
                ->addColumn('users', function($roles) {
                    return User::whereHas("roles", function($q) use ($roles){ $q->where("id", $roles->id); })->count();
                })
                ->addColumn('permissions', function($roles) {
                    return $roles->permissions()->count();
                })
                ->addColumn('actions', '')
                ->toJson();

        return $out;
    }
}
