jQuery.noConflict();
jQuery(document).ready(function($) {
  $('#loading').hide();
  $('#results').hide();
  $('#noresults').hide();
  
  var delay = (function() {
    var timer = 0;
    return function(callback, ms) {
      clearTimeout(timer);
      timer = setTimeout(callback, ms);
    };
  })();

  function fetchResults() {
    delay(function() {
      var query_string = $('#lookup-form').serialize();
      $.ajax({
        type: 'POST',
        url: '/flood2011/search.php',
        data: query_string,
        dataType: 'json',
        success: function(data) {
          $('.error>p').html('');

          if (data['status'] == 'success') {
            $('#results tbody tr').remove();
              
            $.each(data['response'], function(index, address) {
              var water_color = 'grey';
              var water_weight = 'normal';
                
                   if (address['water_depth'] > 10) { water_color = 'red';          water_weight = 'bolder'; }
              else if (address['water_depth'] > 5) {  water_color = 'orange';       water_weight = 'bold'; }
              else if (address['water_depth'] > 3) {  water_color = 'goldenrod';    water_weight = 'lighter'; }
              else if (address['water_depth'] > 1) {  water_color = 'midnightblue'; water_weight = 'lighter'; }
                
              $('#results table tbody').append('<tr>' + 
                '<td class="address">' + address['street_address'] + '</td>' +
                //'<td class="unit">' + (address['unit'] == null ? '' : address['unit']) + '</td>' + 
                '<td class="elevation" style="color:' + water_color + ';font-weight:' + water_weight + '">' + address['elevation'] + '</td>' + 
                '<td class="levee"' + (address['levee'] == 'YES' ? ' style="color:green;font-weight:bold"' : '') + '>' + address['levee'] + '</td>' +
                '<td class="waterDepth" style="color:' + water_color + ';font-weight:' + water_weight + '">' + address['water_depth'] + '</td>' +
                '</tr>');
              $('#results table tbody tr:even').addClass('even');  
            });
              
            if (data['response'].length < 1) {
              $('#results').hide();
              $('#noresults').show();
            }
            else {
              $('#results').show();
              $('#noresults').hide();
            }
            $('#loading').hide();
          }
          else {
            // Failed
            $('#results').hide();
          }
        }
      });
    }, 1000);
  }
  
  $('#county').change(function(e) {
    if ($('#address').val().length < 3) return;
    $('#loading').show();
    fetchResults();
  });
  
  $('#address').keyup(function(e) {
    if ($('#address').val().length < 3) return;
    $('#loading').show();
    fetchResults();
  });
    
  $('#elevation').keyup(function(e) {
    if ($('#elevation').val().length < 3) return;
    $('#loading').show();
    fetchResults();
  });
    
  $('#levee').change(function(e) {
    if ($('#address').val().length < 3) return;
    $('#loading').show();
    fetchResults();
  });
    
  $('#water_depth').keyup(function(e) {
    if ($('#water_depth').val().length < 3) return;
    $('#loading').show();
    fetchResults();
  });
  
  $('#toggle-more-options').click(function(e) {
    e.preventDefault();
    $('#more-options').toggle();
    $('#more-options-arrow').toggleClass('ui-icon-triangle-1-s');
  });
});

jQuery(window).load(function($) {
  jQuery('#advisories-placeholder').load('ajax.advisories.php');
  jQuery('#alerts-placeholder').load('ajax.alerts.php');
  /*
  jQuery('#twitter-placeholder').load('ajax.twitter.php', function(response, status, xhr) {
    if (status != "error") {
      jQuery('#twitter-tabs').tabs({
        event:'mouseover',
        fx: { opacity: 'toggle', duration: 'fast' }
      });
    }
  });
  jQuery('#cameras-placeholder').load('ajax.cameras.php', function(response, status, xhr) {
    if (status != "error") {
      jQuery('#camera-accordion').accordion({
        event:'mouseover',
        fx: { opacity: 'toggle', duration: 'slow' }
      });
      updateCameras();
    }
  });
  jQuery('#streams-placeholder').load('ajax.streams.php');
  jQuery('#resources-placeholder').load('ajax.resources.php');
  jQuery('#hydrograph-placeholder').load('ajax.hydrograph.php');
  */
});

function updateCameras() {
  jQuery('#camera-missouri-bsc').attr('src', 'http://165.234.56.97/jpg/image.jpg?' + new Date());
  jQuery('#camera-missouri-bsc').fadeIn('slow');
  jQuery('#camera-missouri-memorial').attr('src', 'http://165.234.108.23/jpg/image.jpg?' + new Date());
  jQuery('#camera-missouri-memorial').fadeIn('slow');
  jQuery('#camera-missouri-uom').attr('src', 'http://165.234.108.26/jpg/image.jpg?' + new Date());
  jQuery('#camera-missouri-uom').fadeIn('slow');
  jQuery('#camera-expressway').attr('src', 'http://24.230.95.202/axis-cgi/jpg/image.cgi?resolution=CIF&camera=1?' + new Date());
  jQuery('#camera-expressway').fadeIn('slow');
  jQuery('#camera-zoolevee').attr('src', 'http://24.230.95.202/axis-cgi/jpg/image.cgi?resolution=CIF&camera=3?' + new Date());
  jQuery('#camera-zoolevee').fadeIn('slow');
  
  setTimeout(updateCameras, 15000); // 20 seconds
}