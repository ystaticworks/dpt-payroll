<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filterType = $request->get('filter_type', 'single');
        $date       = $request->get('date', today()->toDateString());
        $dateFrom   = $request->get('date_from', today()->toDateString());
        $dateTo     = $request->get('date_to', today()->toDateString());

        $attendances = Attendance::with('employee.position')
            ->when($filterType === 'single', fn($q) => $q->whereDate('date', $date))
            ->when($filterType === 'range',  fn($q) => $q->whereBetween('date', [$dateFrom, $dateTo]))
            ->paginate(15)
            ->withQueryString();

        return view('attendances.index', compact('attendances', 'filterType', 'date', 'dateFrom', 'dateTo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attendance = Attendance::with('employee.position')->findOrFail($id);

        $month = request('month', now()->month);
        $year  = request('year', now()->year);

        $monthlyAttendances = Attendance::where('employee_id', $attendance->employee_id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy(fn($a) => $a->date->format('Y-m-d'));

        return view('attendances.show', compact('attendance', 'monthlyAttendances', 'month', 'year'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
