<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;
use App\Mail\UserRegister;
use Illuminate\Support\Str;
use Auth, DB, Mail, Validator, File, DataTables;

class UserController extends Controller{
    /** construct */
        public function __construct(){
            $this->middleware('permission:user-create', ['only' => ['create']]);
            $this->middleware('permission:user-edit', ['only' => ['edit']]);
            $this->middleware('permission:user-view', ['only' => ['view']]);
            $this->middleware('permission:user-delete', ['only' => ['delete']]);
        }
    /** construct */

    /** index */
        public function index(Request $request){
            if ($request->ajax()) {
                $data = User::all();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        $return = '<div class="btn-group">';

                        if (auth()->user()->can('user-view')) {
                            $return .=  '<a href="' . route('user.view', ['id' => base64_encode($data->id)]) . '" class="btn btn-default btn-xs">
                                                <i class="fa fa-eye"></i>
                                            </a> &nbsp;';
                        }

                        if (auth()->user()->can('user-edit')) {
                            $return .= '<a href="' . route('user.edit', ['id' => base64_encode($data->id)]) . '" class="btn btn-default btn-xs">
                                                <i class="fa fa-edit"></i>
                                            </a> &nbsp;';
                        }

                        if (auth()->user()->can('user-delete')) {
                            $return .= '<a href="javascript:;" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-bars"></i>
                                            </a> &nbsp;
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="active" data-id="' . base64_encode($data->id) . '">Active</a></li>
                                                <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="inactive" data-id="' . base64_encode($data->id) . '">Inactive</a></li>
                                                <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="deleted" data-id="' . base64_encode($data->id) . '">Delete</a></li>
                                            </ul>';
                        }

                        $return .= '</div>';

                        return $return;
                    })

                    ->editColumn('status', function ($data) {
                        if ($data->status == 'active') {
                            return '<span class="badge badge-pill badge-success">Active</span>';
                        } else if ($data->status == 'inactive') {
                            return '<span class="badge badge-pill badge-warning">Inactive</span>';
                        } else if ($data->status == 'deleted') {
                            return '<span class="badge badge-pill badge-danger">Deleted</span>';
                        } else {
                            return '-';
                        }
                    })

                    ->editColumn('name', function ($data) {
                        if ($data->photo != '' || $data->photo != null) {
                            return '<div class="d-flex no-block align-items-center">
                                            <div class="mr-3">
                                                <img src="' . URL('/uploads/users/') . "/" . $data->photo . '" alt="user-icon" class="rounded-circle" width="45" height="45">
                                            </div>
                                            <div class="">
                                                <span class="">' . $data->name . '</span>
                                            </div>
                                        </div>';
                        } else {
                            return '<div class="d-flex no-block align-items-center">
                                            <div class="mr-3">
                                                <img src="' . URL('/uploads/users/user-icon.jpg') . '" alt="user-icon" class="rounded-circle" width="45" height="45">
                                            </div>
                                            <div class="">
                                                <span class="">' . $data->name . '</span>
                                            </div>
                                        </div>';
                        }
                    })

                    ->editColumn('role', function ($data) {
                        return ucfirst(str_replace('_', ' ', $data->roles->first()->name));
                    })

