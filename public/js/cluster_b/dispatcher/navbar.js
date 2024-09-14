'use strict';

import { page_content } from './pg_content.js';
import { DashboardController } from './fn_controller/0001.js';
import { HaulingPlanController } from './fn_controller/0002_0.js';
import { HaulingPlanInfoController } from './fn_controller/0002_1.js';

import { ClientListController } from './fn_controller/0004_0.js';
import { ClientInfoController } from './fn_controller/0004_1.js';
import { TractorTrailerController } from './fn_controller/0005_0.js';
import { TractorTrailerInfoController } from './fn_controller/0005_1.js';



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
            $(`.navbar[data-page='${page}']`).addClass('active');
        }
    })
}

export async function load_page(page, param=null){
    try {
        const contentResult = await page_content(page, param);
        if (contentResult) {
            await page_handler(page, param);
            KTComponents.init();
            return true;
        } else {
            return false;
        }
    } catch (error) {
        console.error('Error in load_page:', error);
        return false;
    }
}

export async function page_handler(page,param=null){
    switch (page) {

        case 'dashboard':
            DashboardController(page,param);
        break;

        case 'dispatch':
            HaulingPlanController(page,param).init();
        break;

        case 'hauling_plan_info':
            HaulingPlanInfoController(page,param).init();
        break;

        case 'reports':
        break;

        case 'client_list':
            ClientListController(page,param)
        break;

        case 'client_info':
            ClientInfoController(page,param)
        break;

        case 'tractor_trailer_list':
            TractorTrailerController(page,param)
        break;

        case 'tractor_trailer_info':
            TractorTrailerInfoController(page,param)
        break;

        case 'driver_list':
        break;

        case 'cycle_time':
        break;

        case 'location':
        break;

        case 'garage':
        break;

        case 'car_color':
        break;

        case 'trailer_type':
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
        let _this = $(this);
        load_page(page).then((res) => {
            if (res) {
                $('.navbar').removeClass('active');
                _this.addClass('active');
            }else{

            }
        })
        // let breadecrumbs_menu = [{'link': link,'page': page,'title':title}]
        // sessionStorage.setItem("breadecrumbs", JSON.stringify(breadecrumbs_menu));
    })

})
