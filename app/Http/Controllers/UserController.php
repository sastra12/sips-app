<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function data()
    {
        $listdata = User::select('id', 'name', 'username', 'role_id')
            ->with(['role' => function ($query) {
                // ambil role id dan role name saja
                $query->select('role_id', 'role_name');
            }, 'user_waste_banks' => function ($query) {
                $query->select('waste_name');
            }])
            ->orderByDesc('created_at')
            ->get();

        return Datatables::of($listdata)
            // for number
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return  '
                <button onclick="editDataAdmin(' . $data->id . ')" class="btn btn-sm btn-info custom-btn-sm">Edit</button>
                <button onclick="deleteData(`' . route('user.destroy', $data->id) . '`)" class="btn btn-sm btn-danger custom-btn-sm">Hapus</button>
            ';
            })
            ->addColumn('role_name', function ($data) {
                if (count($data->user_waste_banks) != null) {
                    return $data->role->role_name . ' - ' .  $data->user_waste_banks[0]->waste_name;
                }
                return $data->role->role_name;
            })
            ->make();
    }

    public function index()
    {
        $roles = Role::query()->get();
        return view('admin-yrpw-new.manage-admin.index', [
            'roles' => $roles
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $role_user = $request->input('role_user');
        $rules = [
            'name' => 'required',
            'role_user' => 'required'
        ];

        if ($role_user == 2) {
            $rules['waste_name'] = 'required';
        }

        $validated = Validator::make($request->all(), $rules);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'Error',
                'errors' => $validated->messages()
            ]);
        } else {
            $data = new User();
            $data->name = $request->input('name');
            $data->username = $request->input('username');
            $data->password = bcrypt($request->input('password'));
            $data->role_id = $request->input('role_user');
            $data->save();

            // Menyimpan ke tabel intermediate
            $data->user_waste_banks()->attach($request->input('waste_name'));

            return response()->json([
                'status' => 'Success',
                'message' => 'Success Added Data'
            ]);
        }
    }

    public function show($id)
    {
        $user = User::query()->find($id);
        return response()->json($user);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $role_user = $request->input('role');
        $rules = [
            'role' => 'required'
        ];

        if ($role_user == 2) {
            $rules['waste_name'] = 'required';
        }

        $validated = Validator::make($request->all(), $rules);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'Error',
                'errors' => $validated->messages()
            ]);
        } else {
            $data = User::query()->find($id);
            $data->role_id = $request->input('role');
            $data->save();
            // Menyimpan ke tabel intermediate
            if ($request->input('role') != 2) {
                $data->user_waste_banks()->detach($request->input('waste_name'));
            } elseif ($request->input('role') == 2) {
                // apakah sebelumnya data relasinya ada
                $existingPivot = $data->user_waste_banks()->wherePivot('user_id', $id)->exists();
                if ($existingPivot) {
                    // kalo ada update waste_id nya
                    $data->user_waste_banks()->updateExistingPivot($data->user_waste_banks->first()->pivot->waste_id, [
                        'waste_id' => $request->input('waste_name')
                    ]);
                } else {
                    $data->user_waste_banks()->attach($request->input('waste_name'));
                }
            }
            return response()->json([
                'status' => 'Success',
                'message' => 'Success Updated Data'
            ]);
        }
    }

    public function destroy($id)
    {
        $data = User::query()->find($id);
        $data->user_waste_banks()->detach();
        $data->delete();
    }
}
