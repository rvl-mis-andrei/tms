'use strict';
// import {ClientListDT} from '../dt_controller/serverside/0004_0.js';
import {Alert} from "../../../global/alert.js"
import {RequestHandler,} from "../../../global/request.js"
import {dealer,car_model} from "../../../global/select.js"
import {data_bs_components,modal_state,page_state,custom_upload,createBlockUI} from "../../../global.js";
import {fvHaulingPlanInfo} from '../fv_controller/0002_1.js';



export function HaulingPlanInfoController(page,param){
    var block_number = 0;
    const hauling_list = $('.hauling_list');
    const empty_hauling_list = $('.empty_hauling_list');
    const _page = $('.haulage_info_page');
    const HaulagePage = createBlockUI('#Page', 'Loading...');
    const HaulingCard = createBlockUI('.hauling_list_card', 'Loading...');
    const AllocationCard = createBlockUI('.for_allocation_card', 'Loading...');
    const ExportTripBlock = createBlockUI('#modal_export_hauling_plan .modal-content', 'Loading...');
    let _tagifyInstance;

    function loadTripBlock(batch=1)
    {
        let formData = new FormData();
        formData.append('id',param)
        formData.append('batch',batch)
        return new Promise((resolve, reject) => {
            (new RequestHandler).post('/tms/cco-b/planner/haulage_info/tripblock',formData).then((res) => {
                if(res.status == 'success'){
                    let payload = JSON.parse(window.atob(res.payload));
                    let html = '',tbody='', isAllBlockFinal = false,tripBlockCount=0,trpBlockUnit=0;
                    hauling_list.empty();
                    $('.finalize-notif').empty().addClass('d-none');
                    if(payload.length >0){
                        payload.forEach(function(item,key) {
                            tbody = '';
                            item.block_units.forEach(function(units) {
                                tbody+=`<tr class=" ${/priority/i.test(units.remarks) ? 'text-danger' : 'text-muted'}" data-original-table="tbl_${units.dealer_code}_${units.hub}" data-type="forAllocation" data-id="${units.encrypted_id}">
                                        <td class="  remove-cell">${units.dealer_code}</td>
                                        <td class="">${units.cs_no}</td>
                                        <td class="">${units.model}</td>
                                        <td class="">${units.color_description}</td>
                                        <td class="">${units.invoice_date}</td>
                                        <td class="">${units.updated_location}</td>
                                        <td class="remove-cell">${units.inspection_start}</td>
                                        <td class="remove-cell">${units.hub}</td>
                                        <td class="text-center exclude-filter remove-cell">
                                            <div class="form-check form-check-custom form-check-solid form-check-sm" style="display:block;">
                                                <input class="form-check-input cursor-pointer transfer-checkbox" type="checkbox" value="" data-id="${units.encrypted_id}"
                                                rq-url="/tms/cco-b/planner/haulage_info/update_transfer" ${units.is_transfer ==1 ? 'checked':''} />
                                            </div>
                                        </td>
                                        <td class="remove-cell exclude-filter pe-1">
                                            <input type="text" class=" ${/priority/i.test(units.remarks) ? 'text-danger' : 'text-muted'} form-control form-control-sm form-control-solid"
                                            name="unit_remarks" value="${units.remarks}" data-id="${units.encrypted_id}"
                                            rq-url="/tms/cco-b/planner/haulage_info/update_unit_remarks">
                                        </td>
                                    </tr>`;
                                    trpBlockUnit++;
                            });
                            html = `
                            <div class="card mb-5">
                                    <div class="card-header collapsible">
                                        <span class="card-title pt-3">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex align-items-center mb-1">
                                                    <a href="javascript:;" class="text-gray-900 text-hover-primary fs-5 fw-bold me-1">
                                                        ${ item.dealer_code ?? 'Trip Block #'+(key+1) }
                                                    </a>
                                                </div>
                                                <div class="d-flex flex-wrap fw-semibold fs-6 pe-2">
                                                    <a href="javascript:;" class="d-flex align-items-center text-gray-500 text-hover-primary me-4 mb-2">
                                                        Units : ${item.units_count}
                                                    </a>
                                                    ${item.is_multipickup ?`
                                                        <a href="javascript:;" class="d-flex align-items-center text-gray-500 text-hover-primary me-4 mb-2">
                                                           Multi-Pickup
                                                        </a>`:``
                                                    }
                                                    ${batch == 'All Batch' ?`
                                                        <a href="javascript:;" class="d-flex align-items-center text-gray-500 text-hover-primary mb-2">
                                                            Batch : ${item.batch}
                                                        </a>`:``
                                                    }
                                                </div>
                                            </div>
                                        </span>
                                        <div class="card-toolbar">
                                            ${
                                                item.status != 2
                                                    ? `
                                            <div class="me-0">
                                                <button class="btn btn-sm btn-icon btn-active-color-primary"
                                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                    <i class="ki-solid ki-dots-horizontal fs-2x"></i>
                                                </button>
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                                                    data-kt-menu="true">
                                                    <div class="menu-item px-3">
                                                        <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                                            More Actions
                                                        </div>
                                                    </div>
                                                    <div class="separator my-2 opacity-75"></div>
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3 text-danger remove-block" data-id="${item.encrypted_id}">
                                                            Remove Block
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            `
                                                    : ""
                                            }
                                            <div class="rotate btn btn-icon btn-sm btn-active-color-info" data-kt-rotate="true" data-bs-toggle="collapse" data-bs-target="#trip_block_${
                                                item.block_number
                                            }">
                                                <i class="ki-duotone ki-down fs-1  rotate-n180"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="trip_block_${
                                        item.block_number
                                    }" class="collapse show">
                                        <div class="card-body ">
                                            <div class="table-responsive">
                                                <table class="table table-row-bordered align-middle table-sm gy-3" id="tbl_block_${item.block_number}"
                                                data-type="planning" data-id="${item.encrypted_id}">
                                                    <thead class="">
                                                        <tr class=" fw-bold fs-8 text-uppercase gs-0">
                                                            <th>Dealer</th>
                                                            <th>Cs No.</th>
                                                            <th>Model</th>
                                                            <th>Color</th>
                                                            <th>Invoice Date</th>
                                                            <th>Location</th>
                                                            <th>Inspection TIme</th>
                                                            <th>Hub</th>
                                                            <th class="text-center">Transfer ?</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="fs-8 fw-semibold text-gray-600" data-type="planning" data-id="${
                                                        item.encrypted_id
                                                    }">
                                                        ${tbody}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            hauling_list.append(html);
                            tripBlockCount++;
                            block_number = tripBlockCount;
                            if(item.status !=2){
                                initSortableTribBlock(`tbl_block_${item.block_number}`)
                                isAllBlockFinal=false;
                            }else{
                                isAllBlockFinal=true;
                            }
                        })
                        if(isAllBlockFinal){
                            $('.finalize-plan').addClass('d-none');
                            $('.add-block').addClass('d-none');
                            $('.more-actions').addClass('d-none');
                            if($('.complete-haulage').length <= 0){
                                $('.complete-haulage').remove();
                                $('.finalize-notif').html(
                                    `<div class="alert alert-dismissible bg-light-primary d-flex flex-column flex-sm-row p-5 mb-5 complete-haulage">
                                        <i class="ki-duotone ki-notification-bing fs-2hx text-primary me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        <div class="d-flex flex-column pe-0 pe-sm-10">
                                            <h4 class="fw-semibold">Batch ${batch} Finalize</h4>
                                            <span>This is to notify you that the hauling plan batch ${batch} is finalize.</span>
                                        </div>

                                        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                            <i class="ki-duotone ki-cross fs-1 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                        </button>
                                    </div>`
                                ).removeClass('d-none');
                            }
                        }else{
                            console.log(batch)
                            if(batch == 'All Batch'){
                                $('.add-block').addClass('d-none');
                            }else{
                                $('.add-block').removeClass('d-none');
                            }
                            $('.finalize-plan').removeClass('d-none');
                            $('.more-actions').removeClass('d-none');
                        }
                        hauling_list.removeClass('d-none')
                        empty_hauling_list.addClass('d-none')
                    }else{
                        hauling_list.addClass('d-none')
                        empty_hauling_list.removeClass('d-none')
                        $('.finalize-plan').removeClass('d-none');
                        if(batch == 'All Batch'){
                            $('.add-block').addClass('d-none');
                        }else{
                            $('.add-block').removeClass('d-none');
                        }
                        $('.more-actions').removeClass('d-none');
                    }
                    $('.haulage_info_page').removeClass('d-none')
                    $('.unit-count').text(trpBlockUnit)
                    $('.trip-count').text(tripBlockCount)
                    KTComponents.init()
                    data_bs_components()
                    resolve(true);
                }
            })
            .catch((error) => {
                console.log(error)
                resolve(false);
                Alert.alert('error',"Something went wrong. Try again later", false);
            })
            .finally(() => {
            });
        })
    }

    function loadForAllocation(hub,search=''){
        let formData = new FormData();
        formData.append('id',param)
        formData.append('hub',hub)
        formData.append('search',search)
        return new Promise((resolve, reject) => {
            (new RequestHandler).post('/tms/cco-b/planner/haulage_info/for_allocation',formData).then((res) => {
                if(res.status == 'success'){
                    let payload = JSON.parse(window.atob(res.payload));
                    let data = payload.data;
                    let status = payload.status;
                    let html = '',accordion=0, isFinal=false;
                    if(Object.keys(data).length){
                        $(`.${hub}_content`).empty();
                        $(`.for_allocation`).empty();
                        Object.keys(data).forEach(function(key) {
                            let tbody = '';
                            accordion++;
                            data[key].unit.forEach(function(item) {
                                if(item.encrypted_id){
                                    tbody+=`<tr data-id="${item.encrypted_id}" class="${/priority/i.test(item.remarks) ? 'text-danger' : 'text-muted'}">
                                        <td>${item.cs_no}</td>
                                        <td>${item.model}</td>
                                        <td>${item.color_description}</td>
                                        <td>${item.invoice_date}</td>
                                        <td>${item.updated_location}</td>
                                        ${status ==2 ? `<td class="remove-cell">
                                           <a href="javascript:;" class="btn btn-icon btn-color-gray-500 btn-active-color-danger justify-content-end remove-unit"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Remove unit" data-id="${item.encrypted_id}" rq-url="/tms/cco-b/planner/haulage_info/remove_unit">
					                            <i class="ki-duotone ki-trash-square fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                </i>
                                            </a>
                                        </td>`:''}
                                    </tr>`;
                                }
                            })
                            html=`<div class="accordion mb-2" id="kt_accordion_${accordion}">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="accordion_${key}">
                                            <button class="accordion-button fs-4 fw-semibold collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#kt_accordion_${accordion}_body_${accordion}"
                                                aria-expanded="true" aria-controls="kt_accordion_${accordion}_body_${accordion}">
                                                ${key}
                                                <span class="badge badge-light-success ms-2">Allocated : <span class="allocated_${accordion}">${data[key].allocated}</span></span>
                                                <span class="badge badge-light-primary ms-2">Unallocated : <span class="unallocated_${accordion}">${data[key].unallocated}</span></span>
                                            </button>
                                        </h2>
                                        <div id="kt_accordion_${accordion}_body_${accordion}" class="accordion-collapse collapse pb-5"
                                            aria-labelledby="accordion_${key}" data-bs-parent="#kt_accordion_${accordion}">
                                            <div class="accordion-body">
                                                <div class="table-responsive">
                                                    <table class="table table-row-bordered align-middle gy-3 table-sm" id="tbl_${key}_${data[key].hub}" data-type="forAllocation">
                                                        <thead class="">
                                                            <tr class=" fw-bold fs-8 text-uppercase gs-0">
                                                                <th class="">Cs No.</th>
                                                                <th class="">Model</th>
                                                                <th class="">Color</th>
                                                                <th class="">Invoice</th>
                                                                <th class="">Location</th>
                                                                ${status ==2 ? `<th class="">Action</th>`:''}
                                                            </tr>
                                                        </thead>
                                                        <tbody class="fs-8 fw-semibold text-gray-600 cursor-pointer" data-type="forAllocation" data-allocated="allocated_${accordion}"" data-unallocated="unallocated_${accordion}"">
                                                            ${tbody}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                            $(`.${hub}_content`).append(html);
                            initSortableAllocation(`tbl_${key}_${data[key].hub}`)
                        })
                        $(`.${hub}_content`).removeClass('d-none');
                        $(`.empty_${hub}`).addClass('d-none');
                        $(`.${hub}-count`).text('('+Object.keys(data).length+')').removeClass('d-none');
                    }else{
                        $(`.${hub}_content`).addClass('d-none').empty();
                        $(`.empty_${hub}`).removeClass('d-none');
                    }

                    KTComponents.init()
                    data_bs_components()
                    resolve(true);
                }
            })
            .catch((error) => {
                console.log(error)
                resolve(false);
                Alert.alert('error',"Something went wrong. Try again later", false);
            })
            .finally(() => {
                // code here
            });
        })
    }

    function addTripBlock(){
        let formData = new FormData();
        let batch = $('select[name="batch"]').val();
        block_number++;
        formData.append('id',param)
        formData.append('batch',batch)
        formData.append('block_number',block_number)

        return new Promise((resolve, reject) => {

            if(batch == 'All Batch'){
                Alert.alert('error',"You can't add block for all batch", false);
                resolve(false)
            }

            (new RequestHandler).post('/tms/cco-b/planner/haulage_info/add_tripblock',formData).then((res) => {
                if(res.status == 'success'){
                    let payload = JSON.parse(window.atob(res.payload));
                    if(payload.length >0){
                        payload.forEach(function(item) {
                            let html =`<div class="card mb-10" data-block="${item.block_number}">
                                    <div class="card-header collapsible">
                                        <span class="card-title pt-3">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex align-items-center mb-1">
                                                    <a href="javascript:;" class="text-gray-900 text-hover-primary fs-5 fw-bold me-1">
                                                        Trip Block # ${ item.block_number}
                                                    </a>
                                                </div>

                                                <div class="d-flex flex-wrap fw-semibold fs-6 pe-2">
                                                    <a href="javascript:;" class="d-flex align-items-center text-gray-500 text-hover-primary me-4 mb-2">
                                                        Units : 0
                                                    </a>
                                                </div>
                                            </div>
                                        </span>
                                        <div class="card-toolbar">
                                            <div class="me-0">
                                                <button class="btn btn-sm btn-icon btn-active-color-primary"
                                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                    <i class="ki-solid ki-dots-horizontal fs-2x"></i>
                                                </button>
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                                                    data-kt-menu="true">
                                                    <div class="menu-item px-3">
                                                        <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                                            More Actions
                                                        </div>
                                                    </div>
                                                    <div class="separator my-2 opacity-75"></div>
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3 text-danger remove-block" data-id="${item.encrypted_id}">
                                                            Delete Block
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="rotate btn btn-icon btn-sm btn-active-color-info" data-kt-rotate="true" data-bs-toggle="collapse" data-bs-target="#trip_block_${item.block_number}">
                                                <i class="ki-duotone ki-down fs-1  rotate-n180"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="trip_block_${item.block_number}" class="collapse show">
                                        <div class="card-body pt-0">
                                            <div class="table-responsive">
                                                <table class="table align-middle fs-6 gy-3 table-sm" id="tbl_block_${item.block_number}" data-type="planning">
                                                    <thead class="">
                                                        <tr class=" fw-bold fs-8 text-uppercase gs-0">
                                                            <th class="">Dealer</th>
                                                            <th class="">Cs No.</th>
                                                            <th class="">Model</th>
                                                            <th class="">Color</th>
                                                            <th class="">Invoice Date</th>
                                                            <th class="">Location</th>
                                                            <th class="">Inspection TIme</th>
                                                            <th class="">Hub</th>
                                                            <th class="text-center">Transfer ?</th>
                                                            <th class="">Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="fs-8 fw-semibold text-gray-600" data-type="planning" data-id="${item.encrypted_id}">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            hauling_list.last().append(html).removeClass('d-none');
                            initSortableTribBlock(`tbl_block_${item.block_number}`)
                            empty_hauling_list.addClass('d-none');
                            resolve(true)
                        })
                    }
                }
            })
            .catch((error) => {
                console.log(error)
                resolve(false);
                Alert.alert('error',"Something went wrong. Try again later", false);
            })
            .finally(() => {
                KTComponents.init()
                data_bs_components()
            });
        })



    }

    function removeTripBlock(_this){

        let formData = new FormData();
        let batch = $('select[name="batch"]').val();
        formData.append('id',param)
        formData.append('batch',batch)
        formData.append('block_id',_this.attr('data-id'))

        return new Promise((resolve, reject) => {
            (new RequestHandler).post('/tms/cco-b/planner/haulage_info/remove_tripblock',formData).then((res) => {
                if(res.status == 'success'){
                    block_number--;
                    $(_this).closest('.card').remove();
                    if(block_number ==0){
                        loadTripBlock(batch)
                    }
                    resolve(true)
                }
            })
            .catch((error) => {
                console.log(error)
                resolve(false);
                Alert.alert('error',"Something went wrong. Try again later", false);
            })
            .finally(() => {
                KTComponents.init()
                data_bs_components()
            });
        })
    }

    function initSortableAllocation(id)
    {
        new Sortable(document.getElementById(id).getElementsByTagName('tbody')[0], {
            group: {
                name: 'forAllocation',
                pull: true,
                put: ['planning']
            },
            animation: 150,
            multiDrag: true,
            selectedClass: 'selected',
            onEnd: function(evt) {
                let selectedItems = evt.items.length > 0 ? evt.items : [evt.item];
                let dragToNewTable = evt.to.closest('table');
                let oldTable = evt.from.closest('table');

                let allocated_badge = oldTable.querySelector('tbody').getAttribute('data-allocated');
                let unallocated_badge = oldTable.querySelector('tbody').getAttribute('data-unallocated');
                let allocated = 0;
                let unallocated = 0;
                let formDataArray = [];
                selectedItems.forEach(function(item) {
                    let originalTableId = item.getAttribute('data-original-table');
                    let currentTableId = evt.to.closest('table').id;

                    let cells = item.getElementsByClassName('remove-cell');
                    if (evt.to.getAttribute('data-type') === 'planning' && item.getAttribute('data-type') === 'forAllocation') {
                        let itemData = {
                            haulage_id: param,
                            block_id: evt.to.getAttribute('data-id'),
                            unit_id: item.getAttribute('data-id'),
                            batch: $('select[name="batch"]').val(),
                            status: 1,
                            unit_order: Array.from(dragToNewTable.querySelectorAll('tbody tr')).indexOf(item) + 1
                        };
                        formDataArray.push(itemData);
                        while (cells.length > 0) {
                            cells[0].parentNode.removeChild(cells[0]);
                        }
                        allocated++;
                    }
                });

                if (formDataArray.length > 0) {
                    let formData = new FormData();
                    formData.append('units', JSON.stringify(formDataArray));
                    formData.append('haulage_id', param);
                    (new RequestHandler).post('/tms/cco-b/planner/haulage_info/update_block_units', formData).then((res) => {
                        if (res.status === 'success') {
                            let payloadArray = JSON.parse(window.atob(res.payload));
                            payloadArray.forEach((payload, index) => {
                                let item = selectedItems[index];

                                // Add new cells with the received data
                                let dealerCodeCell = document.createElement('td');
                                dealerCodeCell.classList.add('remove-cell');
                                dealerCodeCell.textContent = payload.dealer_code;
                                item.insertBefore(dealerCodeCell, item.firstChild);

                                let inspectionTimeCell = document.createElement('td');
                                inspectionTimeCell.classList.add('remove-cell');
                                inspectionTimeCell.textContent = payload.inspection_time;
                                item.appendChild(inspectionTimeCell);

                                let hubCell = document.createElement('td');
                                hubCell.classList.add('remove-cell');
                                hubCell.textContent = payload.hub;
                                item.appendChild(hubCell);

                                let is_transfer = document.createElement('td');
                                is_transfer.className = 'text-center exclude-filter remove-cell';
                                is_transfer.innerHTML = `
                                    <div class="form-check form-check-custom form-check-solid form-check-sm" style="display:block;">
                                        <input class="form-check-input cursor-pointer transfer-checkbox" type="checkbox"
                                            data-id="${payload.encrypted_id}"
                                            rq-url="/tms/cco-b/planner/haulage_info/update_transfer"
                                            ${payload.is_transfer == 1 ? 'checked' : ''} />
                                    </div>`;

                                // Append the <td> to the row (item)
                                item.appendChild(is_transfer);

                                let remarksCell = document.createElement('td');
                                remarksCell.className = 'text-center exclude-filter remove-cell px-1';
                                remarksCell.innerHTML = `<input type="text" class=" ${/priority/i.test(payload.remarks) ? 'text-danger' : 'text-muted'} form-control form-control-sm form-control-solid "
                                            name="unit_remarks" value="${payload.remarks}" data-id="${payload.encrypted_id}"
                                            rq-url="/tms/cco-b/planner/haulage_info/update_unit_remarks">`;
                                item.appendChild(remarksCell);

                            });
                        } else {
                            Alert.alert('error', res.message);
                        }
                    }).catch((error) => {
                        console.error(error);
                        Alert.alert('error', "Something went wrong. Try again later", false);
                    });
                    unallocated = (parseInt($(`.${unallocated_badge}`).text())) - allocated;
                    allocated = (parseInt($(`.${allocated_badge}`).text())) + allocated;
                    $(`.${allocated_badge}`).empty().text(allocated);
                    $(`.${unallocated_badge}`).empty().text(unallocated);
                }
            },
            onStart: function(evt) {
                evt.item.setAttribute('data-original-table', evt.from.closest('table').id);
                evt.item.setAttribute('data-type','forAllocation');
            },
            onSelect: function(evt) {
                evt.item.setAttribute('data-original-table', evt.from.closest('table').id);
                evt.item.setAttribute('data-type','forAllocation');
            },
            onDeselect: function(evt) {
                evt.item
            },
        });
    }

    function initSortableTribBlock(id) {
        new Sortable(document.getElementById(id).getElementsByTagName('tbody')[0], {
            group: {
                name: 'planning',
                pull: true,
                put: ['planning', 'forAllocation'],
            },
            swap: true,
            swapClass: 'highlight',
            filter: '.exclude-filter, input, .form-check-input', // Add input elements to filter
            preventOnFilter: false, // Allow interaction with the filtered elements
            animation: 150,
            multiDrag: true,
            selectedClass: 'selected',
            onEnd: function(evt) {
                let selectedItems = evt.items.length > 0 ? evt.items : [evt.item];
                let currentTable = evt.to.closest('table');
                let formData = new FormData();
                let selectedItemsData = [];

                let allocated_badge = currentTable.querySelector('tbody').getAttribute('data-allocated');
                let unallocated_badge = currentTable.querySelector('tbody').getAttribute('data-unallocated');
                let allocated = 0;
                let unallocated = 0;

                selectedItems.forEach(function(item) {
                    let originalTableId = item.getAttribute('data-original-table');
                    let currentTableId = evt.to.closest('table').id;
                    let tableToType = evt.to.getAttribute('data-type');
                    let cells = item.getElementsByClassName('remove-cell');
                    let itemData = {
                        haulage_id: param,
                        block_id: tableToType === 'forAllocation' ? '' : evt.to.getAttribute('data-id'),
                        unit_id: item.getAttribute('data-id'),
                        batch: $('select[name="batch"]').val(),
                        status: tableToType === 'forAllocation' ? 0 : 1,
                        unit_order: Array.from(currentTable.querySelectorAll('tbody tr')).indexOf(item) + 1
                    };
                    let ispushData = true;
                    if (item.getAttribute('data-type') === 'forAllocation' && tableToType === "forAllocation") {
                        let originalTable = document.getElementById(originalTableId);
                        if(originalTable){
                            while (cells.length > 0) {
                                cells[0].parentNode.removeChild(cells[0]);
                            }
                        }
                        let inspectionTimeCell = document.createElement('td');
                        inspectionTimeCell.classList.add('remove-cell');
                        inspectionTimeCell.innerHTML =`
                            <td class="remove-cell">
                                <a href="javascript:;" class="btn btn-icon btn-color-gray-500 btn-active-color-danger justify-content-end remove-unit"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Remove unit" data-id="${item.getAttribute('data-id')}" rq-url="/tms/cco-b/planner/haulage_info/remove_unit">
                                    <i class="ki-duotone ki-trash-square fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </a>
                            </td>`;
                        item.appendChild(inspectionTimeCell);

                        if (currentTableId !== originalTableId && originalTable) {
                            originalTable.getElementsByTagName("tbody")[0].appendChild(item);
                        } else if (!originalTable) {
                            evt.from.appendChild(item);
                            ispushData = false;
                        }
                        unallocated++;
                    }
                    if(ispushData){ selectedItemsData.push(itemData) }
                });

                if (selectedItemsData.length > 0) {
                    formData.append('units', JSON.stringify(selectedItemsData));
                    formData.append('haulage_id', param);
                    (new RequestHandler).post('/tms/cco-b/planner/haulage_info/update_block_units', formData).then((res) => {
                        if (res.status == 'success') {
                        } else {
                            Alert.alert('error', res.message);
                        }
                    }).catch((error) => {
                        console.log(error);
                        Alert.alert('error', "Something went wrong. Try again later", false);
                    }).finally(() => {
                    });

                    if(unallocated > 0){
                        allocated = (parseInt($(`.${allocated_badge}`).text())) - unallocated;
                        unallocated = (parseInt($(`.${unallocated_badge}`).text())) + unallocated;
                        $(`.${allocated_badge}`).empty().text(allocated);
                        $(`.${unallocated_badge}`).empty().text(unallocated);
                    }

                }
            },
            onStart: function(evt) {
                //code here
            },
        });
    }

    function exportTripBlockList(batch='Show All',search='',filter='Show All'){

        let content = $('.export_tripblock_content');
        let empty_content = $('.export_tripblock_empty');
        let tbody = $('.tripblock_list');

        let formData = new FormData();
        formData.append('id',param)
        formData.append('batch',batch)
        formData.append('search',search)
        formData.append('filter',filter)

        return new Promise((resolve, reject) => {
            (new RequestHandler).post('/tms/cco-b/planner/haulage_info/tripblock_list',formData).then((res) => {
                if(res.status == 'success'){
                    let html='';
                    let payload = JSON.parse(window.atob(res.payload));
                    if(payload.length > 0){
                        console.log(payload);
                        payload.forEach((item,index)=>{
                            html += `
                                <tr>
                                    <td class="">
                                        <div class="position-relative">
                                            <span
                                                class="text-dark text-hover-primary fw-bold">
                                                ${item.name}
                                            </span>
                                            <div class="fs-7 text-muted">
                                                Units : ${item.unit_count} ${item.is_multipickup ?  '| Multi-PickUp' : ''}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="">
                                       Batch ${item.block_batch}
                                    </td>
                                    <td class="">
                                        <div class="position-relative">
                                            <span class=" text-hover-primary">
                                                ${item.export_date}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-custom form-check-solid form-check-sm">
                                            <input class="form-check-input cursor-pointer" type="checkbox" value="${item.encrypt_id}">
                                        </div>
                                    </td>
                                </tr>
                            `
                        });
                        tbody.empty().append(html);
                        empty_content.empty();
                        content.removeClass('d-none');
                        resolve(true)
                    }else{
                        content.addClass('d-none');
                        tbody.empty();
                        empty_content.empty().append(
                            `<div class="card-px text-center pt-10 pb-5">
                            <h2 class="fs-1x mb-0">No Trip Block Found</h2>
                            <p class="text-gray-500 fs-5 fw-semibold py-7">
                                Search other trip block
                            </p>
                        </div>
                        <div class="text-center pb-15 px-5">
                            <img src="${asset_url+'/media/illustrations/sketchy-1/16.png'}" alt="" class="mw-100 h-200px h-sm-325px">
                        </div>`
                        );
                        resolve(true)

                    }

                }
            })
            .catch((error) => {
                console.log(error)
                resolve(false);
                Alert.alert('error',"Something went wrong. Try again later", false);
            })
            .finally(() => {
                KTComponents.init()
                data_bs_components()
            });
        });
    }


    $(document).ready(function(e){

        HaulagePage.block();
        let sess_batch =localStorage.getItem("sess_batch") || 1;

        loadTripBlock(sess_batch).then(()=>{
            if(sess_batch == 'All Batch'){
                $('.add-block').addClass('d-none');
            }else{
                $('.add-block').removeClass('d-none');
            }
            fvHaulingPlanInfo(param)
            loadForAllocation('svc')
            custom_upload()
            dealer('','#modal_add_dealer_unit')
            car_model('','#modal_add_dealer_unit')
            $('select[name="batch"]').val(sess_batch).select2();
            setTimeout(function() {
                HaulagePage.release();
            }, 200);
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
        })

        _page.on('click','.nav-tab',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()
            let tab = $(this);
            let hub = tab.attr('data-hub');

            AllocationCard.block();
            let search = $('input[name="search"]').val();
            loadForAllocation(hub,search).then((res) =>{
                setTimeout(function() {
                    AllocationCard.release();
                }, 200);
            })

        })

        _page.on('change','select[name="batch"]',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            let _this = $(this);
            let url = _this.attr('rq-url');
            let batch = _this.val();

            if(batch == 'Add Batch'){
                let formData = new FormData();
                formData.append('id',param);
                formData.append('batch_count',batch);
                (new RequestHandler).post(url,formData).then((res) => {
                    if(res.status == 'success'){
                        let newBatchOption = `<option value="${res.payload}">Batch ${res.payload}</option>`;
                        // Find the "Add Batch" option
                        let addBatchOption = _this.find('option[value="Add Batch"]');
                        // Insert new batch option before "Add Batch"
                        $(newBatchOption).insertBefore(addBatchOption);
                        _this.select2();
                        _this.val(res.payload).trigger('change');
                        Alert.toast('success',res.message);
                    }else{
                        Alert.toast('error',res.message);
                    }
                }).catch((error) => {
                    console.log(error)
                    Alert.alert('error',"Something went wrong. Try again later", false);
                }).finally(() => {
                    initTagify();
                });
            }else{
                HaulingCard.block();
                loadTripBlock(batch).then((res) =>{
                    setTimeout(function() {
                        HaulingCard.release();
                    }, 200);
                    localStorage.setItem("sess_batch",$(this).val())
                })
            }
        })

        _page.on('click','.add-block',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            HaulingCard.block();

            addTripBlock().then((res) =>{
                if(res){
                    Alert.toast('success','Trip block added')
                }
                setTimeout(function() {
                    HaulingCard.release();
                }, 300);
            })
        })

        _page.on('click','.remove-block',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            Alert.confirm('question',"Remove this block ?",{
                onConfirm: () => {
                    HaulingCard.block();
                     removeTripBlock($(this)).then((res) =>{
                        if(res){
                            Alert.toast('success','Trip block removed')
                        }
                        setTimeout(function() {
                            HaulingCard.release();
                        }, 200);
                    })
                }
            })
        })

        _page.on('click','.finalize-plan',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()
            let rq_url = $(this).attr('rq-url');
            let batch = $('select[name="batch"]').val();
            var hasRows = false;
            $('.hauling_list .card').each(function() {
                var $table = $(this).find('table');
                if ($table.find('tbody tr').length > 0) {
                    hasRows = true;
                    return false;
                }
            });
            if (!hasRows) {
                Alert.alert('info','No trip blocks to finalize');
                return;
            }
            Alert.confirm(`question`,`Finalize Hauling Plan Batch ${batch}?`, {
                onConfirm: function() {
                    HaulingCard.block();
                    let formData = new FormData();
                    formData.append('id',param);
                    formData.append('batch',batch);
                    (new RequestHandler).post(rq_url,formData).then((res) => {
                        Alert.toast(res.status,res.message);
                        if(res.status == 'success'){
                            Alert.loading("Please wait while page is refreshing . . .",{
                                didOpen:function(){
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 300);
                                }
                            });
                        }
                        setTimeout(function() {
                            HaulingCard.release();
                        }, 300);
                    })
                    .catch((error) => {
                        console.log(error)
                        Alert.alert('error',"Something went wrong. Try again later", false);
                    })
                    .finally(() => {
                        //code here
                    });
                },
                onCancel: () => {
                    //code here
                }
            });
        })

        _page.on('click','.reupload',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            let modal_id = $(this).attr('modal-id');
            let modal_title = $(this).attr('modal-title');
            let rq_url = $(this).attr('rq-url');
            let form_id = $(this).attr('form_id');

            $(`#${form_id}`).attr('action',rq_url);
            $('.modal_title').text(modal_title);

            modal_state(modal_id,'show');
        })

        _page.on('click','.remove-unit',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            let _this = $(this);
            let unit_id = _this.attr('data-id');
            let url = _this.attr('rq-url');
            Alert.confirm(`question`,`Remove this unit?`, {
                onConfirm: function() {
                    AllocationCard.block();
                    let formData = new FormData();
                    formData.append('id',param);
                    formData.append('unit_id',unit_id);
                    (new RequestHandler).post(url,formData).then((res) => {
                        Alert.toast(res.status,res.message);
                        if(res.status == 'success'){
                            _this.closest('tr').remove();
                        }
                        setTimeout(function() {
                            AllocationCard.release();
                        }, 300);
                    })
                    .catch((error) => {
                        console.log(error)
                        Alert.alert('error',"Something went wrong. Try again later", false);
                    })
                    .finally(() => {
                    });
                },
            });
        })

        _page.on('change','.transfer-checkbox',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            let _this = $(this);
            let unit_id = _this.attr('data-id');
            let url = _this.attr('rq-url');
            let checked = _this.is(':checked') ?1:0;

            let formData = new FormData();

            formData.append('id',param);
            formData.append('unit_id',unit_id);
            formData.append('transfer',checked);

            (new RequestHandler).post(url,formData).then((res) => {
                Alert.toast(res.status,res.message);
            })
            .catch((error) => {
                console.log(error)
                Alert.alert('error',"Something went wrong. Try again later", false);
            })
            .finally(() => {
            });
        })

        _page.on('keyup','input[name="search"]',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            let hub = $('.nav-tab.active').attr('data-hub');
            let searchTerm = $(this).val();
            if (e.key === 'Enter' || e.keyCode === 13) {
                AllocationCard.block();
                loadForAllocation(hub,searchTerm).then(() => {
                    setTimeout(function() {
                        AllocationCard.release();
                    },200);
                })
            } else if (e.keyCode === 8 || e.key === 'Backspace') {
                setTimeout(() => {
                    let updatedSearchTerm = $(this).val();
                    if (updatedSearchTerm === '') {
                        AllocationCard.block();
                        loadForAllocation(hub,updatedSearchTerm)
                        setTimeout(function() {
                            AllocationCard.release();
                        },200);
                    }
                }, 0);
            }
        })

        _page.on('keyup','input[name="unit_remarks"]',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            if (e.key === 'Enter' || e.keyCode === 13) {
                let _this = $(this);
                let unit_id = _this.attr('data-id');
                let url = _this.attr('rq-url');
                let remarks = _this.val();

                let formData = new FormData();
                formData.append('id',param);
                formData.append('unit_id',unit_id);
                formData.append('remarks',remarks);

                (new RequestHandler).post(url,formData).then((res) => {
                    Alert.toast(res.status,res.message);
                    if(/priority/i.test(remarks)){
                        _this.closest('tr').removeClass('text-muted').addClass('text-danger');
                        _this.removeClass('text-muted').addClass('text-danger');
                    }else{
                        _this.closest('tr').removeClass('text-danger').addClass('text-muted');
                        _this.removeClass('text-danger').addClass('text-muted')
                    }
                })
                .catch((error) => {
                    console.log(error)
                    Alert.alert('error',"Something went wrong. Try again later", false);
                })
                .finally(() => {
                });
            }
        })

        _page.on('click','.export-haulage',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            let modal_id = $(this).attr('modal-id');
            let modal_title = $(this).attr('modal-title');

            exportTripBlockList().then((res) => {
                if(res){
                    modal_state(modal_id,'show');
                }
            })
        })

        _page.on('change','.export_all', function() {
            // Get the checked status of the header checkbox
            var isChecked = $(this).is(':checked');

            $(this).closest('table').find('tbody .form-check-input').each(function() {
                $(this).prop('checked', isChecked);
            });
        });

        _page.on('change','select[name="export_batch"]',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            let batch = $(this).val();
            let filter = $('select[name="filter_exported"]').val();
            let searchTerm = $('input[name="search_tripblock"]').val();

            ExportTripBlock.block();
                exportTripBlockList(batch,searchTerm,filter).then((res) => {
                    setTimeout(function() {
                        ExportTripBlock.release();
                }, 200);
            })

        })

        _page.on('change','select[name="filter_exported"]',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            let filter = $(this).val();
            let batch = $('select[name="export_batch"]').val();
            let searchTerm = $('input[name="search_tripblock"]').val();

            ExportTripBlock.block();
                exportTripBlockList(batch,searchTerm,filter).then((res) => {
                    setTimeout(function() {
                        ExportTripBlock.release();
                }, 200);
            })

        })

        _page.on('keyup','input[name="search_tripblock"]',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            let searchTerm = $(this).val();
            let batch = $('select[name="export_batch"]').val();
            let filter = $('select[name="filter_exported"]').val();

            if (e.key === 'Enter' || e.keyCode === 13 && searchTerm != '') {
                ExportTripBlock.block();
                exportTripBlockList(batch,searchTerm,filter).then((res) => {
                    setTimeout(function() {
                        ExportTripBlock.release();
                    }, 200);
                })
            } else if (e.keyCode === 8 || e.key === 'Backspace') {
                setTimeout(() => {
                    let updatedSearchTerm = $(this).val();
                    if (updatedSearchTerm === '') {
                        ExportTripBlock.block();
                        exportTripBlockList(batch,updatedSearchTerm,filter)
                        setTimeout(function() {
                            ExportTripBlock.release();
                        },200);
                    }
                }, 0);
            }



        })

        _page.on('click','.export_tripblock',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            let modal = $(this).closest('.modal');
            let modal_id = $(this).attr('modal-id');
            var checkedValues = modal.find('tbody .form-check-input:checked')
            .map(function() {
                return $(this).val();
            }).get();


            if(checkedValues.length > 0){
                Alert.confirm(`question`,`Export this trip blocks?`, {
                    onConfirm: function() {
                        let formData = new FormData();
                        formData.append('id',param);
                        formData.append('tripblock_ids',window.btoa(JSON.stringify(checkedValues)));

                        (new RequestHandler).post('/tms/cco-b/planner/haulage_info/export_tripblock',formData).then((res) => {
                            if(res.status == 'success'){
                                modal_state(modal_id);
                            }
                            Alert.toast(res.status,res.message);
                        })
                        .catch((error) => {
                            console.log(error)
                            Alert.alert('error',"Something went wrong. Try again later", false);
                        })
                        .finally(() => {
                        });
                    }
                })

            }else{
                Alert.alert('info','No trip blocks selected')
            }

        })

        _page.on('click','#modal_export_hauling_plan .cancel',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            let modal_id = $(this).attr('modal-id');

            Alert.confirm(`question`,`Close this export ?`, {
                onConfirm: function() {
                    modal_state(modal_id);
                }
            })

        })


    })

}
