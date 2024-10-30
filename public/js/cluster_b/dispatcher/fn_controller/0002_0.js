'use strict';
import {Alert} from "../../../global/alert.js"
import {HaulingPlanDT} from '../../planner/dt_controller/serverside/0002_0.js';
import {fvHaulingPlan} from '../../planner/fv_controller/0002_0.js';


export var HaulingPlanController = function (page,param) {
    
    return {
        init: function () {

            page_block.block();

            HaulingPlanDT().then((res) => {
                fvHaulingPlan()
                setTimeout(() => {
                    page_block.release();
                    KTComponents.init(); // Initialize components after the page is released
                }, 300);

            }).catch((error) => {
                console.error("Error loading trip block:", error);
                Alert.alert('error', "Failed to load trip block. Please try again.", false);

            }).finally(() => {
                $('button[data-bs-target="#modal_add_hauling_plan"]').remove();
            });

        }
    }

}
