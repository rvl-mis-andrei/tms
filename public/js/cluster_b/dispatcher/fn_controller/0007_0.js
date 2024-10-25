'use strict';
import { CarDT } from '../dt_controller/serverside/0007_0.js';
import {fvCar} from '../fv_controller/0007_0.js';
import {trailer_driver} from "../../../global/select.js"
import { fvTractorTrailer } from "../fv_controller/0002_1.js";
import {modal_state} from "../../../global.js"

export var CarListController = function (page,param) {

    return {
        init: function () {
            CarDT().init();
            fvCar(false,'#car_table');
        }
    }

}
