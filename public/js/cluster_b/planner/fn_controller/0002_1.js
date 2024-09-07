'use strict';
// import {ClientListDT} from '../dt_controller/serverside/0004_0.js';
import {Alert} from "../../../global/alert.js"
import {RequestHandler,} from "../../../global/request.js"
import {dealer,car_model} from "../../../global/select.js"
import {data_bs_components,modal_state,page_state,custom_upload} from "../../../global.js";
import {fvHaulingPlanInfo} from '../fv_controller/0002_1.js';



export function HaulingPlanInfoController(page,param){

    let _page = $('.haulage_info_page');
    var block_number = 0;
    var hauling_list = $('.hauling_list');
    var empty_hauling_list = $('.empty_hauling_list');

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
                            console.log(item)
                            tbody = '';
                            item.block_units.forEach(function(units) {
                                tbody+=`<tr data-original-table="tbl_${units.dealer_code}_${units.hub}" data-type="forAllocation" data-id="${units.encrypted_id}">
                                        <td class="remove-cell">${units.dealer_code}</td>
                                        <td>${units.cs_no}</td>
                                        <td>${units.model}</td>
                                        <td>${units.color_description}</td>
                                        <td>${units.invoice_date}</td>
                                        <td>${units.updated_location}</td>
                                        <td class="remove-cell">${units.inspection_start}</td>
                                        <td class="remove-cell">${units.hub}</td>
                                        <td class="remove-cell">${units.remarks}</td>
                                    </tr>`;
                                    trpBlockUnit++;
                            });
                            html=`
                            <div class="card mb-10">
                                    <div class="card-header collapsible">
                                        <span class="card-title"><h6>${item.dealer ?? 'Trip Block #'+(key+1)}</h6></span>
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
                                                            Remove Block
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
                                                <table class="table table-row-bordered align-middle gy-5 table-sm" id="tbl_block_${item.block_number}" data-type="planning" data-id="${item.encrypted_id}">
                                                    <thead>
                                                        <tr class=" fw-bold fs-8 text-uppercase gs-0">
                                                            <th>Dealer</th>
                                                            <th>Cs No.</th>
                                                            <th>Model</th>
                                                            <th>Color</th>
                                                            <th>Invoice Date</th>
                                                            <th>Location</th>
                                                            <th>Inspection TIme</th>
                                                            <th>Hub</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="fs-8 fw-semibold text-gray-600" data-type="planning" data-id="${item.encrypted_id}">
                                                        ${tbody}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            block_number = item.block_number;
                            hauling_list.append(html);
                            tripBlockCount++;
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
                            $('.finalize-plan').removeClass('d-none');
                            $('.add-block').removeClass('d-none');
                            $('.more-actions').removeClass('d-none');
                        }
                        hauling_list.removeClass('d-none')
                        empty_hauling_list.addClass('d-none')
                    }else{
                        hauling_list.addClass('d-none')
                        empty_hauling_list.removeClass('d-none')
                        $('.finalize-plan').removeClass('d-none');
                        $('.add-block').removeClass('d-none');
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
                // code here
            });
        })
    }

    function loadForAllocation(hub='svc'){
        let formData = new FormData();
        formData.append('id',param)
        formData.append('hub',hub)
        return new Promise((resolve, reject) => {
            (new RequestHandler).post('/tms/cco-b/planner/haulage_info/for_allocation',formData).then((res) => {
                if(res.status == 'success'){
                    let payload = JSON.parse(window.atob(res.payload));
                    let html = '',accordion=0, isFinal=false;

                    if(Object.keys(payload).length){
                        $(`.${hub}_content`).empty();
                        $(`.for_allocation`).empty();
                        Object.keys(payload).forEach(function(key) {
                            let tbody = '';
                            accordion++;
                            payload[key].unit.forEach(function(item) {
                                if(item.encrypted_id){
                                    tbody+=`<tr data-id="${item.encrypted_id}">
                                        <td>${item.cs_no}</td>
                                        <td>${item.model}</td>
                                        <td>${item.color_description}</td>
                                        <td>${item.invoice_date}</td>
                                        <td>${item.updated_location}</td>
                                        <td class="remove-cell">
                                           <a href="javascript:;" class="btn btn-icon btn-color-gray-500 btn-active-color-danger justify-content-end remove-unit"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Remove unit" data-id="${item.encrypted_id}" rq-url="/tms/cco-b/planner/haulage_info/remove_unit">
					                            <i class="ki-duotone ki-trash-square fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                </i>
                                            </a>
                                        </td>
                                    </tr>`;
                                }
                            })
                            html=`<div class="accordion" id="kt_accordion_${accordion}">
                                    <div class="accordion-item rounded-0">
                                        <h2 class="accordion-header rounded-0" id="accordion_${key}">
                                            <button class="accordion-button fs-4 fw-semibold rounded-0 collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#kt_accordion_${accordion}_body_${accordion}"
                                                aria-expanded="true" aria-controls="kt_accordion_${accordion}_body_${accordion}">
                                                ${key}
                                                <span class="badge badge-light-success ms-2">Allocated : ${payload[key].allocated}</span>
                                                <span class="badge badge-light-primary ms-2">Unallocated : ${payload[key].unallocated}</span>
                                            </button>
                                        </h2>
                                        <div id="kt_accordion_${accordion}_body_${accordion}" class="accordion-collapse collapse"
                                            aria-labelledby="accordion_${key}" data-bs-parent="#kt_accordion_${accordion}">
                                            <div class="accordion-body">
                                                <div class="table-responsive">
                                                    <table class="table table-row-bordered align-middle gy-5 table-sm" id="tbl_${key}_${payload[key].hub}" data-type="forAllocation">
                                                        <thead class="">
                                                            <tr class=" fw-bold fs-8 text-uppercase gs-0">
                                                                <th class="">Cs No.</th>
                                                                <th class="">Model</th>
                                                                <th class="">Color</th>
                                                                <th class="">Invoice</th>
                                                                <th class="">Location</th>
                                                                <th class="">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="fs-8 fw-semibold text-gray-600 cursor-pointer" data-type="forAllocation">
                                                            ${tbody}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                            $(`.${hub}_content`).append(html);
                            initSortableAllocation(`tbl_${key}_${payload[key].hub}`)
                        })
                        $(`.${hub}_content`).removeClass('d-none');
                        $(`.empty_${hub}`).addClass('d-none');
                        $(`.${hub}-count`).text(Object.keys(payload).length);
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
        block_number++;
        formData.append('id',param)
        formData.append('batch',$('select[name="batch"]').val())
        formData.append('block_number',block_number)

        return new Promise((resolve, reject) => {
            (new RequestHandler).post('/tms/cco-b/planner/haulage_info/add_tripblock',formData).then((res) => {
                if(res.status == 'success'){
                    let payload = JSON.parse(window.atob(res.payload));
                    if(payload.length >0){
                        payload.forEach(function(item) {
                            console.log(payload.length)
                            let html =`<div class="card mb-10" data-block="${item.block_number}">
                                    <div class="card-header collapsible">
                                        <span class="card-title"><h6>Trip Block #${item.block_number}</h6></span>
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
                                                <table class="table align-middle fs-6 gy-5 table-sm" id="tbl_block_${item.block_number}" data-type="planning">
                                                    <thead class="">
                                                        <tr class=" fw-bold fs-7 text-uppercase gs-0">
                                                            <th class="">Dealer</th>
                                                            <th class="">Cs No.</th>
                                                            <th class="">Model</th>
                                                            <th class="">Color</th>
                                                            <th class="">Invoice Date</th>
                                                            <th class="">Location</th>
                                                            <th class="">Inspection TIme</th>
                                                            <th class="">Hub</th>
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
        formData.append('id',param)
        formData.append('batch',$('select[name="batch"]').val())
        formData.append('block_id',_this.attr('data-id'))

        return new Promise((resolve, reject) => {
            (new RequestHandler).post('/tms/cco-b/planner/haulage_info/remove_tripblock',formData).then((res) => {
                if(res.status == 'success'){
                    $(_this).closest('.card').remove();
                    block_number--;
                    if(block_number ==0){
                        loadTripBlock($('select[name="batch"]').val())
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
                let currentTable = evt.to.closest('table');
                let formDataArray = [];
                selectedItems.forEach(function(item) {
                    let originalTableId = item.getAttribute('data-original-table');
                    let currentTableId = evt.to.closest('table').id;

                    let cells = item.getElementsByClassName('remove-cell');
                    console.log(item)
                    if (evt.to.getAttribute('data-type') === 'planning' && item.getAttribute('data-type') === 'forAllocation') {
                        let itemData = {
                            haulage_id: param,
                            block_id: evt.to.getAttribute('data-id'),
                            unit_id: item.getAttribute('data-id'),
                            batch: $('select[name="batch"]').val(),
                            status: 1,
                            unit_order: Array.from(currentTable.querySelectorAll('tbody tr')).indexOf(item) + 1
                        };
                        formDataArray.push(itemData);
                        while (cells.length > 0) {
                            cells[0].parentNode.removeChild(cells[0]);
                        }
                    }
                });
                if (formDataArray.length > 0) {
                    let formData = new FormData();
                    formData.append('units', JSON.stringify(formDataArray));
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

                                let remarksCell = document.createElement('td');
                                remarksCell.classList.add('remove-cell');
                                remarksCell.textContent = payload.remarks;
                                item.appendChild(remarksCell);
                            });
                        } else {
                            Alert.alert('error', res.message);
                        }
                    }).catch((error) => {
                        console.error(error);
                        Alert.alert('error', "Something went wrong. Try again later", false);
                    });
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
                put: ['planning', 'forAllocation']
            },
            animation: 150,
            multiDrag: true,
            selectedClass: 'selected',
            onEnd: function(evt) {
                let selectedItems = evt.items.length > 0 ? evt.items : [evt.item];
                let currentTable = evt.to.closest('table');
                let formData = new FormData();
                let selectedItemsData = [];
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
                    }
                    if(ispushData){ selectedItemsData.push(itemData) }
                });
                if (selectedItemsData.length > 0) {
                    formData.append('units', JSON.stringify(selectedItemsData));
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
                }
            },
            onStart: function(evt) {
                //code here
            },
        });
    }

    $(document).ready(function(e){

        loadTripBlock().then(()=>{
            fvHaulingPlanInfo(param)
            loadForAllocation('svc')
            custom_upload()
            dealer()
            car_model()
        })

        _page.on('click','.nav-tab',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()
            let tab = $(this);
            let hub = tab.attr('data-hub');
            loadForAllocation(hub)
        })

        _page.on('change','select[name="batch"]',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()
            loadTripBlock($(this).val()).then((res) =>{
            })
        })

        _page.on('click','.add-block',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()
            addTripBlock().then((res) =>{
                if(res){
                    Alert.toast('success','Trip block added')
                }
            })
        })

        _page.on('click','.remove-block',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()
            Alert.confirm('question',"Remove this block ?",{
                onConfirm: () => {
                     removeTripBlock($(this)).then((res) =>{
                        if(res){
                            Alert.toast('success','Trip block removed')
                        }
                    })
                }
            })
        })

        _page.on('click','.finalize-plan',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            let btn_submit = $(this);
            let rq_url = $(this).attr('rq-url');
            let batch = $('select[name="batch"]').val();

            Alert.confirm(`question`,`Finalize Hauling Plan Batch ${batch}?`, {
                onConfirm: function() {
                    btn_submit.attr("data-kt-indicator","on");
                    btn_submit.attr("disabled",true);
                    let formData = new FormData();
                    formData.append('id',param);
                    formData.append('batch',batch);
                    (new RequestHandler).post(rq_url,formData).then((res) => {
                        Alert.toast(res.status,res.message);
                        if(res.status == 'success'){
                            loadTripBlock(batch);
                        }
                    })
                    .catch((error) => {
                        console.log(error)
                        Alert.alert('error',"Something went wrong. Try again later", false);
                    })
                    .finally(() => {
                        btn_submit.attr("data-kt-indicator","off");
                        btn_submit.attr("disabled",false);
                        $("#hauling_plan_table").DataTable().ajax.reload(null, false);
                    });
                },
                onCancel: () => {
                    btn_submit.attr("data-kt-indicator","off");
                    btn_submit.attr("disabled",false);
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
                    let formData = new FormData();
                    formData.append('id',param);
                    formData.append('unit_id',unit_id);
                    (new RequestHandler).post(url,formData).then((res) => {
                        Alert.toast(res.status,res.message);
                        if(res.status == 'success'){
                            _this.closest('tr').remove();
                        }
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


    })

}
