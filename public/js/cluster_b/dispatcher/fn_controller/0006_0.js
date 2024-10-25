'use strict';
import {DriverDT} from '../dt_controller/serverside/0006_0.js';
import {fvDriver} from '../fv_controller/0006_0.js';
import {trailer_driver} from "../../../global/select.js"
import { fvTractorTrailer } from "../fv_controller/0002_1.js";
import {modal_state} from "../../../global.js"


export var DriverListController = function (page,param) {




    return {
        init: function () {
            DriverDT().init();
            fvDriver(false,'#driver_table');
        }
    }

}
