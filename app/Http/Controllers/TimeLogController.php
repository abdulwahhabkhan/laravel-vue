<?php

namespace App\Http\Controllers;

use App\Models\TimeLog;
use Illuminate\Http\Request;

class TimeLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TimeLog::with(['created_by', 'user', 'ticket', 'project'])
                       ->orderby('id', 'asc')->paginate(100);
        return response()->json($data);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TimeLog  $timeLog
     * @return \Illuminate\Http\Response
     */
    public function show(TimeLog $timeLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TimeLog  $timeLog
     * @return \Illuminate\Http\Response
     */
    public function edit(TimeLog $timeLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TimeLog  $timeLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TimeLog $timeLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TimeLog  $timeLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(TimeLog $timeLog)
    {
        //
    }
}