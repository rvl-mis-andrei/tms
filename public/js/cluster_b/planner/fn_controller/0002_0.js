'use strict';
import {HaulingPlanDT} from '../dt_controller/serverside/0002_0.js';
import {fvHaulingPlan} from '../fv_controller/0002_0.js';



export function HaulingPlanController(page,param){


    HaulingPlanDT()

    fvHaulingPlan()


    $(document).ready(function(e){


    })

}
