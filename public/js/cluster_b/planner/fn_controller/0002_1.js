'use strict';
// import {ClientListDT} from '../dt_controller/serverside/0004_0.js';
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
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
                    if(payload.length >0){
                        payload.forEach(function(item) {
                            tbody = '';
                            item.block_units.forEach(function(units) {
                                tbody+=`<tr>
                                        <td>${units.dealer_code}</td>
                                        <td>${units.cs_no}</td>
                                        <td>${units.model}</td>
                                        <td>${units.color_description}</td>
                                        <td>${units.invoice_date}</td>
                                        <td>${units.updated_location}</td>
                                        <td>${units.inspection_start}</td>
                                        <td>${units.hub}</td>
                                        <td>${units.remarks}</td>
                                    </tr>`;
                            })
                            html+=`
                            <div class="card mb-10">
                                    <div class="card-header collapsible">
                                        <span class="card-title"><h6>${item.dealer}</h6></span>
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
                                                <table class="table align-middle gy-5 table-sm" id="kt_customers_table">
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
                                                            <th class="">Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="fs-8 fw-semibold text-gray-600">
                                                        ${tbody}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            block_number = item.block_number;
                        })

                        hauling_list.empty().append(html).removeClass('d-none')
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
                                                <table class="table align-middle fs-6 gy-5 table-sm" id="kt_customers_table">
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
                                                    <tbody class="fs-7 fw-semibold text-gray-600">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            hauling_list.last().append(html).removeClass('d-none');
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
                    console.log(payload)
                    if(Object.keys(payload).length){
                        Object.keys(payload).forEach(function(key) {
                            let tbody = '';
                            accordion++;
                            payload[key].forEach(function(item) {
                                tbody+=`<tr>
                                    <td>${item.cs_no}</td>
                                    <td>${item.model}</td>
                                    <td>${item.color_description}</td>
                                    <td>${item.invoice_date}</td>
                                    <td>${item.updated_location}</td>
                                </tr>`;
                            })
                            html+=`<div class="accordion" id="kt_accordion_${accordion}">
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
                                                    <table class="table align-middle gy-5 table-sm">
                                                        <thead class="">
                                                            <tr class=" fw-bold fs-8 text-uppercase gs-0">
                                                                <th class="">Cs No.</th>
                                                                <th class="">Model</th>
                                                                <th class="">Color</th>
                                                                <th class="">Invoice</th>
                                                                <th class="">Location</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="fs-8 fw-semibold text-gray-600">
                                                            ${tbody}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                        })
                        $(`.${hub}_content`).removeClass('d-none').empty().append(html);
                        $(`.empty_${hub}`).addClass('d-none');
                    }else{
                        $(`.${hub}_content`).addClass('d-none').empty();
                        $(`.${hub}_content`).removeClass('d-none');
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

    $(document).ready(function(e){

        loadTripBlock().then(()=>{
            fvHaulingPlanInfo(param)
            loadForAllocation('svc')
            custom_upload()
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


        // Initialize Sortable with MultiDrag plugin on the allocation list
        // new Sortable(document.getElementById('allocation-list').getElementsByTagName('tbody')[0], {
        //     group: 'shared',
        //     animation: 150,
        //     multiDrag: true,
        //     selectedClass: 'selected',
        //     onEnd: function(evt) {
        //         console.log(evt.to)
        //         // Check if the item was dropped into the destination table
        //         if (evt.to === document.getElementById('destination-table').getElementsByTagName('tbody')[0]) {
        //             alert('Row successfully moved to the destination table!');
        //         } else {
        //             alert('Row returned to the original table.');
        //         }
        //     },
        //     // Called when an item is selected
        //     onSelect: function(/**Event*/evt) {
        //         evt.item // The selected item
        //     },

        //     // Called when an item is deselected
        //     onDeselect: function(/**Event*/evt) {
        //         evt.item // The deselected item
        //     }
        // });

        // Initialize Sortable with MultiDrag plugin on the destination table
        // new Sortable(document.getElementById('destination-table').getElementsByTagName('tbody')[0], {
        //     group: 'shared',
        //     animation: 150,
        //     multiDrag: true,
        //     selectedClass: 'selected',
        //     onEnd: function(evt) {
        //         console.log(123)
        //     },
        //     // Called when an item is selected
        //     onSelect: function(/**Event*/evt) {
        //         evt.item // The selected item
        //     },

        //     // Called when an item is deselected
        //     onDeselect: function(/**Event*/evt) {
        //         evt.item // The deselected item
        //     }
        // });
    })


}
