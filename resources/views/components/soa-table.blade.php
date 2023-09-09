@props([
    'id',
    'portCharges',
    'rows'
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
        <th colspan=2 data-field="storage">STORAGE</th>
        <th colspan=2 data-field="power">POWER</th>
        <th data-field="shifting">SHIFTING</th>
        <th data-field="disinf">DISINF</th>
        <th data-field="hand-fes-em">HAND-FES-EM</th>
        <th data-field="gat-lift-off-inbnd-em-ft40">GAT-LIFT OFF-INBND-EM-FT40</th>
        <th data-field="gat-lift-on-inbnd-em-ft40">GAT-LIFT ON-INBND-EM-FT40</th>
        <th colspan="2" data-field="pti">PTI</th>
        <th colspan="2" data-field="add-plan">ADD-PLAN</th>
    </tr>
    </thead>
    <tbody>
    @if(!isset($rows))
        <tr>
            <td style="width:85px;">
                <button type="button" class="btn btn-danger removeContact"><i
                            class="fa fa-trash"></i></button>
            </td>
            <td>
                <select name="rows[port_charge_type][]" class="form-control charge_type" required>
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
            <td><select name="rows[service][]" class="form-control service_type" required>
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
            <td><input type="text" class="form-control ref-no-td" name="rows[bl_no][]"></td>
            <td><input type="text" name="rows[container_no][]"
                       class="container_no form-control"></td>
            <td><input type="text" name="rows[is_transhipment][]"
                       class="is_transhipment form-control"></td>
            <td><input type="text" name="rows[shipment_type][]"
                       class="shipment_type form-control"></td>
            <td><input type="text" name="rows[quotation_type][]"
                       class="quotation_type form-control"></td>
            <td data-field="thc"><input type="text" name="rows[thc][]" class="form-control dynamic-input"
                                        data-field="thc_cost"></td>
            <td data-field="storage"><input type="text" name="rows[storage][]" class="form-control dynamic-input"
                                            data-field="storage_cost"></td>
            <td data-field="storage">
                <select style="min-width: 100px" class="form-control storage-days" name="rows[storage_days][]">
                    <option value="normal" data-cost="0" selected>Normal</option>
                    <option value="minus" data-cost="0">Minus One Day</option>
                </select>
            </td>
            <td data-field="power"><input type="text" name="rows[power][]" class="form-control dynamic-input"
                                          data-field="power_cost"></td>
            <td data-field="power">
                <select style="min-width: 100px" class="form-control power-days" name="rows[power_days][]">
                    <option value="normal" data-cost="0" selected>Normal</option>
                    <option value="minus" data-cost="0">Minus One Day</option>
                </select>
            </td>
            <td data-field="shifting"><input type="text" name="rows[shifting][]" class="form-control dynamic-input"
                                             data-field="shifting_cost">
            </td>
            <td data-field="disinf"><input type="text" name="rows[disinf][]" class="form-control dynamic-input"
                                           data-field="disinf_cost"></td>
            <td data-field="hand-fes-em"><input type="text" name="rows[hand_fes_em][]"
                                                class="form-control dynamic-input"
                                                data-field="hand_fes_em"></td>
            <td data-field="gat-lift-off-inbnd-em-ft40"><input type="text" name="rows[gat_lift_off_inbnd_em_ft40][]"
                                                               class="form-control dynamic-input"
                                                               data-field="gat_lift_off_inbnd_em_ft40"></td>
            <td data-field="gat-lift-on-inbnd-em-ft40"><input type="text" name="rows[gat_lift_on_inbnd_em_ft40][]"
                                                              class="form-control dynamic-input"
                                                              data-field="gat_lift_on_inbnd_em_ft40"></td>
            <td data-field="pti"><input type="text" name="rows[pti][]" class="form-control dynamic-input"
                                        data-field="pti_cost"></td>
            <td data-field="pti">
                <select style="min-width: 100px" class="form-control pti-type" name="rows[pti_type][]">
                    <option value="passed" data-cost="0" selected>Passed</option>
                    <option value="failed" data-cost="0">Failed</option>
                </select>
            </td>
            <td data-field="add-plan"><input type="text" name="rows[add_plan][]" class="form-control dynamic-input"
                                             data-field="add_plan_cost"></td>
            <td data-field="add-plan">
                <select style="min-width: 100px" class="form-control add-plan-select">
                    <option value="1" data-cost="0" selected>Added</option>
                    <option value="0" data-cost="0">Not added</option>
                </select>
            </td>
        </tr>
    @endif
    @foreach($rows ?? [] as $row)
        <tr>
            <td style="width:85px;">
                <button type="button" class="btn btn-danger removeContact"><i
                            class="fa fa-trash"></i></button>
            </td>
            <td>
                <select name="rows[port_charge_type][]" class="form-control charge_type new_charge" required>
                    <option hidden selected>Select</option>
                    @foreach($portCharges as $portCharge)
                        <option value="{{ $portCharge->id }}">{{ $portCharge->name }}</option>
                    @endforeach
                </select>
            </td>
            <td><select name="rows[service][]" class="form-control service_type" required>
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
            <td><input type="text" class="form-control ref-no-td" name="rows[bl_no][]" value="{{ $row->bl_no }}"></td>
            <td><input type="text" name="rows[container_no][]"
                       class="container_no form-control" value="{{ $row->container_no }}"></td>
            <td><input type="text" name="rows[is_transhipment][]"
                       class="is_transhipment form-control" value="{{ $row->is_transhipment }}"></td>
            <td><input type="text" name="rows[shipment_type][]"
                       class="shipment_type form-control" value="{{ $row->shipment_type }}"></td>
            <td><input type="text" name="rows[quotation_type][]"
                       class="quotation_type form-control" value="{{ $row->quotation_type }}"></td>
            <td data-field="thc"><input type="text" name="rows[thc][]" class="form-control dynamic-input"
                                        data-field="thc_cost" value="{{ $row->thc }}"></td>
            <td data-field="storage"><input type="text" name="rows[storage][]" class="form-control dynamic-input"
                                            data-field="storage_cost" value="{{ $row->storage }}"></td>
            <td data-field="power"><input type="text" name="rows[power][]" class="form-control dynamic-input"
                                          data-field="power_cost" value="{{ $row->power }}"></td>
            <td data-field="power">
                <select style="min-width: 100px" class="form-control power-days">
                    <option value="none" data-cost="0" selected>Normal</option>
                    <option value="plus" data-cost="0">Plus One Day</option>
                    <option value="minus" data-cost="0">Minus One Day</option>
                </select>
            </td>
            <td data-field="shifting"><input type="text" name="rows[shifting][]" class="form-control dynamic-input"
                                             data-field="shifting_cost" value="{{ $row->shifting }}">
            </td>
            <td data-field="disinf"><input type="text" name="rows[disinf][]" class="form-control dynamic-input"
                                           data-field="disinf_cost" value="{{ $row->disinf }}"></td>
            <td data-field="hand-fes-em"><input type="text" name="rows[hand_fes_em][]"
                                                class="form-control dynamic-input"
                                                data-field="hand_fes_em" value="{{ $row->bl_no }}"></td>
            <td data-field="gat-lift-off-inbnd-em-ft40"><input type="text" name="rows[gat_lift_off_inbnd_em_ft40][]"
                                                               class="form-control dynamic-input"
                                                               data-field="gat_lift_off_inbnd_em_ft40"
                                                               value="{{ $row->gat_lift_off_inbnd_em_ft40 }}"></td>
            <td data-field="gat-lift-on-inbnd-em-ft40"><input type="text" name="rows[gat_lift_on_inbnd_em_ft40][]"
                                                              class="form-control dynamic-input"
                                                              data-field="gat_lift_on_inbnd_em_ft40"
                                                              value="{{ $row->gat_lift_on_inbnd_em_ft40 }}"></td>
            <td data-field="pti"><input type="text" name="rows[pti][]" class="form-control dynamic-input"
                                        data-field="pti_cost" value="{{ $row->pti }}"></td>
            <td data-field="pti">
                <select style="min-width: 100px" class="form-control pti-type" name="rows[pti_type][]">
                    <option value="passed" data-cost="0" selected>Passed</option>
                    <option value="failed" data-cost="0">Failed</option>
                </select>
            </td>
            <td data-field="add-plan"><input type="text" name="rows[add_plan][]" class="form-control dynamic-input"
                                             data-field="add_plan_cost" value="{{ $row->add_plan }}"></td>
            <td data-field="add-plan">
                <select style="min-width: 100px" class="form-control add-plan-select">
                    <option value="1" data-cost="0" selected>Added</option>
                    <option value="0" data-cost="0">Not added</option>
                </select>
            </td>
        </tr>
    @endforeach
    </tbody>

</table>
