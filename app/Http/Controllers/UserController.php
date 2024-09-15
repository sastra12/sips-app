<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function data()
    {
        $listdata = User::with(['role', 'user_waste_banks'])->get();
        // return response()->json($listdata);

        return Datatables::of($listdata)
            // for number
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return  '
                <button onclick="editDataAdmin(' . $data->id . ')" class="btn btn-xs btn-info">Edit</button>
                <button onclick="deleteData(`' . route('user.destroy', $data->id) . '`)" class="btn btn-xs btn-danger">Hapus</button>
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
        return view('admin.index', [
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role_user = $request->input('role_user');
        $rules = [
            'name' => 'required'
        ];

        if ($role_user == 2) {
            $rules['waste_name'] = 'required';
        }

        $validated = Validator::make($request->all(), $rules);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed added',
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::query()->find($id);
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
                'status' => 'Failed updated',
                'errors' => $validated->messages()
            ]);
        } else {
            $data = User::query()->find($id);
            $data->role_id = $request->input('role');
            $data->save();
            // Menyimpan ke tabel intermediate
            $data->user_waste_banks()->attach($request->input('waste_name'));

            return response()->json([
                'status' => 'Success',
                'message' => 'Success Updated Data'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = User::query()->find($id);
        $data->user_waste_banks()->detach();
        $data->delete();
    }
}
