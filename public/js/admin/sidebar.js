'use strict';

import { page_content } from './pg_content.js';
import { DashboardController } from './fn_controller/0001_0.js';


async function init_page() {
    let pathname = window.location.pathname;
    let page = pathname.split("/")[3];

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
    dashboard: (page, param) => DashboardController(page, param).init(),
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
