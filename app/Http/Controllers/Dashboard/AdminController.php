<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Admin;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Spatie\Permission\Models\Role;
use App\Http\Requests\AdminRequest;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = Admin::orderBy('id', 'DESC')->paginate(4);
        return view('dashboard.Admins.index', compact('data'));
        // ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view('dashboard.Admins.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminRequest $request)
    {
        // dd('sdf');
        $input = $request->all();
        if (request()->hasFile('image')) {
            $image = time() . '_' . $request->file('image')->hashName();
            $request->file('image')->storeAs('public/images/admins/', $image);
            $input['image'] = $image;
        }
        $input['password'] = Hash::make($input['password']);
        $Admin = Admin::create($input);
        $Admin->assignRole($request->input('roles'));
        Toastr::success('User added successfully :)', 'Success');
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Admin = Admin::find($id);
        return view('dashboard.Admins.show', compact('Admin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Admin::find($id);
        $roles = Role::all();
        $nRoles = Auth::user()->getRoleNames()->toArray();
        $userRoles = $user->roles->pluck('name', 'name')->all();

        return view('dashboard.Admins.edit', compact('user', 'roles', 'userRoles','nRoles'));
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
        $input = $request->validate([
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'phone' => 'required|string|between:2,100',
            'password' => 'required|same:confirm-password|min:6',
        ]);
        $input = $request->all();
        $Admin = Admin::find($id);
        if (request()->hasFile('image')) {
            Storage::disk('public')->delete('/images/admins/' . $Admin->image);
            $image = time() . '_' . $request->file('image')->hashName();
            $request->file('image')->storeAs('public/images/admins/', $image);
            $input['image'] = $image;
        }
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }


        // dd($Admin);
        $Admin->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $Admin->assignRole($request->input('roles'));
        Toastr::success('User Updated successfully :)', 'Success');
        return redirect()->route('profile.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->hasFile('image')) {
            Storage::disk('public')->delete('/images/admins/' . Admin::find($id)->image);
        }
        Admin::find($id)->delete();
        Toastr::success('User Deleted successfully :)', 'Success');
        return redirect()->route('users.index');
    }
    public function profile_user()
    {
        return view('dashboard.Admins.profile');
    }
}
