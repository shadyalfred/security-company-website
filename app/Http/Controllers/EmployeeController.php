<?php

namespace App\Http\Controllers;

use App\Imports\EmployeesImport;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = trans('All Employees');

        $employees = Employee::all();

        return view('employees.index', [
            'pageTitle' => $pageTitle,
            'employees' => $employees,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = trans('Add Employee');

        return view('employees.create')->with('pageTitle', $pageTitle);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate($this->getValidationRules());

        $validatedData['hired_on'] = Carbon::createFromFormat('d/m/Y', $validatedData['hired_on']);

        Employee::create($validatedData);

        return back()->with('success', 'Employee was created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        $pageTitle = trans('Edit Employee');

        return view('employees.edit', [
            'pageTitle' => $pageTitle,
            'employee'  => $employee,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $validatedData = $request->validate($this->getValidationRules($employee->id));

        $validatedData['hired_on'] = Carbon::createFromFormat('d/m/Y', $validatedData['hired_on']);

        $employee->update($validatedData);

        return back()->with('success', 'Employee was updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response('Success', 200);
    }

    /**
     * Show form of importing excel file
     *
     * @return \Illuminate\View\View
     */
    public function showImportExcelForm(): \Illuminate\View\View
    {
        $pageTitle = trans('Import Excel Files');

        return view('employees.excel_import')->with('pageTitle', $pageTitle);
    }

    /**
     * Import excel file
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resopnse
     */
    public function storeFromExcel(Request $request)
    {
        foreach ($request->files->all() as $excelFile) {
            Excel::import(new EmployeesImport, $excelFile);
        }

        return response('Success', 200);
    }

    /**
     * Get validation rules
     *
     * @param int $uniqueId
     * @return array
     */
    public function getValidationRules(int $uniqueId = null): array
    {
        return [
            'name'           => 'required|max:64',
            'national_id'    => 'required|max:64|unique:employees,national_id' . ($uniqueId ? ',' . $uniqueId : ''),
            'address'        => 'required|max:128',
            'phone'          => 'required|max:64',
            'age'            => 'required|digits_between:1,3|max:255',
            'notes'          => 'max:64',
            'job_location'   => 'required|max:32',
            'section'        => 'required|max:64',
            'hired_on'       => 'required|date_format:d/m/Y',
            'status'         => 'max:1',
            '3ohda'          => 'max:16',
            'kashf_amny'     => 'max:16',
            'no3_el_mo5alfa' => 'max:64',
            'pants'          => 'max:32',
            'summer_t_shirt' => 'max:32',
            'winter_t_shirt' => 'max:32',
            'jacket'         => 'max:32',
            'shoes'          => 'max:32',
            'vest'           => 'max:32',
            'eish'           => 'max:32',
            'donk'           => 'max:32',
            'notes_2'        => 'max:32',
        ];
    }
}