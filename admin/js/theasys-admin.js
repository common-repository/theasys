/**
 * @link       theasys.io
 * @since      1.0.0
 *
 * @package    Theasys
 * @subpackage Theasys/admin/js
 */
(function( $ ) {
  'use strict';

  $(function() {

    if( pagenow && ( pagenow==='post' || pagenow==='page' ) ){

      var theasys_cached_results = {};

      var theasys_api_key = $('#theasys_api_key').val();
      var theasys_api_url = $('#theasys_api_url').val();
      var theasys_website_url = $('#theasys_website_url').val();
      var theasys_loading_img = $('#theasys_loading_img').val();
      var theasys_logo_img = $('#theasys_logo_img').val();
      var theasys_search_results = $('#theasys_search_results');

      $('#theasys_embed_dialog').dialog({
        title: 'Theasys: Virtual Tour Embed Shortcode',
        dialogClass: 'wp-dialog',
        autoOpen: false,
        draggable: false,
        width: 'auto',
        modal: true,
        resizable: false,
        closeOnEscape: true,
        position: {
          my: "center",
          at: "center",
          of: window
        },
        open: function () {
          $('.ui-widget-overlay').on('click', function(){
            $(this).dialog('close');
          });
        },
        close: function () {
          $('#custom_width_height_inputs_wrapper').addClass('hidden');
          $('#custom_width_height_inputs_wrapper input').val(0);
          $('#embed_size_select').val(0);
        },
        create: function () {
          var parent = $(this).parent();
          parent.find('.ui-dialog-titlebar-close').addClass('ui-button');
          parent.find("span.ui-dialog-title").prepend('<img src="'+theasys_logo_img+'" alt="Theasys logo">&nbsp;');
        },
      });

      $('#theasys_embed_preview_dialog').dialog({
        title: 'Theasys: Preview Virtual Tour Embed',
        dialogClass: 'wp-dialog',
        autoOpen: false,
        draggable: false,
        width: 'auto',
        modal: true,
        resizable: false,
        closeOnEscape: true,
        position: {
          my: "center",
          at: "center",
          of: window
        },
        open: function () {
          $('.ui-widget-overlay').on('click', function(){
            $('this').dialog('close');
          });
        },
        close: function () {

        },
        create: function () {
          var parent = $(this).parent();
          parent.find('.ui-dialog-titlebar-close').addClass('ui-button');
          parent.find("span.ui-dialog-title").prepend('<img src="'+theasys_logo_img+'" alt="Theasys logo">&nbsp;');
          parent.find("span.ui-dialog-title").append('<span id="theasys_embed_preview_dialog_dimensions">sss</span>');
        },
      });

      $('#theasys_embed_dialog').on('input','#custom_width_height_inputs_wrapper input',function(){

        var key = $('#theasys_embed_dialog').data('key');

        var jthis = $(this);
        var type = jthis.data('type');

        var width = 0;
        var height = 0;

        if(type === 'w'){

           width = jthis.val();
           height = jthis.parent().find('input[data-type="h"]').val();

        } else if(type === 'h') {

           width = jthis.parent().find('input[data-type="w"]').val();
           height = jthis.val();

        }

        get_embed_code({

          width : width,
          height : height,
          key : key

        });

      });

      $('#theasys_embed_dialog').on('change','#embed_size_select',function(){

        var key = $('#theasys_embed_dialog').data('key');

        var jthis = $(this);
        var selected_option = jthis.find(':selected');

        var width = selected_option.data('width');
        var height = selected_option.data('height');
        var value = jthis.val();

        if(value==='c'){

          $('#custom_width_height_inputs_wrapper').removeClass('hidden');

        } else {

          $('#custom_width_height_inputs_wrapper').addClass('hidden');

        }

        get_embed_code({

          width : width,
          height : height,
          key : key

        });

      });

      $('#theasys_search_input').on('keypress',function(e){

        if( e.which === 13 ){

          e.preventDefault();

          $('#theasys_search_button').trigger('click');

        }

      });

      $('#theasys_search_button').on('click',function(e){

        e.preventDefault();

        var theasys_search_input = $('#theasys_search_input');

        var term = theasys_search_input.val();
        term = term.trim();

        if( term === '' ){

          theasys_search_input.focus();

          return false;

        }

        if( term in theasys_cached_results ){

          return false;

        }

        theasys_search_results.html('<img src="'+theasys_loading_img+'" alt="loading"> Loading');

        $.ajax({

          url : theasys_api_url+'virtual_tours',
          crossDomain: true,
          cache: false,
          type : 'GET',
          dataType: 'jsonp',
          data: {
            call : 'virtual_tours',
            params : { term : term },
            cors : 1,
            authorization : theasys_api_key,
          },
        })
        .done(function(resp) {

          display_theasys_search_results(resp);

        })
        .fail(function(error,a,statusText,b) {

          theasys_ajax_error_response(theasys_search_results,error,statusText);

        });

        return false;

      });

      $('#theasys_search_results').on('click','.theasys_tour_title',function(e){

        e.preventDefault();

        var jthis = $(this);

        var theasys_tour_embeds = jthis.parent().find('.theasys_tour_embeds');

        theasys_tour_embeds.toggleClass('hidden');

        if( !theasys_tour_embeds.hasClass('hidden') ){

          var li =  jthis.closest('li');
          var id = li.data('id');

          theasys_tour_embeds.html('<img src="'+theasys_loading_img+'" alt="loading"> Loading');

          $.ajax({
            url : theasys_api_url,
            crossDomain: true,
            cache: false,
            type : 'GET',
            dataType: 'jsonp',
            data: {
              call : 'virtual_tours',
              action : 'embeds',
              params : { id : id },
              cors : 1,
              authorization : theasys_api_key,
            },
          })
          .done(function(resp) {

            theasys_tour_embeds.html(display_theasys_embed_results(resp));

          })
          .fail(function(error,a,statusText,b) {

            theasys_ajax_error_response(theasys_tour_embeds,error,statusText);

          });

        }

        return false;

      });

      $('#theasys_search_results').on('click','.theasys_tour_embeds span',function(e){

        e.preventDefault();

        var jthis = $(this);

        var li = jthis.closest('li');
        var li_li = li.closest('div').parent();

        var key_embed = li.data('rnd');
        var key = li_li.data('rnd');

        preview_embed(key+key_embed);

        $('#theasys_embed_preview_dialog').data('key',key+key_embed).dialog('open');

        return false;

      });

      $('#theasys_search_results').on('click','.theasys_tour_embed_name',function(e){

        e.preventDefault();

        var jthis = $(this);
        var li = jthis.closest('li');
        var li_li = li.closest('div').parent();

        var key_embed = li.data('rnd');
        var key = li_li.data('rnd');

        $('#embed_code_preview').data('key',key).data('key_embed',key_embed);

        get_embed_code({key:key+key_embed});

        $('#theasys_embed_dialog').data('key',key+key_embed).dialog('open');

        return false;

      });

      $('#embed_code_preview').on('click',function(e){

        e.preventDefault();

        var jthis = $(this);

        var key_embed = jthis.data('key_embed');
        var key = jthis.data('key');

        preview_embed(key+key_embed);

        $('#theasys_embed_preview_dialog').data('key',key+key_embed).dialog('open');

        return false;

      });

      function display_theasys_search_results(obj){

        if( !('data' in obj) ) {

          return false;

        }

        if( !('entries' in obj.data) ) {

          return false;

        }

        var html = '<ul>';

        var hasRows = false;

        for( var i in obj.data.entries ){

          html += '<li data-id="'+obj.data.entries[i].id+'" data-rnd="'+obj.data.entries[i].rnd+'"><a class="theasys_tour_title" href="#theasys_tour_title">'+obj.data.entries[i].title+'</a><div class="theasys_tour_embeds hidden" style=""></div></li>';

          hasRows = true;

        }

        html += '</ul>';

        if( !hasRows ){

          html = 'No tours found.';

        }

        $('#theasys_search_results').html(html);

      }

      function display_theasys_embed_results(obj){

        if( !('data' in obj) ) {

          return false;

        }

        if( !('entries' in obj.data) ) {

          return false;

        }

        var html = '<strong>Embeds:</strong><ol>';

        var hasRows = false;

        for( var i in obj.data.entries ){

          html += '<li data-id="'+obj.data.entries[i].id+'" data-rnd="'+obj.data.entries[i].rnd+'"><a class="theasys_tour_embed_name" href="#theasys_tour_embed_title">'+obj.data.entries[i].name+'</a><span title="Preview"></span></li>';

          hasRows = true;

        }

        html += '</ol>';

        if( !hasRows ){

          html = 'No embeds for this tour.';

        }

        return html;

      }

      function get_embed_code(obj){

        var d = {

          width : 560,
          height : 315,
          key : '',

        };

        Object.assign(d,obj);

        if( d.key === '' ){

          $('#embed_code_textarea').html('Wrong embed code. No key supplied.');

        }

        var data = [];
        var extra_data = '';

        var width = parseFloat( d.width, 10 );
        var height = parseFloat( d.height, 10 );
        var style = '';

        if( width > 0 || height > 0 ){

          if( width > 0 ) data.push('width="'+width+'"');

          if( height > 0 ) data.push('height="'+height+'"');

        }

        if( data.length ){

          extra_data = ' ' + data.join(' ');

        }

        $('#embed_code_textarea').html('[theasys_embed key="'+d.key+'"'+extra_data+']');

      }

      function preview_embed(key){

        var d = {

          width : 560,
          height : 315,
          key : key,

        };

        var embed_code_preview_wrapper = $('#theasys_embed_preview_dialog').find('.embed_code_preview_wrapper');

        if( d.key === '' ){

          embed_code_preview_wrapper.html('Wrong embed code. No key supplied.');

        }

        var embed_size_select = $('#embed_size_select');
        var selected_option = embed_size_select.find(':selected');

        d.width = selected_option.data('width');
        d.height = selected_option.data('height');
        var value = embed_size_select.val();

        var theasys_embed_preview_dialog = $('#theasys_embed_preview_dialog');

        var dimensions_text = '['+d.width+'x'+d.height+']';

        theasys_embed_preview_dialog.removeClass('no-padding');

        if(value==='c'){

          d.width = $('#custom_width_height_inputs_wrapper').find('input[data-type="w"]').val();
          d.height = $('#custom_width_height_inputs_wrapper').find('input[data-type="h"]').val();

          dimensions_text = '['+d.width+'x'+d.height+']';

          theasys_embed_preview_dialog.dialog( "option", "width", 'auto'  );
          theasys_embed_preview_dialog.dialog( "option", "height", 'auto' );

        } else if(value==='r') {

          dimensions_text = '[Responsive]';

          theasys_embed_preview_dialog.dialog( "option", "width", $(window).width() - ((10 * $(window).width() ) / 100)  );
          theasys_embed_preview_dialog.dialog( "option", "height", $(window).height() - ((10 * $(window).height() ) / 100) );

          theasys_embed_preview_dialog.addClass('no-padding');

        } else {

          theasys_embed_preview_dialog.dialog( "option", "width", 'auto'  );
          theasys_embed_preview_dialog.dialog( "option", "height", 'auto' );

        }

        var theasys_embed_js = $('#theasys_embed_js').val();

        var params = '';

        if( d.width > 0 ){

          params += ' data-width="'+d.width+'" ';

        }

        if( d.height > 0 ){

          params += ' data-height="'+d.height+'" ';

        }

        $('#theasys_embed_preview_dialog_dimensions').html(' '+dimensions_text);

        var html = '<script async src="'+theasys_embed_js+'" data-theasys="'+d.key+'"'+params+'></script>';

        embed_code_preview_wrapper.html(html);

      }

      function theasys_ajax_error_response(elem,error,statusText){

        if( statusText === 'Unauthorized' ){

          if( error.responseText === 'Expired token' ){

            elem.html('Error: '+error.responseText+'. <br>Your API Key has expired. Please, login to your dashboard in <a target="_blank" rel="noopener" href="'+theasys_website_url+'">Theasys website</a> and generate a new key. Then replace it in Theasys plugin settings page.');

          } else {

            elem.html('Error: '+error.responseText+'.');

          }

        } else {

          elem.html('Error: '+error.statusText);

        }

      }

    }

    if( pagenow && pagenow==='theasys_page_theasys_settings' ){

      var theasys_api_key = $('#theasys_api_key').val();
      var theasys_api_url = $('#theasys_api_url').val();
      var theasys_website_url = $('#theasys_website_url').val();

      var theasys_test_api_key_response = $('#theasys_test_api_key_response');
      theasys_test_api_key_response.html('');

      $('#theasys_test_api_key').on('click',function(e){

        e.preventDefault();

        var theasys_test_api_key_loading = $('#theasys_test_api_key_loading');

        theasys_test_api_key_loading.removeClass('hidden');

        $.ajax({
          url : theasys_api_url,
          crossDomain: true,
          cache: false,
          type : 'GET',
          dataType: 'jsonp',
          data: {
            call : 'status',
            action : 'check_key',
            params : {},
            authorization : theasys_api_key,
          },
        })
        .done(function(resp,a,b) {

         if( b.status === 200 ){

          theasys_test_api_key_response.html('API Key is valid!');

         }

         theasys_test_api_key_loading.addClass('hidden');

        })
        .fail(function(error,a,statusText,b) {

          theasys_ajax_error_response($('#theasys_test_api_key_response'),error,statusText);

          theasys_test_api_key_loading.addClass('hidden');

        });

        return false;

      });

      function theasys_ajax_error_response(elem,error,statusText){

        if( statusText === 'Unauthorized' ){

          if( error.responseText === 'Expired token' ){

            elem.html('Error: '+error.responseText+'. <br>Your API Key has expired. Please, login to your dashboard in <a target="_blank" rel="noopener" href="'+theasys_website_url+'">Theasys website</a> and generate a new key.');

          } else {

            elem.html('Error: '+error.responseText+'.');

          }

        } else {

          elem.html('Error: '+error.statusText);

        }

      }

    }

  });

})( jQuery );