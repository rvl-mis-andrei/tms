'use strict';

import { page_content } from './pg_content.js';
import { DashboardController } from './fn_controller/0001.js';
import { HaulingPlanController } from './fn_controller/0002_0.js';
import { HaulingPlanInfoController } from './fn_controller/0002_1.js';





async function init_page() {
    let pathname = window.location.pathname;
    let page = pathname.split("/")[4];
    let param = false;
    let url = window.location.pathname;
    if(url.split('/')[5] !== null && typeof url.split('/')[5] !== 'undefined'){
        param =  pathname.split("/")[5];
    }
    load_page(page, param).then((res) => {
        if (res) {
            //validation
        }
    })
}

export async function load_page(page, param=null){
    return page_content(page,param).then((res) => {
        if (res) {
            page_handler(page,param).then(() => {
                KTComponents.init()
            })
        }
    })
}

export async function page_handler(page,param=null){
    switch (page) {

        case 'dashboard':
            DashboardController(page,param);
        break;

        case 'hauling_plan':
            HaulingPlanController(page,param);
        break;

        case 'hauling_plan_info':
            HaulingPlanInfoController(page,param);
        break;
        default:
            console.log("No handler found for this page");
        break;
    }
}

$(document).ready(function(e){

    init_page()

    $(".navbar").on("click", function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        let page = $(this).data('page');
        let link = $(this).data('link');
        let title = $(this).find('.menu-title').text();
        load_page(page);
        // let breadecrumbs_menu = [{'link': link,'page': page,'title':title}]
        // sessionStorage.setItem("breadecrumbs", JSON.stringify(breadecrumbs_menu));
    })

})
