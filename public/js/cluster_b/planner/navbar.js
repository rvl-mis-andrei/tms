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
            DashboardController(page,param).init();
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

        let _this = $(this);
        let page = _this.data('page');
        let link = _this.data('link');
        let title = _this.find('.menu-title').text();
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
