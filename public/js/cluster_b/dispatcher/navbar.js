'use strict';

import { page_content } from './pg_content.js';
import { DashboardController } from './fn_controller/0001.js';
import { ClientListController } from './fn_controller/0004.js';


async function init_page() {
    let pathname = window.location.pathname;
    let page = pathname.split("/")[4];
    let param = false;
    let url = window.location.pathname;
    if(url.split('/')[5] !== null && typeof url.split('/')[5] !== 'undefined'){
        param =  pathname.split("/")[5];
    }
    load_page(page, param).then(
        page_handler(page,param),
    )
}

export async function load_page(page, param=null){
    page_content(page,param).finally(() => {
        page_handler(page,param).then(
             KTComponents.init(),
             $('.form-select').select2()
        )
    })
}

export async function page_handler(page,param=null){
    switch (page) {

        case 'dashboard':
            DashboardController(page,param);
        break;

        case 'dispatch':
            // DispatchController(page,param);
        break;

        case 'reports':
        break;

        case 'client_list':
            ClientListController(page,param);
        break;

        case 'tractor_trailer_list':
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

document.addEventListener('DOMContentLoaded', function () {

    init_page()

    $(".navbar").on("click", function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        let page = $(this).data('page');
        let link = $(this).data('link');
        let title = $(this).find('.menu-title').text();
        // let breadecrumbs_menu = [{'link': link,'page': page,'title':title}]
        // sessionStorage.setItem("breadecrumbs", JSON.stringify(breadecrumbs_menu));
        load_page(page).then(
            page_handler(page),
        );
    })

})
