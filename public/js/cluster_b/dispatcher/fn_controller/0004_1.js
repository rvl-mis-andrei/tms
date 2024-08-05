'use strict';
import {DealershipListDT} from '../dt_controller/serverside/0004_1.js';
import {fvNewDealership} from '../fv_controller/0004_1.js';
import {location} from "../../../global/select.js"


export function ClientInfoController(page,param){

    DealershipListDT(param).then(() => {
        location(param).then((res) => {
            if(res){
                fvNewDealership(param)
            }
        });
    });

}
