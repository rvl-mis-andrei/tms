"use strict";

export async function _dtVideoList() {
    var _dtVideoList = (function () {

        let a = $('#dt-video-list');

        $.fn.dataTable.Api.register("column().title()", function () {
            return $(this.header()).text().trim();
        });

        $.fn.dataTable.ext.errMode = "throw";
        a.DataTable().clear().destroy();

        return {
            init: function () {
                a.DataTable({
                    responsive: true,
                    searchDelay: 500,
                    processing: true,
                    serverSide: true,
                    ajax:{
                        url: "dt-video-list",
                        type: "POST",
                        data: function (d){
                            d.status = $('#status').val()
                            d.dept = $('#dept').val()
                        },
                    },
                    columns:[
                    ],
                    columnDefs:[
                    ],
                }),
                document.getElementById('search-0002-0001').addEventListener("keyup", ({ target: { value } }) => a.search(value).draw()),
                document.getElementById('filter-0002-0001').addEventListener("change", ({ target: { value } }) => a.draw())
            },
        };
    })();

    KTUtil.onDOMContentLoaded(function () {
        _dtVideoList.init();
    });
}
