'use strict';
import {ClientListDT,ClientDealershipDT} from '../dt_controller/serverside/0004.js';
import {fvNewClient} from '../fv_controller/0004.js';



export function ClientListController(view,pg){

    ClientListDT()

    fvNewClient()

}


export function ClientInfoController(view,pg){

//     $(document).ready(function(e){
//         if(document.readyState === 'complete'){
//             ClientDealershipDT()
//         }
//     })

}
