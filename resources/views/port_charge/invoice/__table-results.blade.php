<label>Invoice EGP
    <input value="{{ number_format($invoiceEgp, 2, '.', ',') }}"
           class="form-control border-0"
           disabled>
</label>
<label>Invoice USD
    <input value="{{ number_format($invoiceUsd, 2, '.', ',') }}"
           class="form-control border-0"
           disabled>
</label>
<label>Total USD
    <input value="{{ number_format($totalUsd, 2, '.', ',') }}" class="form-control border-0"
           disabled>
</label>
<div class="table-responsive">
    <table class="table table-bordered table-hover table-condensed mb-4">
        <thead>
        <tr>
            <th>#</th>
            <th>Invoice Number</th>
            <th>Country</th>
            <th>Line</th>
            <th>Port</th>
            <th>Vessel</th>
            <th>Voyage</th>
            <th>Total USD</th>
            <th>Invoice USD</th>
            <th>Invoice EGP</th>
            <th class='text-center' style='width:100px;'></th>
        </tr>
        </thead>
        <tbody>
        @forelse ($invoices as $key => $invoice)
            <tr>
                <td>{{ $invoices->firstItem() +  $key }}</td>
                <td>{{ $invoice->invoice_no }}</td>
                <td>{{ $invoice->country->name ?? '' }}</td>
                <td>{{ $invoice->line->name ?? '' }}</td>
                <td>{{ $invoice->port->name ?? '' }}</td>
                <td>{{ $invoice->vesselsNames() }}</td>
                <td>{{ $invoice->voyagesNames() }}</td>
                <td>{{ $invoice->total_usd }}</td>
                <td>{{ $invoice->invoice_usd }}</td>
                <td>{{ $invoice->invoice_egp }}</td>
                <td class="text-center">
                    <ul class="table-controls">
                        <li>
                            <a href="{{route('port-charge-invoices.edit', $invoice->id)}}"
                               data-toggle="tooltip" data-placement="top" title=""
                               data-original-title="edit">
                                <i class="far fa-edit text-success"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('port-charge-invoices.show', $invoice->id)}}"
                               data-toggle="tooltip" data-placement="top" title=""
                               data-original-title="show">
                                <i class="far fa-eye text-primary"></i>
                            </a>
                        </li>
                        <li>
                            <form action="{{route('port-charge-invoices.destroy', $invoice->id)}}"
                                  method="post">
                                @method('DELETE')
                                @csrf
                                <button style="border: none; background: none;"
                                        type="submit"
                                        class="fa fa-trash text-danger show_confirm"></button>
                            </form>
                        </li>
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
    {{ $invoices->render() }}
</div>
