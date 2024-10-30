'use strict';
import {fvCar} from '../fv_controller/0007_0.js';
import {trailer_driver} from "../../../global/select.js"
import { fvTractorTrailer } from "../fv_controller/0002_1.js";
import {modal_state} from "../../../global.js"
import { CycleTimeTable } from '../dt_controller/clientside/0008_0.js';

export var CycleTimeController = function (page,param) {

    return {
        init: function () {
            CycleTimeTable().init();
            // fvCar(false,'#car_table');
        }
    }

}
