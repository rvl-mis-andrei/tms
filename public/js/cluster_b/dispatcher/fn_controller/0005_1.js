'use strict';
import {TractorTrailerListDT} from '../dt_controller/serverside/0005_0.js';
import {fvNewTractorTrailer} from '../fv_controller/0005_0.js';
import {tractor,trailer,cluster_driver} from "../../../global/select.js"


export function TractorTrailerInfoController(page,param)
{

    let app = $('.tractor_trailer_info');

    function loadLastTab(){
        let tab = localStorage.getItem('tractor_trailer_tab') || 'tab-content-1';
        $(`a[data-tab='${tab}']`).addClass('active')
        loadTab(tab).then((res)=>{ })
    }

    function loadTab(tab)
    {
        return new Promise((resolve, reject) => {
            switch (tab) {
                case 'tab-content-1':
                    loadTractorTrailerOverview(tab).then((res)=>{
                        resolve(res)
                    })
                break;

                case 'tab-content-2':
                    loadTractorTrailerLogs(tab).then((res)=>{
                        resolve(res)
                    })
                break;

                default:
                    resolve(false)
                break;
            }
        })
    }

    function loadTractorTrailerOverview (tab)
    {
        return new Promise((resolve, reject) => {

            $(`.${tab}`).removeClass('d-none').addClass('active show')
            resolve(true)
        })

    }


    async function loadTractorTrailerLogs(tab)
    {
        return new Promise((resolve, reject) => {

            $(`.${tab}`).removeClass('d-none').addClass('active show')
            resolve(true)
        })
    }


    $(document).ready(function(e){
        if(document.readyState == 'complete'){

            loadLastTab()

            app.on('click','a[data-bs-toggle="tab"]',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let tab = $(this).attr('data-tab');
                $(`.tab-pane`).addClass('d-none').removeClass('active show')
                loadTab(tab).then((res)=>{
                    localStorage.setItem("tractor_trailer_tab",tab)
                })


            })

        }
    });

}
