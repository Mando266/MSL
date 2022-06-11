@extends('layouts.app')
@section('content')
@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Containers</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Movements</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        </div>
                
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>Container No</th>
                                        <th>Error Code</th>
                                        <th>Allowed Movement</th>
                                        <th class='text-center' style='width:100px;'>delete </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                         
                                            <td>{{$item->container_id}}</td>
                                            <td>{{$item->error_code}}</td>
                                            <td>{{$item->allowed_code}}</td>
                                                        <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Movements-Delete')
                                                    <li>
                                                        <form action="{{route('movementerrors.destroy',['movementerror'=>$item->id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger"></button>
                                                        </form>
                                                    </li>
                                                    @endpermission
                                                </ul>
                                            </td>
                            
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="20">{{ trans('home.no_data_found')}}</td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>
                        </div>
                        <div class="paginating-container">
                            {{ $items->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('scripts')

<script>
function unlock(){
    document.getElementById('buttonSubmit').removeAttribute("disabled");
}
</script>
@endpush