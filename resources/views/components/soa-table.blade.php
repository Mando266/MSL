@props([
    'id',
    'portCharges'
])

<table id="{{ $id ?? '' }}" {{ $attributes->merge(['class' => 'table table-bordered table-hover table-condensed mb-4']) }}>
    <thead>
    <tr>
        <th>Remove</th>
        <th style="min-width: 222px">Type</th>
        <th style="min-width: 222px">Service</th>
        <th style="min-width: 222px">BL NO</th>
        <th style="min-width: 222px">CONTAINER NO</th>
        <th style="min-width: 222px">TS</th>
        <th style="min-width: 222px">SHIPMENT TYPE</th>
        <th style="min-width: 222px">QUOTATION TYPE</th>
        <th data-field="thc">THC</th>
        <th data-field="storage">STORAGE</th>
        <th data-field="power">POWER</th>
        <th data-field="shifting">SHIFTING</th>
        <th data-field="disinf">DISINF</th>
        <th data-field="hand-fes-em">HAND-FES-EM</th>
        <th data-field="gat-lift-off">GAT-LIFT OFF-INBND-EM-FT40</th>
        <th data-field="gat-lift-on">GAT-LIFT ON-INBND-EM-FT40</th>
        <th colspan="2" data-field="pti">PTI</th>
        <th data-field="add-plan">ADD-PLAN</th>
    </tr>
{{--    <tr>--}}
{{--        <th rowspan=2 height=98 data-field="thc">20FT</th>--}}
{{--        <th rowspan=2 data-field="thc">40FT</th>--}}
{{--        <th rowspan=2 data-field="power">Free Time</th>--}}
{{--        <th rowspan=2 data-field="power">20FT</th>--}}
{{--        <th rowspan=2 data-field="power">40FT</th>--}}
{{--        <th rowspan=2 data-field="shifting">20FT</th>--}}
{{--        <th rowspan=2 data-field="shifting">40FT</th>--}}
{{--        <th rowspan=2 data-field="disinf">20FT</th>--}}
{{--        <th rowspan=2 data-field="disinf">40FT</th>--}}
{{--        <th rowspan=2 data-field="hand-fes-em">20FT</th>--}}
{{--        <th rowspan=2 data-field="hand-fes-em">40FT</th>--}}
{{--        <th rowspan=2 data-field="gat-lift-off">20FT</th>--}}
{{--        <th rowspan=2 data-field="gat-lift-off">40FT</th>--}}
{{--        <th rowspan=2 data-field="gat-lift-on">20FT</th>--}}
{{--        <th rowspan=2 data-field="gat-lift-on">40FT</th>--}}
{{--        <th colspan=2 data-field="pti">20FT & 40FT</th>--}}
{{--        <th rowspan=2 data-field="add-plan">20FT</th>--}}
{{--        <th rowspan=2 data-field="add-plan">40FT</th>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <th data-field="pti">failed</th>--}}
{{--        <th data-field="pti">pass</th>--}}
{{--    </tr>--}}
    </thead>
    <tbody>
    <tr>
        <td style="width:85px;">
            <button type="button" class="btn btn-danger removeContact"><i
                        class="fa fa-trash"></i></button>
        </td>
        <td>
            <select name="port_charge_type[]" class="form-control charge_type" required>
                <option hidden selected>Select</option>
                @foreach($portCharges as $portCharge)
                    @php
                        $conditions = [
                            'table1' => ['FULL-IMPORT', 'FULL-EXPORT'],
                            'table2' => ['EMPTY-EXPORT'],
                            'table3' => ['EMPTY-IMPORT'],
                            'table4' => ['FULL-TRANSHIPMENT', 'EMPTY-TRANSHIPMENT'],
                        ];
                    @endphp

                    @if (in_array($portCharge->name, $conditions[$id]))
                        <option value="{{ $portCharge->id }}">{{ $portCharge->name }}</option>
                    @endif
                @endforeach
            </select>
        </td>
        <td><select name="service[]" class="form-control service_type" required>
                <option hidden selected>Select</option>
                <option value="001-VSL-RE-STW-OPR">001-VSL-RE-STW-OPR</option>
                <option value="005-VSL-DIS-OPR">005-VSL-DIS-OPR</option>
                <option value="006-VSL-LOD-OPR">006-VSL-LOD-OPR</option>
                <option value="007-VSL-TRNSHP-OPR">007-VSL-TRNSHP-OPR</option>
                <option value="011-VSL-HOL-WRK">011-VSL-HOL-WRK</option>
                <option value="018-YARD-SERV">018-YARD-SERV</option>
                <option value="019-LOG-SERV">019-LOG-SERV</option>
                <option value="020-HAND-FES">020-HAND-FES</option>
                <option value="021-STRG-INBND-FL-CONTRS">021-STRG-INBND-FL-CONTRS
                </option>
                <option value="024-STRG-OUTBND-CONTRS-FL">
                    024-STRG-OUTBND-CONTRS-FL
                </option>
                <option value="025-STRG-OUTBND-CONTRS-EM">
                    025-STRG-OUTBND-CONTRS-EM
                </option>
                <option value="031-STRG-PR-DR-CONTRS">031-STRG-PR-DR-CONTRS</option>
                <option value="033-REFR-CONTR-PWR-SUP">033-REFR-CONTR-PWR-SUP
                </option>
                <option value="037-MISC-REV-GAT-SERV">037-MISC-REV-GAT-SERV</option>
                <option value="038-MISC-REV-YARD-CRN-SHIFTING">
                    038-MISC-REV-YARD-CRN-SHIFTING
                </option>
                <option value="039-MISC-REV-GAT-SERV-LIFT OFF">
                    039-MISC-REV-GAT-SERV-LIFT OFF
                </option>
                <option value="045-MISC-REV-ELEC-REP-SERV">
                    045-MISC-REV-ELEC-REP-SERV
                </option>
                <option value="051-VSL-OPR-ADD-PLAN">051-VSL-OPR-ADD-PLAN</option>
                <option value="060-DISINFECTION OF CONTAINERS">060-DISINFECTION OF
                    CONTAINERS
                </option>
            </select></td>
        <td><input type="text" class="form-control ref-no-td" name="bl_no[]"></td>
        <td><input type="text" name="container_no[]"
                   class="container_no form-control"></td>
        <td><input type="text" name="is_transhipment[]"
                   class="is_transhipment form-control"></td>
        <td><input type="text" name="shipment_type[]"
                   class="shipment_type form-control"></td>
        <td><input type="text" name="quotation_type[]"
                   class="quotation_type form-control"></td>
        <td data-field="thc"><input type="text" name="thc_cost[]" class="form-control dynamic-input" data-field="thc_cost"></td>
{{--        <td data-field="thc"><input type="text" name="thc_40ft[]" class="form-control dynamic-input" data-field="thc_40ft"></td>--}}
        <td data-field="storage"><input type="text" name="storage_cost[]" class="form-control dynamic-input" data-field="storage_cost"></td>
        <td data-field="power"><input type="text" name="power_cost[]" class="form-control dynamic-input" data-field="power_cost"></td>
{{--        <td data-field="power"><input type="text" name="power_20ft[]" class="form-control dynamic-input" data-field="power_20ft"></td>--}}
{{--        <td data-field="power"><input type="text" name="power_40ft[]" class="form-control dynamic-input" data-field="power_40ft"></td>--}}
        <td data-field="shifting"><input type="text" name="shifting_cost[]" class="form-control dynamic-input" data-field="shifting_cost">
        </td>
{{--        <td data-field="shifting"><input type="text" name="shifting_40ft[]" class="form-control dynamic-input" data-field="shifting_40ft">--}}
{{--        </td>--}}
        <td data-field="disinf"><input type="text" name="disinf_cost[]" class="form-control dynamic-input" data-field="disinf_cost"></td>
{{--        <td data-field="disinf"><input type="text" name="disinf_40ft[]" class="form-control dynamic-input" data-field="disinf_40ft"></td>--}}
        <td data-field="hand-fes-em"><input type="text" name="hand_fes_em_cost[]" class="form-control dynamic-input"
                   data-field="hand_fes_em_cost"></td>
{{--        <td data-field="hand-fes-em"><input type="text" name="hand_fes_em_40ft[]" class="form-control dynamic-input"--}}
{{--                   data-field="hand_fes_em_40ft"></td>--}}
        <td data-field="gat-lift-off"><input type="text" name="gat_lift_off_inbnd_em_ft40_cost[]" class="form-control dynamic-input"
                   data-field="gat_lift_off_inbnd_em_ft40_cost"></td>
{{--        <td data-field="gat-lift-off"><input type="text" name="gat_lift_off_inbnd_em_ft40_40ft[]" class="form-control dynamic-input"--}}
{{--                   data-field="gat_lift_off_inbnd_em_ft40_40ft"></td>--}}
        <td data-field="gat-lift-on"><input type="text" name="gat_lift_on_inbnd_em_ft40_cost[]" class="form-control dynamic-input"
                   data-field="gat_lift_on_inbnd_em_ft40_cost"></td>
{{--        <td data-field="gat-lift-on"><input type="text" name="gat_lift_on_inbnd_em_ft40_40ft[]" class="form-control dynamic-input"--}}
{{--                   data-field="gat_lift_on_inbnd_em_ft40_40ft"></td>--}}
        <td data-field="pti"><input type="text" name="pti_cost[]" class="form-control dynamic-input" data-field="pti_cost"></td>
        <td data-field="pti">
            <select style="min-width: 100px" class="form-control pti-type" name="pti_type[]">
                <option value="passed" data-cost="0" selected>Passed</option>
                <option value="failed" data-cost="0">Failed</option>
            </select>
        </td>
{{--        <td data-field="pti"><input type="text" name="pti_40ft[]" class="form-control dynamic-input" data-field="pti_passed"></td>--}}
        <td data-field="add-plan"><input type="text" name="add_plan_cost[]" class="form-control dynamic-input"
                   data-field="add_plan_cost"></td>
{{--        <td data-field="add-plan"><input type="text" name="add_plan_40ft[]" class="form-control dynamic-input"--}}
{{--                   data-field="add_plan_40ft"></td>--}}

    </tbody>

</table>

@push('scripts')
    <script>

    </script>
@endpush