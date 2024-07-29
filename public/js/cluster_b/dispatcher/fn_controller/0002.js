import {page_content} from '../pg_controller/pg_content.js';
import {_httpRequest} from '../pg_controller/pg_data.js';
import {_dtDeviceList} from '../dt_controller/serverside/0002.js';
import {gs_sessionStorage,gs_clearSession,gs_SelectSearch,gs_Modal} from '../../global.js';

export function controller(view,pg){

    _dtDeviceList();

    $('body').delegate('.btn-0002-0001','click',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        // NEW DEVICE
    });

    $('body').delegate('.btn-0002-0002','click',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        // EXPORT
    });


    KTMenu.createInstances();

}

export function construct(res,type){

    switch(type){

        case '':
        break;

        default:
        break;

    }

}
