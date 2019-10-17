<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController as Controller;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\Auth;
use Validator;

use Spatie\Permission\Models\Role;
use App\User;

class UserController extends Controller
{
        
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:show users');
        $this->middleware('permission:add users', ['only' => ['create','store']]);
        $this->middleware('permission:edit users', ['only' => ['edit','update']]);
        $this->middleware('permission:delete users', ['only' => ['destroy']]);   
        
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return view('admin.users_block.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $roles = Role::pluck('name', 'name')->all();
        
        if(!Auth::user()->hasRole('SuperAdmin'))
            unset($roles['SuperAdmin']);
        
        if(!Auth::user()->hasRole('Administrator'))
            unset($roles['Administrator']);
        
        return view('admin.users_block.users.create',compact('roles'));
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
            'name' => 'required|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|same:confirm-password',
            'status' => 'required'
        ]);
                
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
                
        $user = User::create($input);
        
        $user->assignRole($request->input('roles')); 

        return redirect()->route('users.index')
                        ->with('success','User created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {        
        $user = User::where('id',$id)->first();
                
        return view('admin.users_block.users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
                
        $roles = Role::pluck('name', 'name')->all();
        
        if(!Auth::user()->hasRole('SuperAdmin'))
            unset($roles['SuperAdmin']);
        
        if(!Auth::user()->hasRole('Administrator'))
            unset($roles['Administrator']);
                        
        return view('admin.users_block.users.edit',compact('user', 'roles'));
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
        $validateRules = [];        
        $input = $request->all();
        
        $user = User::find($id);        
        
        if(!$user)
            return response()->json(['message' => 'The given data was invalid.', 'errors'=>['model'=>['User not found!']]], 422);
        
        if($request->get('status'))
            $validateRules['status'] = 'required';
            
        if($request->get('name') && $user->name != $request->get('name'))
            $validateRules['name'] = 'required|unique:users,name';
        
        if($request->get('email') && $user->email != $request->get('email'))
            $validateRules['email'] = 'required|email|unique:users,email';            
        
        if($request->get('password')){  
            if(!Auth::user()->hasRole('SuperAdmin') && $user->hasRole('SuperAdmin') )
                return response()->json(['message' => 'You not have permissions!', 'errors'=>['user'=>['You not have permissions!']]], 422);                

            if(!Auth::user()->hasRole('Administrator') && $user->hasRole('Administrator') )
                return response()->json(['message' => 'You not have permissions!', 'errors'=>['user'=>['You not have permissions!']]], 422);
        
            $validateRules['password'] = 'required|min:6|same:confirm-password';
            $input['password'] = Hash::make($input['password']);
                        
        }
        
        $this->validate($request, $validateRules);
                        
        $user->update($input);        
        
        $newRoles = $request->input('roles');
        
        if(!Auth::user()->hasRole('SuperAdmin') && $user->hasRole('SuperAdmin') )
            $newRoles['SuperAdmin'] = 'SuperAdmin';

        if(!Auth::user()->hasRole('Administrator') && $user->hasRole('Administrator') )
            $newRoles['Administrator'] = 'Administrator';
        
        $user->syncRoles($newRoles); 
        
        return response()->json(['success'=>'ok']);

    }
        
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userIDs = explode(',', $id);
        
        $validator = Validator::make($userIDs,['numeric']);
        $success = '';
        
        foreach($userIDs as $userID){
            $user = User::find($userID);
            if($user && !Auth::user()->hasRole('SuperAdmin') && $user->hasRole('SuperAdmin') ){
                $validator->errors()->add($user->name, 'You not have permissions to delete '.$user->name.'!');
                continue;
            }
            
            if($user && !Auth::user()->hasRole('Administrator') && $user->hasRole('Administrator') ){
                $validator->errors()->add($user->name, 'You not have permissions to delete '.$user->name.'!');
                continue;
            }
            
            $success .= ($success != '' ? 'User:' : '') .' '.$user->name;
            
            $user->delete();
        }
        
        $success .= $success != '' ? ' delete successfully!' : '';
        
        return redirect()->route('users.index')
                        ->with('success',$success)
                        ->withErrors($validator);
    }
    
    /**
     * Ban the specified resource from storage.
     *
     * @param  str  $ids
     * @return \Illuminate\Http\Response
     */
    public function ban(Request $request, $ids)
    {
        $banIDs = explode(',', $ids);
        $statuses = [];
        
        $type = $request->get('action') ? ($request->get('action') == 'hold' ? false : true ) : null;
                
        foreach($banIDs as $userID){
            $user = User::find($userID);            
            
            if($user){
                
                if($user && !Auth::user()->hasRole('SuperAdmin') && $user->hasRole('SuperAdmin') )
                    continue;

                if($user && !Auth::user()->hasRole('Administrator') && $user->hasRole('Administrator') )
                    continue;
                
                $user->status = $type ?? $user->status ? false : true;
                $user->save();
                $statuses[$userID] = $user->status;
            }
            
        }
        
        return response()->json(['success'=>'ok', 'statuses'=>$statuses]);
    }
    
    /**
     * Datatable Ajax fetch
     *
     * @return
     */
    public function usersDTAjax(Request $request) {

        if($request->get('role')){
            $roleName = $request->get('role');
            $users = User::whereHas("roles", function($q) use ($roleName){ $q->where("name", $roleName); })->get();
        }else{
            $users = User::all();
        }
                            
        $out = datatables()->of($users)
                ->addColumn('roles', function($users) {
                    return implode(', ', $users->roles->pluck('name', 'id')->all());
                })                
                ->addColumn('actions', '')
                ->toJson();

        return $out;
    }    
}
