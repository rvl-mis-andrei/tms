'use strict';
import {Alert} from "../../global/alert.js"


export var DashboardController = function (page,param) {

    return {
        init: function () {

            page_block.block();

            setTimeout(() => {
                page_block.release();
                KTComponents.init();
            }, 300);

        }
    }

}
