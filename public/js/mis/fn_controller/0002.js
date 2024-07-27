// VIDEO LIST

import {page_content} from '../pg_controller/pg_content.js';
import {_httpRequest} from '../pg_controller/pg_data.js';
import {_dtVideoList} from '../dt_controller/serverside/0002.js';
import {gs_sessionStorage,gs_clearSession} from '../../global.js';

export function controller(view,pg){

    _dtVideoList();

    // NEW VIDEO
    $('body').on('click','.btn-0002-0001', e => {
        e.preventDefault();
        e.stopImmediatePropagation();

        page_content('new-training-videos');
    });

    // EDIT VIDEO
    $('body').on('click','.btn-0002-0002', e => {
        e.preventDefault();
        e.stopImmediatePropagation();
    });


    KTMenu.createInstances();

}

export function construct(res,type){

    // code here

}
