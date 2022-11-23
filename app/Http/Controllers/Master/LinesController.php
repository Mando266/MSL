<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Lines;
use App\Models\Master\LinesType;
use App\Models\Master\LinesWithType;
use Illuminate\Support\Facades\Auth;
class LinesController extends Controller
{
  
    public function index()
    {
        $this->authorize(__FUNCTION__,Lines::class);
        $lines = Lines::with('types.type')->orderBy('id')->paginate(10);
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
        $line = Lines::create($request->except('_token','line_type_id'));
        if(isset($request->line_type_id)){
            if(count($request->line_type_id) > 0){
                foreach($request->line_type_id as $lineType){
                    LinesWithType::create([
                        'line_id'=>$line->id,
                        'type_id'=>$lineType['type_id']
                    ]);
                }
            }
        }
        return redirect()->route('lines.index')->with('success',trans('line.created'));   
    }

    public function show($id)
    {
        //
    }

    public function edit(Lines $line)
    {
        $this->authorize(__FUNCTION__,Lines::class);
        $line_types = LinesType::orderBy('name')->get();
        $types = LinesWithType::where('line_id',$line->id)->get()->pluck('type_id')->toarray();
        return view('master.lines.edit',[
            'types'=>$types,
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
        $line->update($request->except('_token','line_type_id'));
        $newTypes = [];
        foreach($request->line_type_id as $item){
            array_push($newTypes,$item['type_id']);
        }
        $Oldtypes = LinesWithType::where('line_id',$line->id)->get();
        if(isset($Oldtypes)){
            if($Oldtypes->count() > 0){
                foreach($Oldtypes as $old){
                    if(!in_array($old->type_id, $newTypes)){
                        $old->delete();
                    }else{
                        $key = array_search($old->type_id, $newTypes);
                        unset($newTypes[$key]);
                    }
                }
                if(count($newTypes) > 0){
                    foreach($newTypes as $type){
                        LinesWithType::create([
                            'type_id'=>$type,
                            'line_id'=>$line->id
                        ]);
                    }
                }
            }else{
                if(isset($request->line_type_id)){
                    if(count($newTypes) > 0){
                        foreach($newTypes as $type){
                            LinesWithType::create([
                                'type_id'=>$type,
                                'line_id'=>$line->id
                            ]);
                        }
                    }
                }
            }
        }else{
            if(isset($request->line_type_id)){
                if(count($newTypes) > 0){
                    foreach($newTypes as $type){
                        LinesWithType::create([
                            'type_id'=>$type,
                            'line_id'=>$line->id
                        ]);
                    }
                }
            }
        }
        
        return redirect()->route('lines.index')->with('success',trans('line.updated.success')); 
    }

    public function destroy($id)
    {
        $line =Lines::Find($id);
        $lineTypes = LinesWithType::where('line_id',321)->get();
        if($lineTypes->count() > 0 ){
            foreach($lineTypes as $type){
                $type->delete();
            }
        }
        $line->delete();

        return redirect()->route('lines.index')->with('success',trans('line.deleted.success'));
    }
}
