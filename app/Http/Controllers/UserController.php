<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            ->where('id', '!=', Auth::user()->id)
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

    public function store(StoreUserRequest $request)
    {
        // Validation dengan form request
        DB::beginTransaction();
        try {
            $validated = $request->safe();
            $data = new User();
            $data->name = $validated['name'];
            // kenapa kok pakai $request->input, karena username dan passwordnya tidak di validasi
            $data->username = $request->input('username');
            $data->password = bcrypt($request->input('password'));
            $data->role_id = $validated['role_user'];
            $data->save();

            // Menyimpan ke tabel intermediate
            $data->user_waste_banks()->attach($request->input('waste_name'));
            DB::commit();

            return response()->json([
                'status' => 'Success',
                'message' => 'Success Added Data'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        // }
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

    public function update(UpdateUserRequest $request, $id)
    {
        // Validation dengan form request
        $validated = $request->safe();
        $data = User::query()->find($id);
        $data->role_id = $validated['role'];
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

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $data = User::query()->find($id);
            $data->user_waste_banks()->detach();
            $data->delete();
            DB::commit();
            return response()->json([
                'status' => 'Success',
                'message' => "Sukses menghapus data"
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'False',
                'message' => 'Gagal menghapus data'
            ]);
        }
    }
}
