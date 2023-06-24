<?php

namespace App\Http\Controllers\Admin;

use App\Filters\User\UserIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Master\Company;
use App\Models\Master\Agents;
use App\Models\Master\LessorSeller;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,User::class);

            $users = User::filter(new UserIndexFilter(request()))
            ->orderBy('name')
            ->with('roles')->where('company_id',Auth::user()
            ->company_id)
            ->paginate(30);
            $roles = Role::orderBy('name')->get();

        return view('admin.user.index',[
            'items'=>$users,
            'roles'=>$roles
        ]);
    }

    public function show(User $user){
        $this->authorize(__FUNCTION__,User::class);
        $user->load('roles','companies');
        return view('admin.user.show',[
            'user'=>$user
        ]);
    }
    public function showProfile(){
        $user = Auth::user();
        $user->load('roles','companies');
        return view('admin.user.show-profile',[
            'user'=>$user
        ]);
    }


    public function create(){
        $this->authorize(__FUNCTION__,User::class);
        $companies = Company::orderBy('name')->get();
        $user_agent = Agents::where('company_id',Auth::user()->company_id)->get();
        $roles = Role::orderBy('name')->get();
        $isSuperAdmin = false;
        $lessors = LessorSeller::where('company_id',Auth::user()->company_id)->get();
        $operators = LessorSeller::where('company_id',Auth::user()->company_id)->get();
        if(Auth::user()->is_super_admin){
            $isSuperAdmin = true;
            $agents = Agents::where('company_id',Auth::user()->company_id)->get();
        }else{
            $agents = [];
        }
        return view('admin.user.create',[
            'companies'=>$companies,
            'roles'=>$roles,
            'agents'=>$agents,
            'lessors'=>$lessors,
            'user_agent'=>$user_agent,
            'isSuperAdmin'=>$isSuperAdmin
        ]);
    }

    public function store(Request $request){
        $this->authorize(__FUNCTION__,User::class);
        $this->validate($request,$this->rules(),$this->messages());
        $user = Auth::user();
        $data = array_merge($request->except('_token','password_confirmation','role'),[
            'password'=>Hash::make($request->input('password')),
            'avatar'=>$this->storeAvatar($request),
            'is_super_admin'=>0,
            'company_id'=>$user->company_id,
        ]);
        if(is_null($request->input('role'))){
            $data['is_active'] = '0';
        }
        $user = User::create($data);
        if(!is_null($request->input('role'))){
            $user->attachRole($request->input('role'));
        }
        return redirect()->route('users.index')->with('success',trans('user.created'));
    }

    public function edit(User $user){
        $this->authorize(__FUNCTION__,User::class);
        $companies = Company::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        $user->load('roles');
        $userCompanis = $user->companies()->pluck('company_users.company_id')->all();
        $user_agent = Agents::where('company_id',Auth::user()->company_id)->get();
        $isSuperAdmin = false;
        if(Auth::user()->is_super_admin){
            $isSuperAdmin = true;
            $agents = Agents::where('company_id',Auth::user()->company_id)->get();
        }else{
            $agents = [];
        }
        return view('admin.user.edit',[
            'companies'=>$companies,
            'isSuperAdmin'=>$isSuperAdmin,
            'agents'=>$agents,
            'roles'=>$roles,
            'user'=>$user,
            'userCompanis'=>$userCompanis,
            'user_agent'=>$user_agent,
        ]);
    }

    public function update(Request $request,User $user){
        $this->authorize(__FUNCTION__,User::class);
        $this->validate($request,$this->rules(true,$user));
        $data = array_merge($request->except('_token','password','password_confirmation','role','companies'),[
            'password'=>is_null($request->input('password')) ? $user->password : Hash::make($request->input('password')),
            'avatar'=>$this->storeAvatar($request,$user->avatar)
        ]);
        if(is_null($request->input('role'))){
            $data['is_active'] = '0';
        }
        $user->update($data);
        $roles = is_null($request->input('role')) ? [] : [$request->input('role')];
        $user->syncRoles($roles);
        $user->clearCache();
        return redirect()->route('users.index')->with('success',trans('user.updated'));
    }

    protected function rules($is_update = false,$user = null){
        $rules =  [
            'full_name'=>'required|string|max:128',
            'name'=>'required|alpha_dash|min:4|max:30|unique:users,name',
            'password'=>'required|string|min:6|max:30|confirmed',
            'email'=>'required|email|nullable|unique:users,email',
            'employee_no'=>'nullable|integer|unique:users,employee_no',
            'avatar'=>'nullable|mimes:jpeg,png|file|max:2048'
        ];
        if($is_update){
            $rules = array_merge($rules, [
                'name'=>'required|alpha_dash|min:4|unique:users,name,'.$user->id,
                'email'=>'required|email|nullable|unique:users,email,'.$user->id,
                'employee_no'=>'nullable|integer|unique:users,employee_no,'.$user->id,
                'password'=>'nullable|min:6|confirmed',
            ]);
        }
        return $rules;
    }

    protected function messages(){
        return  [
            'name.required'=>'User Name is Required',
            'name.unique'=>'User Name has already been taken',
            'email.required'=>'Email is Required'

        ];
    }
    protected function storeAvatar(Request $request,$url = null){
        return $request->hasFile('avatar') ? $request->file('avatar')->store('avatars',['disk' => 'public']) : $url;
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete(); 
        return redirect()->route('users.index')->with('success',trans('User.deleted.success'));
    }

}
