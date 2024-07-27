"use strict";

export async function _dtDeviceList(tbl) {

    var KTUsersList = (function () {
    // var e, t, n, r, o = document.getElementById("kt_table_users"),
    //     c = () => {
    //         console.log('called')
    //         o.querySelectorAll('[data-kt-users-table-filter="delete_row"]').forEach(
    //         (t) => {
    //         t.addEventListener("click", function (t) {
    //             t.preventDefault();
    //             const n = t.target.closest("tr"),
    //             r = n.querySelectorAll("td")[1].querySelectorAll("a")[1].innerText;
    //             Swal.fire({
    //                 text: "Are you sure you want to delete " + r + "?",
    //                 icon: "warning",
    //                 showCancelButton: !0,
    //                 buttonsStyling: !1,
    //                 confirmButtonText: "Yes, delete!",
    //                 cancelButtonText: "No, cancel",
    //                 customClass: {
    //                     confirmButton: "btn fw-bold btn-danger",
    //                     cancelButton: "btn fw-bold btn-active-light-primary",
    //                 },
    //             }).then(function (t) {
    //             t.value
    //                 ? Swal.fire({
    //                     text: "You have deleted " + r + "!.",
    //                     icon: "success",
    //                     buttonsStyling: !1,
    //                     confirmButtonText: "Ok, got it!",
    //                     customClass: { confirmButton: "btn fw-bold btn-primary" },
    //                 }).then(function () {
    //                     e.row($(n)).remove().draw();
    //                 }).then(function () {
    //                     a();
    //                 })
    //                 :
    //                 t.dismiss === "cancel" &&
    //                 Swal.fire({
    //                     text: customerName + " was not deleted.",
    //                     icon: "error",
    //                     buttonsStyling: !1,
    //                     confirmButtonText: "Ok, got it!",
    //                     customClass: { confirmButton: "btn fw-bold btn-primary" },
    //                 });
    //             });
    //         });
    //         }
    //     );
    //     },
    //     l = () => {
    //     const c = o.querySelectorAll('[type="checkbox"]');
    //     (t = document.querySelector('[data-kt-user-table-toolbar="base"]')),
    //         (n = document.querySelector('[data-kt-user-table-toolbar="selected"]')),
    //         (r = document.querySelector(
    //         '[data-kt-user-table-select="selected_count"]'
    //         ));
    //     const s = document.querySelector('[data-kt-user-table-select="delete_selected"]');
    //     c.forEach((e) => {
    //         e.addEventListener("click", function () {
    //         setTimeout(function () {
    //             a();
    //         }, 50);
    //         });
    //     }),
    //         s.addEventListener("click", function () {
    //         Swal.fire({
    //             text: "Are you sure you want to delete selected customers?",
    //             icon: "warning",
    //             showCancelButton: !0,
    //             buttonsStyling: !1,
    //             confirmButtonText: "Yes, delete!",
    //             cancelButtonText: "No, cancel",
    //             customClass: {
    //             confirmButton: "btn fw-bold btn-danger",
    //             cancelButton: "btn fw-bold btn-active-light-primary",
    //             },
    //         }).then(function (t) {
    //             t.value
    //             ? Swal.fire({
    //                 text: "You have deleted all selected customers!.",
    //                 icon: "success",
    //                 buttonsStyling: !1,
    //                 confirmButtonText: "Ok, got it!",
    //                 customClass: { confirmButton: "btn fw-bold btn-primary" },
    //                 })
    //                 .then(function () {
    //                     c.forEach((t) => {
    //                     t.checked &&
    //                         e
    //                         .row($(t.closest("tbody tr")))
    //                         .remove()
    //                         .draw();
    //                     });
    //                     o.querySelectorAll('[type="checkbox"]')[0].checked = !1;
    //                 })
    //                 .then(function () {
    //                     a(), l();
    //                 })
    //             : "cancel" === t.dismiss &&
    //                 Swal.fire({
    //                 text: "Selected customers was not deleted.",
    //                 icon: "error",
    //                 buttonsStyling: !1,
    //                 confirmButtonText: "Ok, got it!",
    //                 customClass: { confirmButton: "btn fw-bold btn-primary" },
    //                 });
    //         });
    //         });
    //     };
    // const a = () => {
    //     const e = o.querySelectorAll('tbody [type="checkbox"]');
    //     let c = !1,
    //     l = 0;
    //     e.forEach((e) => {
    //     e.checked && ((c = !0), l++);
    //     }),
    //     c
    //         ? ((r.innerHTML = l),
    //         t.classList.add("d-none"),
    //         n.classList.remove("d-none"))
    //         : (t.classList.remove("d-none"), n.classList.add("d-none"));
    // };

    const dt_deviceList = $(tbl).DataTable({
        responsive: true,
        searchDelay: 500,
        processing: true,
        serverSide: true,
        ajax:{
            url: "dt-device-list",
            type: "POST",
            data: function (d){
                // d.filter_condition = $('.filter_eq_status').val()
                // d.filter_eq_type = $('.filter_eq_type').val()
            },
        },
        columns: [
        ],
        columnDefs: [
        ],
    });
    
    const _filters = () => {
        const selectValues = [], inputValues = [];
        return {
            select(){
                document.querySelectorAll('select').forEach(select => {
                    selectValues.push(select.value);
                    const value = select.value.trim();
                    if (value !== '') {
                        selectValues.push(value);
                    }
                });
                return selectValues;
            },
            input(){
                document.querySelectorAll('input[type="text"]').forEach(input => {
                    const value = input.value.trim();
                    if (value !== '') {
                        inputValues.push(value);
                    }
                });
                return inputValues;
            }

        }
    };

    $('body').delegate('.search-0002-0001','keyup',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        dt_deviceList.search($(this).val()).draw();
    });

    $('body').delegate('.btn-0002-0003','click',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        // FILTER

    });

    // const s = document.querySelector('[data-kt-user-table-select="delete_selected"]');
    // const c = o.querySelectorAll('[type="checkbox"]');
    //     (t = document.querySelector('[data-kt-user-table-toolbar="base"]')),
    //     (n = document.querySelector('[data-kt-user-table-toolbar="selected"]')),
    //     (r = document.querySelector('[data-kt-user-table-select="selected_count"]'));

    // c.forEach((e) => {
    //     e.addEventListener("click", function () {
    //         setTimeout(function () {
    //             // a();
    //             console.log('clicked')
    //         }, 50);
    //     });
    // })

    return {
        init: function () {
        // o &&
        //     (o.querySelectorAll("tbody tr").forEach((e) => {
        //     const t = e.querySelectorAll("td"),
        //         n = t[3].innerText.toLowerCase();
        //     let r = 0, o = "minutes";
        //     n.includes("yesterday")
        //         ? ((r = 1), (o = "days"))
        //         : n.includes("mins")
        //         ? ((r = parseInt(n.replace(/\D/g, ""))), (o = "minutes"))
        //         : n.includes("hours")
        //         ? ((r = parseInt(n.replace(/\D/g, ""))), (o = "hours"))
        //         : n.includes("days")
        //         ? ((r = parseInt(n.replace(/\D/g, ""))), (o = "days"))
        //         : n.includes("weeks") &&
        //         ((r = parseInt(n.replace(/\D/g, ""))), (o = "weeks"));
        //     const c = moment().subtract(r, o).format();
        //     t[3].setAttribute("data-order", c);
        //     const l = moment(t[5].innerHTML, "DD MMM YYYY, LT").format();
        //     t[5].setAttribute("data-order", l);

        //     }),
        //     (e = $(o).DataTable({
        //     info: !1,
        //     order: [],
        //     pageLength: 10,
        //     lengthChange: !1,
        //     columnDefs: [
        //         { orderable: !1, targets: 0 },
        //         { orderable: !1, targets: 6 },
        //     ],
        //     })).on("draw", function () {
        //         l(), c(), a();
        //     }),
        //     l(),
        //     document.addEventListener("keyup", function (t) {
        //         e.search(t.target.value).draw();
        //     }),
        //     document.querySelector('[data-kt-user-table-filter="reset"]').addEventListener("click", function () {
        //         document.querySelector('[data-kt-user-table-filter="form"]').querySelectorAll("select").forEach((e) => {
        //             $(e).val("").trigger("change");
        //         }),
        //         e.search("").draw();
        //     }),
        //     c(),
        //     (() => {
            // const t = document.querySelector('[data-kt-user-table-filter="form"]'),
            //     n = t.querySelector('[data-kt-user-table-filter="filter"]'),
            //     r = t.querySelectorAll("select");
            // n.addEventListener("click", function () {
            //     var t = "";
            //     r.forEach((e, n) => {
            //     e.value &&
            //         "" !== e.value &&
            //         (0 !== n && (t += " "), (t += e.value));
            //     }),
            //     e.search(t).draw();
            // });
        //     })());
        },
    };
    })();

    KTUtil.onDOMContentLoaded(function () {
    KTUsersList.init();
    });
}
