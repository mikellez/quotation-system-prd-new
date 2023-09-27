$(function() {
    $(document).on('show.bs.modal', '.modal', function() {
      const zIndex = 1040 + 20 * $('.modal:visible').length;
      $(this).css('z-index', zIndex);
      setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack'));
    });

    $(document).on('click', '.showModalButton', function(){
        $('#modal').find('#modalContent')
                .html('<div class="spinner-border text-dark"></div>');

        if($(this).attr("datatitle")) {
          $('#modal').find('.modal-title')
            .text($(this).attr('datatitle'));
        }

        /*$.post( $(this).attr('value'), function( data ) {
            $('#modal').find('#modalContent')
                    .html(data);
          });*/

        $.ajax({
            url: $(this).attr('value'),
            method: 'POST',
            dataType: 'html',
            success: function(data) {
                $('#modal').find('#modalContent')
                        .html(data);

            }
        });

        /*$('#modal').find('#modalContent')
                .load($(this).attr('value'));*/
    });

    var salesChartCanvas = $('#salesChart').get(0).getContext('2d')

    var salesChartData = {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label: 'Digital Goods',
          backgroundColor: 'rgba(60,141,188,0.9)',
          borderColor: 'rgba(60,141,188,0.8)',
          pointRadius: false,
          pointColor: '#3b8bba',
          pointStrokeColor: 'rgba(60,141,188,1)',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data: [28, 48, 40, 19, 86, 27, 90]
        },
        {
          label: 'Electronics',
          backgroundColor: 'rgba(210, 214, 222, 1)',
          borderColor: 'rgba(210, 214, 222, 1)',
          pointRadius: false,
          pointColor: 'rgba(210, 214, 222, 1)',
          pointStrokeColor: '#c1c7d1',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data: [65, 59, 80, 81, 56, 55, 40]
        }
      ]
    }
  
    var salesChartOptions = {
      maintainAspectRatio: false,
      responsive: true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines: {
            display: false
          }
        }],
        yAxes: [{
          gridLines: {
            display: false
          }
        }]
      }
    }
  
    // This will get the first returned node in the jQuery collection.
    // eslint-disable-next-line no-unused-vars
    var salesChart = new Chart(salesChartCanvas, {
        type: 'line',
        data: salesChartData,
        options: salesChartOptions
        }
    )

    $('table').footable({
      "on": {
        "postdraw.ft.table": function(e, ft) {
          ft.$el.find('[colspan!=""][colspan]').each(function(index, element) {
            var colspan = $(element).attr('colspan');
            $(element).nextAll('td:empty').slice(0, colspan).remove();
          });
        }
      }
    });

});