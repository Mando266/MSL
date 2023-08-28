@props([
    'id',
    'portCharges'
])

<table id="{{ $id ?? '' }}" {{ $attributes->merge(['class' => 'table table-bordered table-hover table-condensed mb-4']) }}>
    <thead>
    <tr>
        <th rowspan="3">Remove</th>
        <th rowspan="3" style="min-width: 222px">Type</th>
        <th rowspan="3" style="min-width: 222px">Service</th>
        <th rowspan="3" style="min-width: 222px">BL NO</th>
        <th rowspan="3" style="min-width: 222px">CONTAINER NO</th>
        <th rowspan="3" style="min-width: 222px">TS</th>
        <th rowspan="3" style="min-width: 222px">SHIPMENT TYPE</th>
        <th rowspan="3" style="min-width: 222px">QUOTATION TYPE</th>
        <th colspan=2 data-field="thc">THC</th>
        <th colspan=7 data-field="storage">STORAGE</th>
        <th colspan=3 data-field="power">POWER</th>
        <th colspan=2 data-field="shifting">SHIFTING</th>
        <th colspan=2 data-field="disinf">DISINF</th>
        <th colspan=2 data-field="hand-fes-em">HAND-FES-EM</th>
        <th colspan=2 data-field="gat-lift-off">GAT-LIFT OFF-INBND-EM-FT40</th>
        <th colspan=2 data-field="gat-lift-on">GAT-LIFT ON-INBND-EM-FT40</th>
        <th colspan=2 data-field="pti">PTI</th>
        <th colspan=2 data-field="add-plan">ADD-PLAN</th>
    </tr>
    <tr>
        <th rowspan=2 height=98 data-field="thc">20FT</th>
        <th rowspan=2 data-field="thc">40FT</th>
        <th rowspan=2 data-field="storage">Free Time</th>
        <th colspan=3 data-field="storage">Slab1</th>
        <th colspan=3 data-field="storage">Slab2</th>
        <th rowspan=2 data-field="power">Free Time</th>
        <th rowspan=2 data-field="power">20FT</th>
        <th rowspan=2 data-field="power">40FT</th>
        <th rowspan=2 data-field="shifting">20FT</th>
        <th rowspan=2 data-field="shifting">40FT</th>
        <th rowspan=2 data-field="disinf">20FT</th>
        <th rowspan=2 data-field="disinf">40FT</th>
        <th rowspan=2 data-field="hand-fes-em">20FT</th>
        <th rowspan=2 data-field="hand-fes-em">40FT</th>
        <th rowspan=2 data-field="gat-lift-off">20FT</th>
        <th rowspan=2 data-field="gat-lift-off">40FT</th>
        <th rowspan=2 data-field="gat-lift-on">20FT</th>
        <th rowspan=2 data-field="gat-lift-on">40FT</th>
        <th colspan=2 data-field="pti">20FT & 40FT</th>
        <th rowspan=2 data-field="add-plan">20FT</th>
        <th rowspan=2 data-field="add-plan">40FT</th>
    </tr>
    <tr>
        <th data-field="storage">Period</th>
        <th data-field="storage">20 FT</th>
        <th data-field="storage">40 FT</th>
        <th data-field="storage">Period</th>
        <th data-field="storage">20 FT</th>
        <th data-field="storage">40 FT</th>
        <th data-field="pti">failed</th>
        <th data-field="pti">pass</th>
    </tr>
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
                    @if ($id == 'table1' && (strpos($portCharge->name, 'FULL-IMPORT') !== false || strpos($portCharge->name, 'FULL-EXPORT') !== false))
                        <option value="{{ json_encode($portCharge) }}">{{ $portCharge->name }}</option>
                    @elseif($id == 'table2' && strpos($portCharge->name, 'EMPTY-EXPORT') !== false)
                        <option value="{{ json_encode($portCharge) }}">{{ $portCharge->name }}</option>
                    @elseif($id == 'table3' && strpos($portCharge->name, 'EMPTY-IMPORT') !== false)
                        <option value="{{ json_encode($portCharge) }}">{{ $portCharge->name }}</option>
                    @elseif($id == 'table4' && strpos($portCharge->name, 'TRANSHIP') !== false)
                        <option value="{{ json_encode($portCharge) }}">{{ $portCharge->name }}</option>
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
        <td data-field="thc"><input type="text" name="thc_20ft[]" class="form-control dynamic-input" data-field="thc_20ft"></td>
        <td data-field="thc"><input type="text" name="thc_40ft[]" class="form-control dynamic-input" data-field="thc_40ft"></td>
        <td data-field="storage"><input type="text" name="free_time[]" class="form-control dynamic-input" data-field="storage_free"></td>
        <td data-field="storage"><input type="text" name="slab1_period[]" class="form-control dynamic-input"
                   data-field="storage_slab1_period"></td>
        <td data-field="storage"><input type="text" name="slab1_20ft[]" class="form-control dynamic-input" data-field="storage_slab1_20ft">
        </td>
        <td data-field="storage"><input type="text" name="slab1_40ft[]" class="form-control dynamic-input" data-field="storage_slab1_40ft">
        </td>
        <td data-field="storage"><input type="text" name="slab2_period[]" class="form-control dynamic-input"
                   data-field="storage_slab2_period"></td>
        <td data-field="storage"><input type="text" name="slab2_20ft[]" class="form-control dynamic-input" data-field="storage_slab2_20ft">
        </td>
        <td data-field="storage"><input type="text" name="slab2_40ft[]" class="form-control dynamic-input" data-field="storage_slab2_40ft">
        </td>
        <td data-field="power"><input type="text" name="power_free[]" class="form-control dynamic-input" data-field="power_free"></td>
        <td data-field="power"><input type="text" name="power_20ft[]" class="form-control dynamic-input" data-field="power_20ft"></td>
        <td data-field="power"><input type="text" name="power_40ft[]" class="form-control dynamic-input" data-field="power_40ft"></td>
        <td data-field="shifting"><input type="text" name="shifting_20ft[]" class="form-control dynamic-input" data-field="shifting_20ft">
        </td>
        <td data-field="shifting"><input type="text" name="shifting_40ft[]" class="form-control dynamic-input" data-field="shifting_40ft">
        </td>
        <td data-field="disinf"><input type="text" name="disinf_20ft[]" class="form-control dynamic-input" data-field="disinf_20ft"></td>
        <td data-field="disinf"><input type="text" name="disinf_40ft[]" class="form-control dynamic-input" data-field="disinf_40ft"></td>
        <td data-field="hand-fes-em"><input type="text" name="hand_fes_em_20ft[]" class="form-control dynamic-input"
                   data-field="hand_fes_em_20ft"></td>
        <td data-field="hand-fes-em"><input type="text" name="hand_fes_em_40ft[]" class="form-control dynamic-input"
                   data-field="hand_fes_em_40ft"></td>
        <td data-field="gat-lift-off"><input type="text" name="gat_lift_off_inbnd_em_ft40_20ft[]" class="form-control dynamic-input"
                   data-field="gat_lift_off_inbnd_em_ft40_20ft"></td>
        <td data-field="gat-lift-off"><input type="text" name="gat_lift_off_inbnd_em_ft40_40ft[]" class="form-control dynamic-input"
                   data-field="gat_lift_off_inbnd_em_ft40_40ft"></td>
        <td data-field="gat-lift-on"><input type="text" name="gat_lift_on_inbnd_em_ft40_20ft[]" class="form-control dynamic-input"
                   data-field="gat_lift_on_inbnd_em_ft40_20ft"></td>
        <td data-field="gat-lift-on"><input type="text" name="gat_lift_on_inbnd_em_ft40_40ft[]" class="form-control dynamic-input"
                   data-field="gat_lift_on_inbnd_em_ft40_40ft"></td>
        <td data-field="pti"><input type="text" name="pti_20ft[]" class="form-control dynamic-input" data-field="pti_failed"></td>
        <td data-field="pti"><input type="text" name="pti_40ft[]" class="form-control dynamic-input" data-field="pti_passed"></td>
        <td data-field="add-plan"><input type="text" name="add_plan_20ft[]" class="form-control dynamic-input"
                   data-field="add_plan_20ft"></td>
        <td data-field="add-plan"><input type="text" name="add_plan_40ft[]" class="form-control dynamic-input"
                   data-field="add_plan_40ft"></td>

    </tbody>

</table>

@push('scripts')
    <script>

    </script>
@endpush