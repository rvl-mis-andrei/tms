'use strict';
// import {TractorTrailerListDT} from '../dt_controller/serverside/0005_0.js';
// import {fvNewTractorTrailer} from '../fv_controller/0005_0.js';
// import {tractor,trailer,cluster_driver} from "../../../global/select.js"
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {data_bs_components} from "../../../global.js";

export function TractorTrailerInfoController(page,param)
{

    let app = $('.tractor_trailer_info');

    function loadLastTab(){
        let tab = localStorage.getItem('tractor_trailer_tab') || 'tab-content-1';
        $(`a[data-tab='${tab}']`).addClass('active')
        loadTab(tab).then((res)=>{
            data_bs_components()
        })
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

            app.on('click','.delete',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let action = $(this).attr('data-action');
                let column = $(this).attr('data-column');
                let url = $(this).attr('data-url');

                let formData = new FormData();
                formData.append('id',param)
                formData.append('column',column+'_id')
                formData.append('action',action)

                Alert.confirm('question',"Decouple "+column+" ?",{
                    onConfirm: () => {
                        (new RequestHandler).post(url,formData).then((res) => {
                            Alert.toast(res.status,res.message);
                            if(res.status == 'success'){
                                //refresh page
                            }
                        })
                        .catch((error) => {
                            console.log(error)
                            Alert.alert('error',"Something went wrong. Try again later", false);
                        })
                        .finally(() => {
                            // code here
                        });
                    }
                })
            })

        }
    });

}
