'use strict';
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {data_bs_components,modal_state,page_state,custom_upload,createBlockUI,createDateRangePicker} from "../../../global.js";



export var DashboardController = function (page,param) {

    const _request  = new RequestHandler();
    const _page = $('.dashboard_page');

    function loadTripMonitoring(formData=new FormData()){
        return new Promise((resolve, reject) => {

            _request.post('/tms/cco-b/planner/dashboard',formData)
            .then((res) => {
                if(res.status =='success'){
                    let data = JSON.parse(window.atob(res.payload));
                    _page.empty().html(data);
                    resolve(true);
                }
            })
            .catch((error) => {
                console.log(error)
                resolve(false);
                Alert.alert('error',"Something went wrong. Try again later", false);
            })
            .finally(() => {

            });
        })
    }

    function filterTripMonitoring(){
        let formData     = new FormData();
        let status_filter = _page.find('select[name="status_filter"]');
        let type_filter = _page.find('select[name="type_filter"]');
        let search = _page.find('input[name="search"]');

        let picker = $('.date_filter').data('daterangepicker');
        let startDate = picker.startDate.format("YYYY-MM-DD");
        let endDate = picker.endDate.format("YYYY-MM-DD");

        form.append('start_date',startDate)
        form.append('end_date',endDate)
        form.append('status_filter',status_filter.val())
        form.append('type_filter',type_filter.val())
        form.append('search',search.val())

        loadTripMonitoring(formData);
    }

    function loadScript(){
        $('.date_filter').on('apply.daterangepicker', function(ev, picker) {
            e.preventDefault()
            e.stopImmediatePropagation()
            filterTripMonitoring();
        });

        $('.select_filter').on('change',function(){
            e.preventDefault()
            e.stopImmediatePropagation()
            filterTripMonitoring();
        });

        $('.search').on('keydown',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()
            if (e.key === 'Enter' || e.keyCode === 13) {
                filterTripMonitoring();
            } else if (e.keyCode === 8 || e.key === 'Backspace') {
                setTimeout(() => {
                    filterTripMonitoring();
                }, 0);
            }
        });
    }

    return {
        init: function () {

            page_block.block();
            loadTripMonitoring().then((res) => {
                if(res){
                    setTimeout(() => { page_block.release(); },300);
                    createDateRangePicker('date_filter');
                    data_bs_components();
                    KTComponents.init();
                }
            }).finally(() => {
                loadScript()
            });

        }
    }
}
