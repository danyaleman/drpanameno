<?php

namespace App\Http\Controllers;

use App\Doctor;
use App\Invoice;
use App\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class AccountantController extends Controller
{
    public $limit;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (session()->has('page_limit')) {
                $this->limit = session()->get('page_limit');
            } else {
                $this->limit = Config::get('app.page_limit');
            }
            return $next($request);
        });
        $this->middleware('sentinel.auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;
        $accountant_role = Sentinel::findRoleBySlug('accountant');
        $accountants = $accountant_role->users()->with('roles')->where('is_deleted', 0)->orderByDesc('id')->get();

        // Load Datatables
        if ($request->ajax()) {
            return DataTables::of($accountants)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    $name = $row->first_name . ' ' . $row->last_name;
                    return $name;
                })
                ->addColumn('option', function ($row) use ($role) {
                    if ($role == 'admin') {
                        $option = '
                            <a href="accountant/' . $row->id . '">
                                <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light mb-2 mb-md-0" title="View Profile">
                                    <i class="mdi mdi-eye"></i>
                                </button>
                            </a>
                            <a href="accountant/' . $row->id . '/edit">
                                <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light mb-2 mb-md-0" title="Update Profile">
                                    <i class="mdi mdi-lead-pencil"></i>
                                </button>
                            </a>
                            <a href="javascript:void(0)">
                                <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light mb-2 mb-md-0" title="Deactivate Profile" data-id="' . $row->id . '" id="delete-accountant">
                                    <i class="mdi mdi-trash-can"></i>
                                </button>
                            </a>';
                    } elseif ($role == 'doctor') {
                        $option = '
                            <a href="accountant/' . $row->id . '">
                                <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light mb-2 mb-md-0" title="View Profile">
                                    <i class="mdi mdi-eye"></i>
                                </button>
                            </a>';
                    }
                    return $option;
                })->rawColumns(['option'])->make(true);
        }
        // End

        return view('accountant.accountants', compact('user', 'role', 'accountants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('accountant.create')) {
            $role = $user->roles[0]->slug;
            $accountant = null;
            return view('accountant.accountant-details', compact('user', 'role', 'accountant'));
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
        if ($user->hasAccess('accountant.create')) {
            $validatedData = $request->validate([
                'first_name' => 'required|alpha',
                'last_name' => 'required|alpha',
                'mobile' => 'required|numeric|digits_between:8,20',
                'email' => 'required|email|unique:users|regex:/(.+)@(.+)\.(.+)/i|max:50',
                'profile_photo' => 'image|mimes:jpg,png,jpeg,gif,svg|max:500'
            ]);
            if ($request->profile_photo != null) {
                $request->validate([
                    'profile_photo' => 'image'
                ]);
                $file = $request->file('profile_photo');
                $extention = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $extention;
                $file->move(public_path('storage/images/users'), $fileName);
                $validatedData['profile_photo'] = $fileName;
            }
            try {
                $user = Sentinel::getUser();
                // Set Default Password for Doctor
                $validatedData['password'] = Config::get('app.DEFAULT_PASSWORD');
                $validatedData['created_by'] = $user->id;
                $validatedData['updated_by'] = $user->id;
                //Create a new user
                $accountant = Sentinel::registerAndActivate($validatedData);
                //Attach the user to the role
                $role = Sentinel::findRoleBySlug('accountant');
                $role->users()->attach($accountant);
                return redirect('accountant')->with('success', 'Accountant created successfully!');
            } catch (Exception $e) {
                return redirect('accountant')->with('error', 'Something went wrong!!! ' . $e->getMessage());
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
    public function show(User $accountant)
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('accountant.view')) {
            $user_id = $accountant->id;
            $accountant = $user::whereHas('roles', function ($rq) {
                $rq->where('slug', 'accountant');
            })->where('id', $accountant->id)->where('is_deleted', 0)->first();
            if ($accountant) {
                $role = $user->roles[0]->slug;
                $invoices = Invoice::where('is_deleted', 0)->paginate($this->limit, '*', 'invoice');
                $doctor_role = Sentinel::findRoleBySlug('doctor');
                $doctors = $doctor_role->users()->with(['roles', 'doctor'])->where('is_deleted', 0)->paginate($this->limit);
                $unpaid_invoices = Invoice::where('is_deleted', 0)->where('payment_status', 'Unpaid')->count();
                $payment = Invoice::join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
                    ->where('payment_status', 'Paid')
                    ->where('invoices.is_deleted', 0)
                    ->where('invoice_details.is_deleted', 0)
                    ->sum('invoice_details.amount');
                $payment_due = Invoice::join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
                    ->where('payment_status', 'Unpaid')
                    ->where('invoices.is_deleted', 0)
                    ->where('invoice_details.is_deleted', 0)
                    ->sum('invoice_details.amount');

                return view('accountant.accountant-profile', compact('user', 'role', 'accountant', 'payment', 'invoices', 'unpaid_invoices', 'doctors'));
            } else {
                return redirect('/dashboard')->with('error', 'Receptionist not found');
            }
        } else {
            return view('error.403');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $accountant)
    {
        $user = Sentinel::getUser();
        $accountant = $user::whereHas('roles', function ($rq) {
            $rq->where('slug', 'accountant');
        })->where('id', $accountant->id)->where('is_deleted', 0)->first();
        if ($accountant) {

            if ($user->hasAccess('accountant.update')) {
                $role = $user->roles[0]->slug;
                return view('accountant.accountant-edit', compact('user', 'role', 'accountant'));
            } else {
                return view('error.403');
            }
        } else {
            return redirect('/dashboard')->with('error', 'Accountant not found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $accountant)
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('accountant.update')) {
            $validatedData = $request->validate([
                'first_name' => 'required|alpha',
                'last_name' => 'required|alpha',
                'mobile' => 'required|numeric|digits_between:8,20',
                'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i|max:50',
                'profile_photo' => 'image|mimes:jpg,png,jpeg,gif,svg|max:500'
            ]);
            try {
                $user = Sentinel::getUser();
                $role = $user->roles[0]->slug;
                if ($request->hasFile('profile_photo')) {
                    $des = 'storage/images/users/.' . $accountant->profile_photo;
                    if (File::exists($des)) {
                        File::delete($des);
                    }
                    $file = $request->file('profile_photo');
                    $extension = $file->getClientOriginalExtension();
                    $imageName = time() . '.' . $extension;
                    $file->move(public_path('storage/images/users'), $imageName);
                    $accountant->profile_photo = $imageName;
                }
                $accountant->first_name = $validatedData['first_name'];
                $accountant->last_name = $validatedData['last_name'];
                $accountant->mobile = $validatedData['mobile'];
                $accountant->email = $validatedData['email'];
                $accountant->updated_by = $user->id;
                $accountant->save();
                if ($role == 'accountant') {
                    return redirect('/dashboard')->with('success', 'Profile updated successfully!');
                } else {
                    return redirect('accountant')->with('success', 'Accountant Profile updated successfully!');
                }
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
        if ($user->hasAccess('accountant.delete')) {
            try {
                $accountant = User::where('id', $request->id)->first();
                if ($accountant != Null) {
                    $accountant->is_deleted = 1;
                    $accountant->save();
                    return response()->json([
                        'isSuccess' => true,
                        'message' => 'Accountant deleted successfully.',
                        'data' => $accountant,
                    ], 200);
                } else {
                    return response()->json([
                        'isSuccess' => false,
                        'message' => 'Accountant not found.',
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
