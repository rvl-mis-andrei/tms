'use strict';
import { fvTractorTrailer } from "../fv_controller/0002_1.js";
import {tractor,trailer,cluster_driver,trailer_type} from "../../../global/select.js"
import {modal_state} from "../../../global.js"
import { GarageDT,LocationDT,TrailerTypeDT } from '../dt_controller/serverside/0009_0.js';
import { fvGarage,fvLocation,fvTrailerType } from '../fv_controller/0009_0.js';


export var SettingsController = function (page,param) {

    const _page = $('.page-settings');
    let tabLoaded = [];

    function loadActiveTab(tab=false){
        tab = (tab == false ? (localStorage.getItem("settings_tab") || 'garage') : tab);
        const _tab = {
            "garage": GarageTab,
            "location": LocationTab,
            "trailer_type": TrailerTypeTab,

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

    function GarageTab()
    {
        return new Promise((resolve, reject) => {
            try {
                GarageDT().init();
                fvGarage(param,'#garage_table');
                resolve(true);
            } catch (error) {
                resolve(false);
            }
        });
    }

    function LocationTab()
    {
        return new Promise((resolve, reject) => {
            try {
                LocationDT().init();
                fvLocation(param,'#location_table');
                resolve(true);
            } catch (error) {
                resolve(false);
            }
        });
    }

    function TrailerTypeTab()
    {
        return new Promise((resolve, reject) => {
            try {

                TrailerTypeDT().init();
                fvTrailerType(false,'#trailer_type_table');
                resolve(true);

            } catch (error) {
                reject(false);
            }
        });
    }

    return {
        init: function () {

            loadActiveTab().then((tab) => {
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

                localStorage.setItem("settings_tab",tab);
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
