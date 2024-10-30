'use strict';
import {ClientListDT} from '../dt_controller/serverside/0004_0.js';
import {fvNewClient} from '../fv_controller/0004_0.js';

export var ClientListController = function (page,param) {
    return {
        init: function () {
            ClientListDT().init();
            fvNewClient()
        }
    }
}
