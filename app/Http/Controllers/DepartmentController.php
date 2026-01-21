<?php

namespace App\Http\Controllers;

use App\Departments;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('department.list')) {
            $role = $user->roles[0]->slug;

            $departments = Departments::withCount(['doctor' => function ($doctor) {
                $doctor->select(DB::raw('COUNT(id) AS doctors'));
            }])->where('is_deleted', 0)->orderByDesc('id')->get();
            // Load Datatables
            if ($request->ajax()) {
                return DataTables::of($departments)
                    ->addIndexColumn()
                    ->addColumn('option', function ($row) use ($role) {
                        if ($role != 'patient') {
                            if ($role == 'admin') {
                                $option = '<a href="department/' . $row->id . '/edit">
                                    <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light mb-2 mb-md-0" title="Update Profile">
                                        <i class="mdi mdi-lead-pencil"></i>
                                    </button>
                                </a>
                                <a href="javascript:void(0)">
                                <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light mb-2 mb-md-0" title="Deactivate Department" data-id="' . $row->id . '" id="delete-department">
                                    <i class="mdi mdi-trash-can"></i>
                                </button>';
                            }
                        } else {
                            $option = '';
                        }
                        return $option;
                    })->rawColumns(['option'])->make(true);
            }
            // End
            return view('department.department', compact('user', 'role'));
        } else {
            return view('error.403');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('department.create')) {
            $role = $user->roles[0]->slug;
            $department = null;
            return view('department.department-details', compact('user', 'role', 'department'));
        } else {
            return view('error.403');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('department.create')) {
            $validatedData = $request->validate([
                'name' => 'required|alpha|max:250',
                'description' => 'required|regex:/^[a-zA-Z\s]+$/|max:250',
            ]);
            try {
                //Create a new department
                Departments::create($validatedData);

                //Attach the user to the role
                return redirect('department')->with('success', 'Department created successfully!');
            } catch (Exception $e) {
                return redirect('department')->with('error', 'Something went wrong!!! ' . $e->getMessage());
            }
        } else {
            return view('error.403');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Departments $department)
    {
        $user = Sentinel::getUser();
        if ($department) {
            if ($user->hasAccess('department.update')) {
                $role = $user->roles[0]->slug;
                return view('department.department-details', compact('user', 'role', 'department'));
            } else {
                return view('error.403');
            }
        } else {
            return redirect('/dashboard')->with('error', 'Department not found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Departments $department)
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('department.update')) {
            $validatedData = $request->validate([
                'name' => 'required|alpha|max:250',
                'description' => 'required|regex:/^[a-zA-Z\s]+$/|max:250',
            ]);
            try {
                $department->name = $validatedData['name'];
                $department->description = $validatedData['description'];
                $department->save();

                return redirect('department')->with('success', 'Department updated successfully!');
            } catch (Exception $e) {
                return redirect('accountant')->with('error', 'Something went wrong!!! ' . $e->getMessage());
            }
        } else {
            return view('error.403');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('department.delete')) {
            try {
                $department = Departments::where('id', $request->id)->first();
                if ($department != Null) {
                    $department->is_deleted = 1;
                    $department->save();
                    return response()->json([
                        'isSuccess' => true,
                        'message' => 'Department deleted successfully.',
                        'data' => $department,
                    ], 200);
                } else {
                    return response()->json([
                        'isSuccess' => false,
                        'message' => 'Department not found.',
                        'data' => [],
                    ], 409);
                }
            } catch (Exception $e) {
                return response()->json([
                    'isSuccess' => false,
                    'message' => 'Something went wrong!!!' . $e->getMessage(),
                    'data' => [],
                ], 409);
            }
        } else {
            return response()->json([
                'isSuccess' => false,
                'message' => 'You have no permission to delete Accountant',
                'data' => [],
            ], 409);
        }
    }
}
