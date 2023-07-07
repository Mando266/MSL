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
                        <form action="{{ route('temperature-discrepancy.send') }}" method="POST"
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
                                                <input type="date" class="form-control" name="gateInDate[]">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="tempDiff[]">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="mainLaneNo[]">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="secondaryLaneNo[]">
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
                                        <div class="col-md-12">
                                            <label for="customerEmail">Customer Email (Shipper/FTW)</label>
                                            <select class="selectpicker form-control w-100" id="customerEmail"
                                                    data-live-search="true" data-size="10"
                                                    multiple="multiple">
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->get('emails') }}">{{$customer->get('name')}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label for="serviceProviderEmail">Service Provider Email (ATEB)</label>
                                            <select class="selectpicker form-control w-100" id="serviceProviderEmail"
                                                    data-live-search="true" data-size="10"
                                                    multiple="multiple">
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->email }}">{{$supplier->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-10">
                                            <label for="selectedCustomerEmails">Selected Customer Emails:</label>
                                            <textarea class="form-control" id="selectedCustomerEmails"
                                            ></textarea>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button class="btn btn-primary float-right mt-2" id="sendCustomer"
                                                    value="sendCustomer" type="submit">Send Customer Email
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-10">
                                            <label for="selectedServiceProviderEmails"
                                            >Selected Service Provider Emails:</label>
                                            <textarea class="form-control" id="selectedServiceProviderEmails"
                                            ></textarea>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button class="btn btn-primary float-right mt-2" id="sendServiceProvider"
                                                    value="sendServiceProvider" type="submit"
                                            >Send Service Provider Email
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
        $(document).ready(() => {
            let customerSelect = $('#customerEmail')
            let providerSelect = $('#serviceProviderEmail')

            const updateSelectedCustomerEmails = () => {
                const selectedcEmails = customerSelect.val().join('\n');
                $('#selectedCustomerEmails').val(selectedcEmails)
            }

            const updateSelectedServiceProviderEmails = () => {
                const selectedEmails = providerSelect.val().join('\n');
                $('#selectedServiceProviderEmails').val(selectedEmails);
            }

            customerSelect.on('changed.bs.select', (e, clickedIndex, isSelected, previousValue) => {
                updateSelectedCustomerEmails()
                removeEmptyLines()
            })

            providerSelect.on('changed.bs.select', (e, clickedIndex, isSelected, previousValue) => {
                updateSelectedServiceProviderEmails()
                removeEmptyLines()
            })

            updateSelectedCustomerEmails()
            updateSelectedServiceProviderEmails()
        });

        document.addEventListener('DOMContentLoaded', () => {
            removeRow()
            setEmailsInput()
        })

        const removeRow = () => {
            const removeButtons = document.querySelectorAll('.remove');

            removeButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const row = button.closest('tr')
                    row.remove()
                });
            });
        };

        const setEmailsInput = () => {
            document.getElementById('sendServiceProvider').addEventListener('click', () => {
                document.getElementById('selectedServiceProviderEmails').name = "emails"
            })
            document.getElementById('sendCustomer').addEventListener('click', () => {
                document.getElementById('selectedCustomerEmails').name = "emails"
            })
        }

        const removeEmptyLines = () => {
            let textAreas = document.getElementsByTagName('textarea')
            
            Array.from(textAreas).forEach((textarea) => {
                const lines = textarea.value.split('\n')
                const nonEmptyLines = lines.filter((line) => line.trim() !== '')
                textarea.value = nonEmptyLines.join('\n')
            })
        }
        
    </script>

@endpush
