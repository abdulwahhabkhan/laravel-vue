<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Models\Comment;
use App\Models\File;
use App\Models\Ticket;
use App\Models\TimeLog;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Ticket::with(['created_by', 'assigned_to', 'project'])
                      ->orderby('id', 'asc')->paginate($request->per_page);
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
     * @param  TicketRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketRequest $request)
    {
        if( $request->validated()) {
            // store
            $ticket = new Ticket();
            $ticket->title = $request->title;
            $ticket->description = $request->description;

            if($request->start_date)
                $ticket->start_date = date('Y-m-d', strtotime($request->start_date));
            if($request->end_date)
                $ticket->end_date = date('Y-m-d', strtotime($request->end_date));

            $ticket->assigned_to = $request->assigned_to;
            $ticket->created_by = $request->user()->id;
            $ticket->project_id = $request->project_id;
            $ticket->save();
            $data = Ticket::with(['created_by', 'assigned_to', 'project'])->findOrFail($ticket->id);
            return response()->json(["message"=>"Ticket created successfully", 'data'=>$data], 201);
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
        return response()->json(Ticket::with(['created_by', 'assigned_to', 'project'])->findOrFail($id), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TicketRequest $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(TicketRequest $request, Ticket $ticket)
    {
        if( $request->validated()) {
            // store
            $ticket->title = $request->title;
            $ticket->description = $request->description;
            $ticket->start_date = $request->start_date? date('Y-m-d', strtotime($request->start_date)): null;
            $ticket->end_date = $request->end_date? date('Y-m-d', strtotime($request->end_date)):null;
            $ticket->assigned_to = $request->assigned_to;
            $ticket->project_id = $request->project_id;
            $ticket->save();
            $data = Ticket::with(['created_by', 'assigned_to', 'project'])->findOrFail($ticket->id);
            return response()->json(["message"=>"Ticket updated successfully", 'data'=> $data], 202);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        $status = $ticket->delete();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Project Deleted!' : 'Error Deleting Project'
        ], 200);
    }

    public function comments($id, Request $request)
    {
        $dir = $request->get('sort');
        $dir = $dir? $dir : 'asc';
        return response()->json(Comment::with(['created_by'])->where('ticket_id', $id)->orderby('updated_at', $dir)->paginate(100), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  ticket.id  $id
     * @return Response
     */
    public function updateDates(Request $request, $id)
    {
        Ticket::where('id', $id)->update([
            'start_date' => $request->start_date? date('Y-m-d', strtotime($request->start_date)): null,
            'end_date' => $request->end_date? date('Y-m-d', strtotime($request->end_date)): null
        ]);

        return response()->json(["message"=>"Ticket dates updated successfully", 'data'=> $request->toArray()], 202);

    }

    /**
     * get time log entries for ticket.
     *
     * @param  Request $request
     * @param  ticket.id  $id
     * @return Response
     */
    public function timeEntries(Request $request, $id)
    {
        $data = TimeLog::with(['created_by', 'user', 'ticket', 'project'])
            ->where('ticket_id', $id)
            ->orderby('id', 'asc')->paginate(100);

        return response()->json($data);

    }

    /**
     * get files for ticket.
     *
     * @param  Request $request
     * @param  ticket.id  $id
     * @return Response
     */
    public function files(Request $request, $id)
    {
        $data = File::with(['user', 'ticket', 'project'])
                    ->where('ticket_id', $id)
                    ->orderby('id', 'asc')->paginate(100);

        return response()->json($data);

    }
}
