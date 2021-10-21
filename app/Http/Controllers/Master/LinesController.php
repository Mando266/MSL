<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Lines;
use App\Models\Master\LinesType;
use Illuminate\Support\Facades\Auth;
class LinesController extends Controller
{
  
    public function index()
    {
        $this->authorize(__FUNCTION__,Lines::class);
        $lines = Lines::orderBy('id')->paginate(10);
        return view('master.lines.index',[
            'items'=>$lines,
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Lines::class);
        $line_types = LinesType::orderBy('name')->get();
        return view('master.lines.create',[
            'line_types'=>$line_types,

        ]); 
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Lines::class);
        $request->validate([
            'name' => 'required',
        ]);
        $lines = Lines::create($request->except('_token'));
        return redirect()->route('lines.index')->with('success',trans('line.created'));     }

    public function show($id)
    {
        //
    }

    public function edit(Lines $line)
    {
        $this->authorize(__FUNCTION__,Lines::class);
        $line_types = LinesType::orderBy('name')->get();
        return view('master.lines.edit',[
            'line'=>$line,
            'line_types'=>$line_types,
        ]);     
    }

    public function update(Request $request,Lines $line)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $this->authorize(__FUNCTION__,Lines::class);
        $line->update($request->except('_token'));
        return redirect()->route('lines.index')->with('success',trans('line.updated.success')); 
    }

    public function destroy($id)
    {
        $line =Lines::Find($id);
        $line->delete();

        return redirect()->route('lines.index')->with('success',trans('line.deleted.success'));
    }
}
