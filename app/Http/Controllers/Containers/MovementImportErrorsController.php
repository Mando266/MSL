<?php

namespace App\Http\Controllers\Containers;

use App\Http\Controllers\Controller;
use App\MovementImportErrors;
use Illuminate\Http\Request;

class MovementImportErrorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize(__FUNCTION__,Movements::class);
        $movementErrors = MovementImportErrors::paginate(30);
        return view('containers.movementerrors.index',[
            'items' => $movementErrors,
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MovementImportErrors  $movementImportErrors
     * @return \Illuminate\Http\Response
     */
    public function show(MovementImportErrors $movementImportErrors)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MovementImportErrors  $movementImportErrors
     * @return \Illuminate\Http\Response
     */
    public function edit(MovementImportErrors $movementImportErrors)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MovementImportErrors  $movementImportErrors
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MovementImportErrors $movementImportErrors)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MovementImportErrors  $movementImportErrors
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        MovementImportErrors::query()->truncate();
        return redirect()->route('movementerrors.index')->with('success',trans('MovementErrors.deleted.success'));    }
}
