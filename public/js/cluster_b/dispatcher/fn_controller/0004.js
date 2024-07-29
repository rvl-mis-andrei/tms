'use strict';
import {ClientListDT} from '../dt_controller/serverside/0004.js';


export function ClientListController(view,pg){

    $(document).ready(function(e){
        if(document.readyState == 'complete'){

            ClientListDT()

            $(".navbar").on("click", function (e) {
                e.preventDefault()
                e.stopImmediatePropagation()

            })
            
        }
    })

}