                    ->rawColumns(['name', 'role', 'action', 'status'])
                    ->make(true);
            }

            return view('user.index');
        }
    /** index */

    /** create */
        public function create(Request $request){
            $roles = Role::all();
            return view('user.create', ['roles' => $roles]);
        }
    /** create */

    /** insert */
        public function insert(UserRequest $request){
            if ($request->ajax()) { return true; }
            $password = 'abcd1234';

            if ($request->password != '' && $request->password != NULL) {
                $password = $request->password;
            }

            $crud = [
                'name' => ucfirst($request->name),
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => 'active',
                'password' => bcrypt($password),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => auth()->user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];

            if (!empty($request->file('profile'))) {
                $file = $request->file('profile');
                $filenameWithExtension = $request->file('profile')->getClientOriginalName();
                $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
                $extension = $request->file('profile')->getClientOriginalExtension();
                $filenameToStore = time() . "_" . $filename . '.' . $extension;

                $folder_to_upload = public_path() . '/uploads/users/';

                if (!\File::exists($folder_to_upload)) {
                    \File::makeDirectory($folder_to_upload, 0777, true, true);
                }

                $crud['photo'] = $filenameToStore;
            } else {
                $crud['photo'] = 'user-icon.jpg';
            }

            DB::beginTransaction();
            try {
                $user = User::create($crud);
                $user->assignRole($request->role);

                if ($user) {
                    if (!empty($request->file('profile'))) {
                        $file->move($folder_to_upload, $filenameToStore);
                    }
                    DB::commit();
                    return redirect()->route('user')->with('success', 'Record inserted successfully.');
                } else {
                    DB::rollback();
                    return redirect()->back()->with('error', 'Failed to insert record.')->withInput();
                }
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->back()->with('error', 'Failed to insert record.')->withInput();
            }
        }
    /** insert */

    /** edit */
        public function edit(Request $request, $id = ''){
            if (isset($id) && $id != '' && $id != null)
                $id = base64_decode($id);
            else
                return redirect()->route('user')->with('error', 'Something went wrong.');

            $roles = Role::all();
            $path = URL('/uploads/users') . '/';
            $data = User::select(
                'id',
                'name',
                'email',
                'phone',
                'password',
                DB::Raw("CASE
                                        WHEN " . 'photo' . " != '' THEN CONCAT(" . "'" . $path . "'" . ", " . 'photo' . ")
                                        ELSE CONCAT(" . "'" . $path . "'" . ", 'default.png')
                                    END as photo")
            )
                ->where(['id' => $id])
                ->first();

            return view('user.edit')->with(['data' => $data, 'roles' => $roles]);
        }
    /** edit */

    /** update */
        public function update(UserRequest $request){
            if ($request->ajax()) { return true; }

            $id = $request->id;
            $exst_rec = User::where(['id' => $id])->first();

            $crud = [
                'name' => ucfirst($request->name),
                'email' => $request->email,
                'phone' => $request->phone,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];

            if ($request->password != '' && $request->password != NULL) {
                $crud['password'] = $request->password;
            }

            if (!empty($request->file('profile'))) {
                $file = $request->file('profile');
                $filenameWithExtension = $request->file('profile')->getClientOriginalName();
                $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
                $extension = $request->file('profile')->getClientOriginalExtension();
                $filenameToStore = time() . "_" . $filename . '.' . $extension;

                $folder_to_upload = public_path() . '/uploads/users/';

                if (!\File::exists($folder_to_upload)) {
                    \File::makeDirectory($folder_to_upload, 0777, true, true);
                }

                $crud['photo'] = $filenameToStore;
            } else {
                $crud['photo'] = $exst_rec->photo;
            }

            DB::beginTransaction();
            try {

                $update = User::where(['id' => $id])->update($crud);

                if ($update) {
                    if (!empty($request->file('profile'))) {
                        $file->move($folder_to_upload, $filenameToStore);
                    }

                    DB::table('model_has_roles')->where(['model_id' => $id])->delete();

                    $exst_rec->assignRole($request->role);

                    DB::commit();
                    return redirect()->route('user')->with('success', 'Record updated successfully.');
                } else {
                    DB::rollback();
                    return redirect()->back()->with('error', 'Failed to update record 1.')->withInput();
                }
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->back()->with('error', 'Failed to update record 2.')->withInput();
            }
        }
    /** update */

    /** view */
        public function view(Request $request, $id = ''){
            if (isset($id) && $id != '' && $id != null)
                $id = base64_decode($id);
            else
                return redirect()->route('user')->with('error', 'Something went wrong.');

            $roles = Role::all();
            $path = URL('/uploads/users') . '/';
            $data = User::select(
                'id',
                'name',
                'email',
                'phone',
                'password',
                DB::Raw("CASE
                                        WHEN " . 'photo' . " != '' THEN CONCAT(" . "'" . $path . "'" . ", " . 'photo' . ")
                                        ELSE CONCAT(" . "'" . $path . "'" . ", 'default.png')
                                    END as photo")
            )
                ->where(['id' => $id])
                ->first();

            return view('user.view')->with(['data' => $data, 'roles' => $roles]);
        }
    /** view */

    /** change-status */
        public function change_status(Request $request){
            if (!$request->ajax()) {
                exit('No direct script access allowed');
            }

            if (!empty($request->all())) {
                $id = base64_decode($request->id);
                $status = $request->status;

                $user = User::where(['id' => $id])->first();

                if (!empty($user)) {
                    DB::beginTransaction();
                    try {
                        $update = User::where(['id' => $id])->update(['status' => $status, 'updated_by' => auth()->user()->id]);

                        if ($update) {
                            DB::commit();
                            return response()->json(['code' => 200]);
                        } else {
                            DB::rollback();
                            return response()->json(['code' => 201]);
                        }
                    } catch (\Throwable $th) {
                        DB::rollback();
                        return response()->json(['code' => 201]);
                    }
                } else {
                    return response()->json(['code' => 201]);
                }
            } else {
                return response()->json(['code' => 201]);
            }
        }
    /** change-status */

    /** remove-profile */
        public function profile_remove(Request $request){
            if (!$request->ajax()) { exit('No direct script access allowed'); }

            if (!empty($request->all())) {
                $id = base64_decode($request->id);
                $data = DB::table('users')->find($id);

                if ($data) {
                    if ($data->photo != '') {
                        $file_path = public_path() . '/uploads/user/' . $data->photo;

                        if (File::exists($file_path) && $file_path != '') {
                            if ($data->photo != 'user-icon.jpg') {
                                unlink($file_path);
                            }
                        }

                        $update = DB::table('users')->where(['id' => $id])->limit(1)->update(['photo' => '']);

                        if ($update)
                            return response()->json(['code' => 200]);
                        else
                            return response()->json(['code' => 201]);
                    } else {
                        return response()->json(['code' => 200]);
                    }
                } else {
                    return response()->json(['code' => 201]);
                }
            } else {
                return response()->json(['code' => 201]);
            }
        }
    /** remove-profile */
}
