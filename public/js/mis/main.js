'use strict';

import {page_content} from './pg_controller/pg_content.js';
import {gs_sessionStorage,gs_getItem} from '../global.js';

var appHandler = function(){
    Array.from($("a[data-menu=main-menu],a[data-menu=sub-menu]")).forEach(function(element){
        if(element.getAttribute('data-url')){
            element.addEventListener("click", function(e){
                e.preventDefault();
                page_content(element.getAttribute('data-url'));
                gs_sessionStorage('mis-nav-link',element.getAttribute('href'));
                navbar_active(element.getAttribute('data-url'),element.getAttribute('data-menu'));
        })
      }
    });

    var navbar_active = function(pg,type){
        if(type=="main-menu"){
          $('.menu-item').removeClass('menu-item-active menu-item-open');
          $('a[data-url='+pg+']').parent().addClass('menu-item-active');
        }else{
          $(".menu-item").removeClass('here');
          $('a[data-url='+pg+']').parent().addClass('here');
          $('a[data-url='+pg+']').parent().parent().parent().addClass('here');
        }
    }

    var urlParams = async function(url){
        var params = {};
        var parser = document.createElement('a');
        parser.href = url;
        var query = parser.search.substring(1);
        var vars = query.split('&');
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split('=');
            params[pair[0]] = decodeURIComponent(pair[1]);
        }
        return params;
    }

    var urlPage =  async function (url){
        let check =  await urlParams(window.location.href), page  = "";
        if(url.split('/')[2] == 'mis')
        {
            page = url.split('/')[3];
            await page_content(page,check.view);
        }
        navbar_active(page,$('a[data-url='+page.replace('#','')+']').attr('data-menu'));
    };

    return {
        init: function(){
            urlPage(window.location.pathname);
        }
    };

}();

$(document).ready(function(){
    appHandler.init();
});
