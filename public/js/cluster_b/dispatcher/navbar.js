'use strict';

import { page_content } from './pg_content.js';
import { DashboardController } from './fn_controller/0001.js';
import { HaulingPlanController } from './fn_controller/0002_0.js';
import { HaulingPlanInfoController } from './fn_controller/0002_1.js';

import { ClientListController } from './fn_controller/0004_0.js';
import { ClientInfoController } from './fn_controller/0004_1.js';
import { TractorTrailerController } from './fn_controller/0005_0.js';
import { TractorTrailerInfoController } from './fn_controller/0005_1.js';
import { DriverListController } from './fn_controller/0006_0.js';
import { CarListController } from './fn_controller/0007_0.js';
import { CycleTimeController } from './fn_controller/0008_0.js';
import { SettingsController } from './fn_controller/0009_0.js';



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
    const handler = _handlers[page];
    if (handler) {
        handler(page, param);
    } else {
        console.log("No handler found for this page");
    }
}

const _handlers = {
    dashboard: (page, param) => DashboardController(page, param),
    dispatch: (page, param) => HaulingPlanController(page, param).init(),
    hauling_plan_info: (page, param) => HaulingPlanInfoController(page, param).init(),
    reports: () => {}, // Empty function for 'reports'
    client_list: (page, param) => ClientListController(page, param).init(),
    client_info: (page, param) => ClientInfoController(page, param),
    tractor_trailer_list: (page, param) => TractorTrailerController(page, param).init(),
    tractor_trailer_info: (page, param) => TractorTrailerInfoController(page, param),
    driver_list: (page, param) => DriverListController(page, param).init(),
    car_list: (page, param) => CarListController(page, param).init(),
    cycle_time: (page, param) => CycleTimeController(page,param).init(),
    settings: (page, param) => SettingsController(page,param).init(),
    location: (page, param) => {},
    garage: (page, param) => {},
    car_color: () => {},
    trailer_type: (page, param) => {},
};

$(document).ready(function(e){

    init_page();

    $(".navbar").on("click", function(e) {
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
            }
        });
    });

})
