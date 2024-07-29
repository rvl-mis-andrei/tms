'use strict';

import {page_state,construct_url} from "../global.js";

export class DataTableHelper {

    constructor() {
        this.tableId = 'client_list_table';
        this.wrapper = 'client_list_wrapper';
        this.table = null;
    }

    initTable(url, parameters, columns, columnDefs, orderByColumn) {
        $.fn.dataTable.Api.register('column().title()', function() {
            return $(this.header()).text().trim();
        });
        $.fn.dataTable.ext.errMode = 'throw';
        let container = $(`#${this.wrapper}`);
        // drawTable(`${this.wrapper}`, container);
        this.table = $(`#${this.tableId}`).DataTable({
            responsive: true,
            responsiveMode: 'collapsed',
            processing: true,
            serverSide: true,
            // order: [[orderByColumn, 'desc']],
            ajax: {
                url: construct_url(`${url}`),
                type: "POST",
                data: function (d) {
                    if (parameters != null) {
                        let keys = Object.keys(parameters);

                        for (let i = 0; i < keys.length; i++) {
                            let key = keys[i];
                            let value = parameters[key];
                            d[key] = value;
                        }
                    }
                },
                // success: function(r){
                //     console.log(r)
                // }
            },
            columns: columns,
            destroy: true,
            // order: [[0, "asc"]],
            searchDelay: 500,
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i>',
                search: "_INPUT_",
                searchPlaceholder: "Search...",
            }, // change the search
            initComplete: function (settings, json) {
                if (settings._iRecordsTotal == 0) {
                    page_state(
                        container,
                        "empty",
                        null,
                        "No collection made yet!"
                    );
                }
                var ths = $(this.api().table().header()).find("th");
                ths.addClass(  "text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0" );
            },
        });
        this.table.on('draw', function () {
            KTMenu.createInstances();
        });
        if (columnDefs) {
            this.table.columnDefs().push(columnDefs);
            this.table.draw();
        }
    }

    search(term) {
        this.table.search(term).draw();
    }
}
