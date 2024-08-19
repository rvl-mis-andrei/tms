'use strict';
// import {ClientListDT} from '../dt_controller/serverside/0004_0.js';
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {data_bs_components,modal_state,page_state} from "../../../global.js";
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
                break;

                case 'tab-content-2':
                    loadHaulage(tab).then((res)=>{
                        resolve(res)
                    })
                break;


                case 'tab-content-3':
                    loadUnderload(tab).then((res)=>{
                        resolve(res)
                    })
                break;

                default:
                    resolve(false)
                break;
            }
        })
    }

    async function loadHaulage(batch=localStorage.getItem("haulage_info_batch")){

        let hauling_list = $('.hauling_list');
        let empty_hauling_list = $('.empty_hauling_list');

        let formData = new FormData();
        formData.append('id',param)
        formData.append('batch',batch)
        return new Promise((resolve, reject) => {
            (new RequestHandler).post('/services/haulage_info/list',formData).then((res) => {
                console.log(res)
                if(res.status == 'success'){
                    let payload = JSON.parse(window.atob(res.payload));
                    if(payload.length >0){
                        hauling_list.removeClass('d-none')
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

        _page.on('change','select[name="hauling_batch"]',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()
            loadHaulage($(this).val()).then((res) =>{
                localStorage.setItem("haulage_info_batch",$(this).val())
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
