@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('voyages.index')}}">Voyages</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Add New Voyage</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
            <div class="widget-content widget-content-area">
                <form id="regForm" action="{{route('voyages.store')}}" method="POST">
                        @csrf
                    <div class="tab">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="vessel_idInput">Name *</label>
                                <select class="selectpicker form-control" id="vessel_idInput" data-live-search="true" name="vessel_id" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($vessels as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('vessel_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('vessel_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="voyage_noInput">Voyage No *</label>
                            <input type="text" class="form-control" id="voyage_noInput" name="voyage_no" value="{{old('voyage_no')}}"
                                 placeholder="Voyage No" autocomplete="off" autofocus>
                                @error('voyage_no')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                    <label for="leg_idInput">Leg</label>
                                    <select class="selectpicker form-control" id="leg_idInput" data-live-search="true" name="leg_id" data-size="10"
                                        title="{{trans('forms.select')}}">
                                        @foreach ($legs as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('leg_id') ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('leg_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="line_idInput">Line</label>
                                    <select class="selectpicker form-control" id="line_idInput" data-live-search="true" name="line_id" data-size="10"
                                        title="{{trans('forms.select')}}">
                                        @foreach ($lines as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('line_id') ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('line_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="tab">
                        <table id="voyagePort" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Port</th>
                                    <th>Terminal</th>
                                    <th>Road No</th>
                                    <th>ETA</th>
                                    <th>ETD</th>
                                    <th>
                                        <a id="add"> Add Port <i class="fas fa-plus"></i></a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <select class="selectpicker form-control" id="port" data-live-search="true" name="voyageport[0][port_from_name]" data-size="10"
                                            title="{{trans('forms.select')}}">
                                            @foreach ($ports as $item)
                                                <option value="{{$item->id}}" {{$item->name == old('port_from_name') ? 'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                    </select>
                                </td>
                                <td>
                                  <select class="form-control" id="terminal" data-live-search="true" name="voyageport[0][terminal_name]" data-size="10"
                                          title="{{trans('forms.select')}}">
                                          @foreach ($terminals as $item)
                                              <option value="{{$item->name}}" {{$item->name == old('terminal_name') ? 'selected':''}}>{{$item->name}}</option>
                                          @endforeach
                                  </select>
                                </td>
                                <td>
                                <input type="text" id="roadInput" name="voyageport[0][road_no]" class="form-control" autocomplete="off">
                                </td>
                                <td>
                                <input type="date" id="etaInput" name="voyageport[0][eta]" class="form-control">
                                </td>
                                <td>
                                <input type="date" id="etdInput" name="voyageport[0][etd]" class="form-control">
                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>

                        </div>
                        <div class="form-row">
                            <div class="col-md-12 text-center">
                                <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                                <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
                            </div>
                        </div>
                        <div style="text-align:center;margin-top:40px;">
                                <span class="step"></span>
                                <span class="step"></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
var currentTab = 0;
showTab(currentTab);
function showTab(n) {
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").innerHTML = "Submit";
  } else {
    document.getElementById("nextBtn").innerHTML = "Next";
  }
  fixStepIndicator(n)
}

function nextPrev(n) {
  var x = document.getElementsByClassName("tab");
  if (n == 1 && !validateForm()) return false;
  x[currentTab].style.display = "none";
  currentTab = currentTab + n;
  if (currentTab >= x.length) {
    document.getElementById("regForm").submit();
    return false;
  }
  showTab(currentTab);
}

function validateForm() {
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input");
  for (i = 0; i < y.length; i++) {
    if (y[i].value == "") {
    //   y[i].className += " invalid";
      valid = true;
    }
  }
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
  }
  return valid;
}

function fixStepIndicator(n) {
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  x[n].className += " active";
}

</script>
<script>
      $(document).ready(function(){
        $("#voyagePort").on("click", ".remove", function () {
        $(this).closest("tr").remove();
        });
     var counter  = 1;
        $("#add").click(function(){
                var tr = '<tr>'+
            '<td><select class="form-control port" id="port" data-live-search="true" name="voyageport['+counter+'][port_from_name]" data-size="10"><option value="">Select...</option>@foreach ($ports as $item)<option value="{{$item->id}}" {{$item->name == old('port_from_name') ? 'selected':''}}>{{$item->name}}</option>@endforeach</select></td>'+
            '<td><select class="form-control" id="terminal" data-live-search="true" name="voyageport['+counter+'][terminal_name]" data-size="10"><option value="">Select...</option>@foreach ($terminals as $item)<option value="{{$item->id}}" {{$item->name == old('terminal_name') ? 'selected':''}}>{{$item->name}}</option>@endforeach</select></td>'+
            '<td><input type="text" name="voyageport['+counter+'][road_no]" class="form-control" autocomplete="off"></td>'+
            '<td><input type="date" name="voyageport['+counter+'][eta]" class="form-control"></td>'+
            '<td><input type="date" name="voyageport['+counter+'][etd]" class="form-control"></td>'+
            '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
            '</tr>';
            counter++;
            $('#voyagePort').append(tr);
        });
    });
</script>

<script>

  $(document).ready(function (){
    
        $(function(){
                $('#port').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/master/terminals/${value}`).then(function(data){
                        let terminals = data.terminals || '';
                        console.log(terminals);
                        let list2 = [`<option value=''>Select...</option>`];
                        for(let i = 0 ; i < terminals.length; i++){
                            list2.push(`<option value='${terminals[i].name}'>${terminals[i].name} </option>`);
                        }
                let terminal = $('#terminal');
                console.log(list2);
                terminal.html(list2.join(''));
                });
            });
        });
  });
    function asd()
    {
      const ports = document.querySelectorAll('.port');
    console.log(ports);

ports.forEach((port) => {
  console.log(port)
  port.on('change',function(e){
    let value = e.target.value;
    let response =    $.get(`/api/master/terminals/${value}`).then(function(data){
        let terminals = data.terminals || '';
        console.log(terminals);
        let list2 = [`<option value=''>Select...</option>`];
        for(let i = 0 ; i < terminals.length; i++){
            list2.push(`<option value='${terminals[i].name}'>${terminals[i].name} </option>`);
        }
let terminal = $('#terminal');
console.log(list2);
terminal.html(list2.join(''));
     });
  });
});
    }    
</script>
@endpush

@push('styles')
<style>
* {
  box-sizing: border-box;
}

body {
  background-color: #f1f1f1;
}

#regForm {
  font-family: Raleway;
  width: 100%;
  min-width: 300px;
}

h1 {
  text-align: center;
}


/* Mark input boxes that gets an error on validation: */
input.invalid {
  background-color: #ffdddd;
}

/* Hide all steps by default: */
.tab {
  display: none;
}

button {
  background-color: #1b55e2;
  color: #ffffff;
  border: none;
  padding: 10px 20px;
  font-size: 17px;
  font-family: Raleway;
  cursor: pointer;
}

button:hover {
  opacity: 0.8;
}

#prevBtn {
  background-color: #bbbbbb;
}

/* Make circles that indicate the steps of the form: */
.step {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbbbbb;
  border: none;
  border-radius: 50%;
  display: inline-block;
  opacity: 0.5;
}

.step.active {
  opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
  background-color: #1b55e2;
}
</style>
@endpush
