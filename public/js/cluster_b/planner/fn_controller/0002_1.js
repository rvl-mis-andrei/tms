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
                    let html = '',tbody='';
                    hauling_list.empty();
                    if(payload.length >0){
                        payload.forEach(function(item,key) {
                            tbody = '';
                            item.block_units.forEach(function(units) {
                                tbody+=`<tr data-original-table="tbl_${units.dealer_code}" data-type="forAllocation" data-id="${units.encrypted_id}">
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

                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3 add-row">
                                                            Add Rows
                                                        </a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3 delete-row">
                                                            Delete Rows
                                                        </a>
                                                    </div>
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
                                                <table class="table align-middle gy-5 table-sm" id="tbl_block_${item.block_number}" data-type="planning" data-id="${item.encrypted_id}">
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
                            hauling_list.append(html)
                            if(item.status !=2){  initSortableTribBlock(`tbl_block_${item.block_number}`)  }
                        })
                        hauling_list.removeClass('d-none')
                        empty_hauling_list.addClass('d-none')
                    }else{
                        hauling_list.addClass('d-none')
                        empty_hauling_list.removeClass('d-none')
                    }
                    $('.haulage_info_page').removeClass('d-none')
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

                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3 add-rows">
                                                            Add Rows
                                                        </a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3 remove-rows">
                                                            Remove Rows
                                                        </a>
                                                    </div>
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
                                                    <tbody class="fs-7 fw-semibold text-gray-600" data-type="planning">
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

    function loadForAllocation(hub='svc'){
        let formData = new FormData();
        formData.append('id',param)
        formData.append('hub',hub)
        return new Promise((resolve, reject) => {
            (new RequestHandler).post('/tms/cco-b/planner/haulage_info/for_allocation',formData).then((res) => {
                if(res.status == 'success'){
                    let payload = JSON.parse(window.atob(res.payload));
                    let html = '',accordion=0;
                    if(Object.keys(payload).length){
                        $(`.${hub}_content`).empty();
                        $(`.for_allocation`).empty();
                        Object.keys(payload).forEach(function(key) {
                            let tbody = '';
                            accordion++;
                            payload[key].forEach(function(item) {
                                if(item.encrypted_id){
                                    tbody+=`<tr data-id="${item.encrypted_id}">
                                        <td>${item.cs_no}</td>
                                        <td>${item.model}</td>
                                        <td>${item.color_description}</td>
                                        <td>${item.invoice_date}</td>
                                        <td>${item.updated_location}</td>
                                    </tr>`;
                                }
                            })
                            html=`<div class="accordion" id="kt_accordion_${accordion}">
                                    <div class="accordion-item rounded-0">
                                        <h2 class="accordion-header rounded-0" id="accordion_${key}">
                                            <button class="accordion-button fs-4 fw-semibold rounded-0 collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#kt_accordion_${accordion}_body_${accordion}"
                                                aria-expanded="true" aria-controls="kt_accordion_${accordion}_body_${accordion}">
                                                ${key} Units : ${payload[key].length}
                                            </button>
                                        </h2>
                                        <div id="kt_accordion_${accordion}_body_${accordion}" class="accordion-collapse collapse"
                                            aria-labelledby="accordion_${key}" data-bs-parent="#kt_accordion_${accordion}">
                                            <div class="accordion-body">
                                                <div class="table-responsive">
                                                    <table class="table align-middle gy-5 table-sm" id="tbl_${key}" data-type="forAllocation">
                                                        <thead class="">
                                                            <tr class=" fw-bold fs-8 text-uppercase gs-0">
                                                                <th class="">Cs No.</th>
                                                                <th class="">Model</th>
                                                                <th class="">Color</th>
                                                                <th class="">Invoice</th>
                                                                <th class="">Location</th>
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
                            initSortableAllocation(`tbl_${key}`)
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
                selectedItems.forEach(function(item) {
                    let originalTableId = item.getAttribute('data-original-table');
                    let currentTableId = evt.to.closest('table').id;
                    let cells = item.getElementsByClassName('remove-cell');

                    if(evt.to.getAttribute('data-type') === 'planning' && item.getAttribute('data-type') === 'forAllocation'){
                        let formData = new FormData();
                        formData.append('haulage_id',param);
                        formData.append('block_id',evt.to.getAttribute('data-id'));
                        formData.append('unit_id',item.getAttribute('data-id'));
                        formData.append('batch',$('select[name="batch"]').val());
                        formData.append('status',1);

                        (new RequestHandler).post('/tms/cco-b/planner/haulage_info/update_block_units',formData).then((res) => {
                            if(res.status == 'success'){
                                let payload = JSON.parse(window.atob(res.payload));

                                while (cells.length > 0) {
                                    cells[0].parentNode.removeChild(cells[0]);
                                }

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
                            }else{
                                Alert.alert('error',res.message)
                            }
                        })
                    }

                    // if (item.getAttribute('data-type') === 'forAllocation') {
                    //     if (currentTableId !== originalTableId && evt.to.getAttribute('data-type') === 'forAllocation') {
                    //         document.getElementById(originalTableId).getElementsByTagName('tbody')[0].appendChild(item);
                    //     }
                    // }
                });
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

                selectedItems.forEach(function(item) {
                    let originalTableId = item.getAttribute('data-original-table');
                    let currentTableId = evt.to.closest('table').id;
                    let tableToType = evt.to.getAttribute('data-type');
                    let cells = item.getElementsByClassName('remove-cell');
                    let formData = new FormData();

                    formData.append('haulage_id',param);
                    formData.append('block_id',tableToType === 'forAllocation'?'':evt.to.getAttribute('data-id'));
                    formData.append('unit_id',item.getAttribute('data-id'));
                    formData.append('batch',$('select[name="batch"]').val());
                    formData.append('status',tableToType === 'forAllocation'?0:1);
                    formData.append('unit_order',Array.from(currentTable.querySelectorAll('tbody tr')).indexOf(item) + 1);

                    (new RequestHandler).post('/tms/cco-b/planner/haulage_info/update_block_units',formData).then((res) => {
                        if(res.status == 'success'){
                            if (item.getAttribute('data-type') === 'forAllocation' && tableToType === "forAllocation") {
                                while (cells.length > 0) {
                                    cells[0].parentNode.removeChild(cells[0]);
                                }
                                if (currentTableId !== originalTableId) {
                                    document.getElementById(originalTableId).getElementsByTagName("tbody")[0].appendChild(item);
                                }
                            }
                        }else{
                            Alert.alert('error',res.message)
                        }
                    }).catch((error) => {
                        console.log(error)
                        Alert.alert('error',"Something went wrong. Try again later", false)
                    })
                    .finally(() => {
                        // code here
                    });
                });
            },
            onStart: function(evt) {
                // if (!evt.item.getAttribute('data-original-table')) {
                //     evt.item.setAttribute('data-original-table', evt.from.closest('table').id);
                // }
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

            Alert.confirm("question","Finalize Hauling Plan?", {
                onConfirm: function() {
                    btn_submit.attr("data-kt-indicator","on");
                    btn_submit.attr("disabled",true);
                    let formData = new FormData();
                    formData.append('id',param);
                    (new RequestHandler).post(rq_url,formData).then((res) => {
                        Alert.toast(res.status,res.message);
                        if(res.status == 'success'){
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

            $('#form').attr('action',rq_url);
            $('.modal_title').text(modal_title);

            modal_state(modal_id,'show');
        })

    })

}
