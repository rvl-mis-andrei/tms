'use strict';
import {TractorTrailerListDT} from '../dt_controller/serverside/0005_0.js';
import {fvNewTractorTrailer} from '../fv_controller/0005_0.js';
import {tractor,trailer,cluster_driver} from "../../../global/select.js"


export function TractorTrailerInfoController(page,param)
{

    function loadLastTab(){
        let tab = localStorage.getItem('tractor_trailer_tab')|| 'tab-1';
        $(`.tab[data-tab='${tab}']`).addClass('border-3 border-bottom border-primary');
        $(`.${tab}`).removeClass('d-none');
        loadTab(tab);
    }

    function loadTab(tab)
    {
        return new Promise((resolve, reject) => {
            switch (tab) {
                case 'tab-1':
                    loadTractorTrailerInfo(tab).then((res)=>{
                        resolve(res)
                    })
                break;

                case 'tab-2':
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

    function loadTractorTrailerInfo (tab)
    {
        return new Promise((resolve, reject) => {

            $(`.${tab}`).removeClass('d-none')
            resolve(true)

        })

    }


    async function loadTractorTrailerLogs()
    {
        return new Promise((resolve, reject) => {

            $(`.${tab}`).removeClass('d-none')
            resolve(true)
        })
    }


    $(document).ready(function(e){

        if(document.readyState == 'complete'){

            loadLastTab()

            let app = $('.tractor_trailer_info');
            app.on('click','.tab',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let tab = $(this).attr('data-tab');
                let tab_css = 'border-3 border-bottom border-primary text-active-primary active';
                loadTab($(this).attr(tab)).then((res)=>{

                    $('.tab').removeClass(tab_css)
                    $(this).addClass(tab_css)

                    $(`.tab-content`).addClass('d-none');
                    $(`.${tab}`).removeClass('d-none');
                    localStorage.setItem("tractor_trailer_tab",tab)
                })
            })

        }

    });

}
