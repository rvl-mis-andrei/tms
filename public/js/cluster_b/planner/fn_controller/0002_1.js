'use strict';
// import {ClientListDT} from '../dt_controller/serverside/0004_0.js';
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {data_bs_components,modal_state,page_state,custom_upload} from "../../../global.js";
import {fvHaulingPlanInfo} from '../fv_controller/0002_1.js';



export function HaulingPlanInfoController(page,param){

    let _page = $('.haulage_info_page');

    function loadLastTab(){
        let tab = localStorage.getItem('haulage_info_tab') || 'tab-content-1';
        loadTab(tab).then(()=>{
            $(`a[data-tab='${tab}']`).addClass('active')
            $(`.${tab}`).removeClass('d-none')
            KTComponents.init()
            data_bs_components()
        })
    }

    function loadTab(tab)
    {
        return new Promise((resolve, reject) => {
            switch (tab) {
                case 'tab-content-1':
                    resolve(true)
                break;

                case 'tab-content-2':
                    loadTripBlock().then((res)=>{
                        resolve(res)
                    })
                break;


                case 'tab-content-3':
                    // loadUnderload(tab).then((res)=>{
                    //     resolve(res)
                    // })
                    resolve(true)

                break;

                default:
                    resolve(false)
                break;
            }
        })
    }

    async function loadTripBlock(batch=1)
    {
        let hauling_list = $('.hauling_list');
        let empty_hauling_list = $('.empty_hauling_list');

        let formData = new FormData();
        formData.append('id',param)
        formData.append('batch',batch)
        return new Promise((resolve, reject) => {
            (new RequestHandler).post('/tms/cco-b/planner/haulage_info/tripblock',formData).then((res) => {
                console.log(res)
                if(res.status == 'success'){
                    let payload = JSON.parse(window.atob(res.payload));
                    let html = '',tbody='';
                    console.log(payload)
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
                                                        <a href="#" class="menu-link px-3">
                                                            Add Rows
                                                        </a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3 text-danger">
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
                                                        ${tbody}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        })
                        hauling_list.empty().append(html).removeClass('d-none')
                        empty_hauling_list.addClass('d-none')
                    }else{
                        hauling_list.addClass('d-none')
                        empty_hauling_list.removeClass('d-none')
                    }
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

    // async function loadForAllocation(){
    //     let for_allocation = $('.for_allocation');

    //     return new Promise((resolve, reject) => {
    //         (new RequestHandler).post(url,formData).then((res) => {
    //             Alert.toast(res.status,res.message);
    //             if(res.status == 'success' && res.page){
    //                 for_allocation.empty().html(res.page).promise().done(function(){
    //                 });
    //             }
    //         })
    //         .catch((error) => {
    //             console.log(error)
    //             Alert.alert('error',"Something went wrong. Try again later", false);
    //         })
    //         .finally(() => {
    //             // code here
    //         });
    //     })
    // }

    // function loadUnderload(){
    //     let for_underload = $('.for_underload');
    //     return new Promise((resolve, reject) => {
    //         (new RequestHandler).post(url,formData).then((res) => {
    //             Alert.toast(res.status,res.message);
    //             if(res.status == 'success' && res.page){
    //                 for_underload.empty().html(res.page).promise().done(function(){
    //                 });
    //             }
    //         })
    //         .catch((error) => {
    //             console.log(error)
    //             Alert.alert('error',"Something went wrong. Try again later", false);
    //         })
    //         .finally(() => {
    //             // code here
    //         });
    //     })
    // }

    $(document).ready(function(e){

        loadLastTab()
        fvHaulingPlanInfo(param)
        custom_upload()

        _page.on('click','.nav-tab',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()
            let tab = $(this);
            let data_tab = tab.attr('data-tab');
            loadTab(data_tab).then(()=>{

                $('.nav-tab').removeClass('active')
                tab.addClass('active')

                $('.tab-content').addClass('d-none')
                $(`.${data_tab}`).removeClass('d-none')

                KTComponents.init()
                data_bs_components()
                localStorage.setItem("haulage_info_tab",data_tab)
            })
        })

        _page.on('change','select[name="batch"]',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()
            loadTripBlock($(this).val()).then((res) =>{
            })
        })

        _page.on('click','.upload',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()
            let tab = $(this);
            let data_tab = tab.attr('data-tab');
            loadTab(data_tab).then(()=>{

                $('.nav-tab').removeClass('active')
                tab.addClass('active')

                $('.tab-content').addClass('d-none')
                $(`.${data_tab}`).removeClass('d-none')

                KTComponents.init()
                data_bs_components()
                localStorage.setItem("haulage_info_tab",data_tab)
            })
        })

        // _page.on('click','.add-block',function(e){
        // })


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
