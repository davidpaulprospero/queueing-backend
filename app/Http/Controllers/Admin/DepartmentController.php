<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Department;
use App\Models\TransactionType;

class DepartmentController extends Controller
{

// DEPARTMENT
    public function index()
    { 
        $departments = Department::get();
        return view('backend.admin.department.list', compact('departments'));
    }

    public function showForm()
    {
        $keyList = $this->keyList();
        return view('backend.admin.department.form', compact('keyList'));
    }

    // public function showFormTransaction()
    // {
    //     $departments = Department::get(); // Retrieve all departments from the database
    //     return view('backend.admin.department.transaction', compact('departments'));
    // }

    public function create(Request $request)
    { 
        @date_default_timezone_set(session('app.timezone')); 
        $validator = Validator::make($request->all(), [
            'name'        => 'required|unique:department,name|max:50',
            'description' => 'max:255',
            'transaction' => 'reuired|max:50|uniue:department,name',
            'key'         => 'required|unique:department,key|max:1',
            'status'      => 'required',
        ])
        ->setAttributeNames(array(
           'name' => trans('app.name'),
           'description' => trans('app.description'),
           'key' => trans('app.key_for_keyboard_mode'),
           'transaction' => trans('app.transaction_type'),
           'status' => trans('app.status')
        ));

        if ($validator->fails()) {
            return redirect('admin/department/create')
                ->withErrors($validator)
                ->withInput();
        } else {

            $save = Department::insert([
                'name'        => $request->name,
                'description' => $request->description,
                'key'         => $request->key,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => null,
                'status'      => $request->status
            ]);

            if ($save) {
                return back()->withInput()
                     ->with('message',trans('app.save_successfully'));
            } else {
                return back()->withInput()
                        ->with('exception', trans('app.please_try_again'));
            }

        }
    } 
    
    public function showEditForm($id = null)
    {
        $keyList = $this->keyList();
        $department = Department::where('id', $id)->first();
        return view('backend.admin.department.edit', compact('department', 'keyList'));
    }


    public function update(Request $request)
    { 
        @date_default_timezone_set(session('app.timezone'));

        $validator = Validator::make($request->all(), [
            'name'        => 'required|max:50|unique:department,name,'.$request->id,
            'description' => 'max:255',
            'key'         => 'required|max:1|unique:department,key,'.$request->id,
            'status'      => 'required',
        ])->setAttributeNames([
            'name' => trans('app.name'),
            'description' => trans('app.description'),
            'key' => trans('app.key_for_keyboard_mode'),
            'status' => trans('app.status')
        ]);

        if ($validator->fails()) {
            return redirect('admin/department/edit/'.$request->id)
                ->withErrors($validator)
                ->withInput();
        } else {
            $department = Department::findOrFail($request->id);
            $department->name = $request->name;
            $department->description = $request->description;
            $department->key = $request->key;
            $department->status = $request->status;
            $department->save();

            // Update transaction types
            foreach ($request->transaction_type as $transactionTypeId => $transactionTypeName) {
                $transactionType = TransactionType::findOrFail($transactionTypeId);
            
                // Only update if the transaction type has been modified
                if ($transactionType->name !== $transactionTypeName) {
                    $transactionType->name = $transactionTypeName;
                    $transactionType->save();
                }
            
                // Update the transaction type key
                $transactionTypeKey = $request->transaction_type_key[$transactionTypeId];
                if ($transactionType->key !== $transactionTypeKey) {
                    $transactionType->key = $transactionTypeKey;
                    $transactionType->save();
                }
            }
            

            return back()->with('message', trans('app.update_successfully'));
        }
    }

    public function delete($id = null)
    {
        $delete = Department::where('id', $id)->delete();
        return redirect('admin/department')->with('message', trans('app.delete_successfully'));
    } 
 
    public function keyList()
    {
        $chars = array_merge(range('1','9'), range('a','z'));
        $list = array();
        foreach($chars as $char)
        {
            if ($char != "v")
            $list[$char] = $char;
        }
        return $list;
    }

// TRANSAcTION TYPES 

    public function showTransactionTypes()
    {
        // Retrieve all transaction types with their associated departments
        $transactionTypes = TransactionType::with('departments')->get();
        
        // Pass the data to the view
        return view('backend.admin.department.transaction', compact('transactionTypes'));
    }

    public function createTransaction()
    {
        // Retrieve all departments to populate a dropdown select
        $departments = Department::get();

        // Get the selected department
        $selectedDepartmentId = request()->input('department_id');
        $selectedDepartment = null;
        if ($selectedDepartmentId) {
            $selectedDepartment = Department::with('transactionTypes')->find($selectedDepartmentId);
        }
        $keyList = $this->keyList();
        // Pass the data to the view
        return view('backend.admin.department.transaction', compact('departments', 'selectedDepartment', 'keyList'));
    }


    public function storeTransaction(Request $request)
    {
        // Validate the input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:department,id',
            
        ]);

        // Check if the transaction type already exists
        $existingTransactionType = TransactionType::where('name', $validatedData['name'])
            ->where('department_id', $validatedData['department_id'])
            ->first();

        if ($existingTransactionType) {
            // Transaction type with the same name already exists under the department
            return redirect()->back()->with('exception', trans('app.please_try_again'));
        }

          // Add the 'key' field to the validated data
        $validatedData['key'] = $request->input('key');


        // Create a new transaction type
        $transactionType = TransactionType::create($validatedData);
        

        // Redirect back or to a success page
        return redirect()->back()->with('message', trans('app.save_successfully'));
    }




    public function editTransaction(TransactionType $transactionType)
    {
        // Retrieve all departments to populate a dropdown select
        $departments = Department::all();
        
        // Pass the data to the view
        return view('admin.transaction_types.edit', compact('transactionType', 'departments'));
    }

    public function updateTransaction(Request $request, TransactionType $transactionType)
    {
        // Validate the input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'transaction_types' => 'nullable|array',
            'transaction_types.*' => 'exists:transaction_types,id',
        ]);

        // Update the transaction type
        $transactionType->update($validatedData);
        $transactionType->transactionTypes()->sync($validatedData['transaction_types'] ?? []);

        // Redirect back or to a success page
        return redirect()->back()->with('success', 'Transaction type updated successfully.');
    }


    public function destroyTransaction(TransactionType $transactionType)
    {
        // Delete the transaction type
        $transactionType->delete();
        
        // Redirect back or to a success page
        return redirect()->back()->with('message', trans('app.delete_successfully'));
    }
 
}