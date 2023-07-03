@extends('layouts.app')

@section('content')
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
                        <h3>Booking Reference No: {{ $booking->ref_no }}</h3>
                        <form action="{{ route('temperature-discrepancy.send-customer') }}" method="POST"
                              id="temperatureForm">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-condensed mb-4">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Container No</th>
                                        <th>TEMPERATURE</th>
                                        <th>GATE IN DATE</th>
                                        <th>TEMP BY THE TIME OF GATE IN</th>
                                        <th>MAIN LANE NO</th>
                                        <th>SECONDARY LANE NO</th>
                                        <th>COMMODITY DESCRIPTION</th>
                                        <th>REMOVE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($bookingContainerDetails as $detail)
                                        <tr>
                                            <td>{{ ++$loop->index }}</td>
                                            <td>
                                                <input type="text" class="form-control" name="container_no[]"
                                                       value="{{ optional($detail->container)->code }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="temperature[]"
                                                       value="{{ $detail->haz }}"
                                                       title="{{ $detail->haz }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="gateInDate[]">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="tempDiff[]">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="mainLaneNo[]"
                                                       value="{{ 'main lane' }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="secondaryLaneNo[]"
                                                       value="{{ 'second lane' }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="commodityDescription[]"
                                                       value="{{ $booking->commodity_description }}" readonly>
                                            </td>
                                            <td style="width: 85px;">
                                                <button type="button" class="btn btn-danger remove"><i
                                                            class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="20">{{ trans('home.no_data_found')}}</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="customerEmail">Customer Email (Shipper/FTW)</label>
                                            <select name="customerEmail" id="customerEmail" class="form-control">
                                                <option value="customer1@example.com">customer1@example.com</option>
                                                <option value="customer2@example.com">customer2@example.com</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="serviceProviderEmail">Service Provider Email (ATEB)</label>
                                            <select name="serviceProviderEmail" id="serviceProviderEmail"
                                                    class="form-control">
                                                <option value="provider1@example.com">provider1@example.com</option>
                                                <option value="provider2@example.com">provider2@example.com</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <button class="btn btn-primary" id="sendCustomer" value="sendCustomer"
                                                    type="submit">Send Customer Email
                                            </button>
                                        </div>
                                        <div class="col-md-6">
                                            <button class="btn btn-primary" id="sendServiceProvider"
                                                    value="sendServiceProvider" type="submit">Send Service Provider
                                                Email
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            removeRow();
            adjustFormAction();
        });

        function removeRow() {
            const removeButtons = document.querySelectorAll('.remove');

            removeButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const row = this.closest('tr');
                    row.remove();
                });
            });
        }

        function adjustFormAction() {
            const sendServiceProviderButton = document.getElementById('sendServiceProvider');
            const temperatureForm = document.getElementById('temperatureForm');

            sendServiceProviderButton.addEventListener('click', function () {
                temperatureForm.action = "{{ route('temperature-discrepancy.send-provider') }}";
            });
        }
    </script>
@endpush
