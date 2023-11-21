<?php

namespace App\Http\Controllers;
use App\Helpers\HumanNameFormatterHelper;
use App\Helpers\DepartmentHelper;
use App\Helpers\UsertypeHelper;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\usertype;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use App\Imports\UserImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
class UserinfoController extends Controller
{
    //for phase 2 updates 01.05.2023
    public function index(){
        $dept = Department::all();
        $seluser = User::where('id','!=',auth()->user()->key())->get();
        $getuser = User::where('id','!=',auth()->user()->key())->paginate(10);
        return view('users.index',[
           'users' => $getuser,
           'searchuser' => $seluser,
           'department' =>$dept
       ]);
    }

    public function importPage(){
        return view('users.import');
    }
    public function import(Request $request){
        $request->validate([
            'file' => 'required|file'
        ]);

        ini_set('max_execution_time', -1);
        Excel::import(new UserImport, $request->file('file')->store('temp'));
        ini_set('max_execution_time', 60);
        return back()->with('success', 'Successfully imported data');
    
    }
    public function searchUser(Request $request){
        $dept = Department::all();
        $seluser = User::where('id','!=',auth()->user()->key())->get();
        $getuser = User::where('id','!=',auth()->user()->key())->where('id' , $request->user)->paginate(10);
      
        if($request->user == 'all'){
            $getuser = User::where('id','!=',auth()->user()->key())->paginate(10);
        }

      
        return view('users.index',[
           'users' => $getuser,
           'searchuser' => $seluser,
           'department' =>$dept
       ]);
       
    }
    public function getDept(Request $request){
        $dept = Department::all();
        $seluser = User::where('id','!=',auth()->user()->key())->get();
        $getuser = User::where('id','!=',auth()->user()->key())->where('department', $request->deptcode)->paginate(10);
        
        if($request->deptcode =='all'){
            $getuser = User::where('id','!=',auth()->user()->key())->paginate(10);
        }
        
        return view('users.index',[
           'users' => $getuser,
           'searchuser' => $seluser,
           'department' =>$dept
       ]);
    }
    public function edit(User $users)
    {
        $departments = Department::all(); 
        $usertype = usertype::all();
     return view('users.edit', compact('users', 'usertype','departments'));
       
    }
    public function update(Request $request , User $users){
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'department' => 'required',
            'useraccess' => 'required',
        ]);
        $users->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'department'=> $request->department,
            'usertype' => $request->useraccess
        ]);
       // return redirect(route('user.profile'))->with('success','Profile successfully updated to the database');
        return redirect()->route('usrinfo.edit', ['users' => $users])->with('success', 'Author successfully updated to the database');

    }
    public function create()
    {
        $departments = Department::all(); 
        $usertype = usertype::all();
        $randpass = "!@#$%&()1234567890abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
       // $print = substr(str_shuffle($randpass),1,8);
         $print  = "qwe123123";
        return view('users.create', compact('usertype' ,'print' ,'departments'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'middle_initial' => 'required',
            'email' => 'required',
            'department' => 'required',
            'password' => 'required',
            'useraccess' => 'required',
        ]);
        User::create([
            'firstname' => $request->firstname,
            'lastname'=> $request->lastname,
            
            'middlename'=>$request->middle_initial,
            'email'=> $request->email,
            'password' =>Hash::make($request->password),
            'usertype' => $request->useraccess,
            'department' => $request->department


        ]);
        return redirect(route('userinfo.index'));
    }
    public function delete(User $users)
    {
        /**
         * You can directly delete the author
         * To achieve that, use the authorVariable->delete()
         */

        $users->delete();

        /**
         * Redirect to author.index
         * Also add session with the value of { Author has been successfully deleted from the database }
         */

        return redirect()->route('userinfo.index')->with('success', 'Author has been successfully deleted from the database');
    }
}
