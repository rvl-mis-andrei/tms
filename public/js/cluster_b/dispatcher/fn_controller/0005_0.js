'use strict';
import {TractorTrailerDT,TractorDT,TrailerDT} from '../dt_controller/serverside/0005_0.js';
import {fvTrailer,fvTractor} from '../fv_controller/0005_0.js';
import { fvTractorTrailer } from "../fv_controller/0002_1.js";
import {tractor,trailer,cluster_driver,trailer_type} from "../../../global/select.js"
import {modal_state} from "../../../global.js"


export var TractorTrailerController = function (page,param) {

    const _page = $('.page-tractor-trailer');
    let tabLoaded = [];

    function loadActiveTab(tab=false){
        tab = (tab == false ? (localStorage.getItem("tractor_trailer_tab") || 'tractor_trailer') : tab);
        const _tab = {
            "tractor_trailer": TractorTrailerTab,
            "tractor": TractorTab,
            "trailer": TrailerTab,

        };

        return new Promise((resolve, reject) => {
            if (_tab[tab]) {
                // Call the tab function with `tab` as a parameter, then resolve with `tab`
                _tab[tab](tab).then(() => resolve(tab)).catch(reject);
            } else {
                resolve(false);
            }
        });
    }

    function TractorTrailerTab()
    {
        return new Promise((resolve, reject) => {
            try {

                TractorTrailerDT().init();
                tractor();
                trailer();
                cluster_driver();
                fvTractorTrailer(false,'#tractor_trailer')

                _page.on('click','.add-tractor-trailer',function(e){
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    modal_state('#modal_add_tractor_trailer','show');
                    $('#modal_add_tractor_trailer').find('form').attr('action','/services/tractor/create_tractor_trailer');
                })

                resolve(true);

            } catch (error) {
                reject(false);
            }
        });
    }

    function TractorTab()
    {
        return new Promise((resolve, reject) => {
            try {

                TractorDT().init();
                fvTractor(param,'#tractor_table');

                resolve(true);
            } catch (error) {

                resolve(false);
            }
        });
    }

    function TrailerTab()
    {
        return new Promise((resolve, reject) => {
            try {
                TrailerDT().init();
                trailer_type();
                fvTrailer(param,'#trailer_table');
                resolve(true);
            } catch (error) {

                resolve(false);
            }
        });
    }

    return {
        init: function () {

            loadActiveTab().then((tab) => {
                console.log(tab)

                if(tab != false){
                    $('a[data-tab='+tab+']').addClass('active');
                    $(`.${tab}`).addClass('show active');
                    tabLoaded.push(tab);
                }
            })

            _page.on('click','.menu-tab',function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                let _this = $(this);
                let tab = $(this).attr('data-tab');
                _this.attr('disabled',true);

                localStorage.setItem("tractor_trailer_tab",tab);
                if(tabLoaded.includes(tab)){
                    _this.attr('disabled',false);
                    return;
                }

                page_block.block();
                tabLoaded.push(tab);
                loadActiveTab(tab).then((res) => {
                    if (res) {
                        setTimeout(() => {
                            page_block.release();
                            _this.attr('disabled',false);
                        },500);
                    }else{
                        // localStorage.setItem("tractor_trailer_tab",tab);
                    }
                })
            });
        }
    }

}
