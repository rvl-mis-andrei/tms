'use strict';
import {TractorTrailerListDT} from '../dt_controller/serverside/0005_0.js';
import {fvNewTractorTrailer} from '../fv_controller/0005_0.js';
import {tractor,trailer,cluster_driver} from "../../../global/select.js"


export function TractorTrailerController(page,param){

    TractorTrailerListDT(param).then(() => {
        tractor().then(() => {
            trailer().then(() => {
                cluster_driver().then(() => {
                    fvNewTractorTrailer(param)
                    $('select[data-control="select2"]').select2({
                        dropdownParent: $('.modal')
                    });
                });
            });
        });
    });

}
