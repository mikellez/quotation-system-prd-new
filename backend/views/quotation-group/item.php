<?php 
use yii\helpers\Html;
//use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Modal;
use yii\widgets\Pjax;

//use kartik\grid\GridView;
use backend\components\Alert;

?>

<?php
    Modal::begin([
        'title' => 'Quotations',
        'id' => 'modal',
        'size' => 'modal-xl',
    ]);

    echo "<div id='modalContent'><div class='spinner-border' role='status'> <span class='sr-only'>Loading...</span> </div></div>";

    Modal::end();

?>

<?php
Pjax::begin( [
    'id' => 'pjax-quotation-grid',
    'timeout' => 5000, 
    'enablePushState' => false, 
    'clientOptions' => ['method' => 'POST']]
);?>

<h3><?=$quotationModel->doc_name;?></h3>

<?php 
echo $this->render('product', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
    'quotationModel' => $quotationModel,
]);

Pjax::end();

$id = Yii::$app->request->getBodyParam('id');

$js = <<<JS
    /*$(document).on('click', '#generate-quotation-btn', function(){
        swal("Are you sure?","You will be generating quotation..","warning")
        .then(okay => {
            if (okay) {

                $.ajax({
                    url: 'generate?id=$id',
                    type: 'post',
                    success: function(data) {
                        if(data.success) {
                            swal("Success..","Quotation generated, ref no: "+data.model.doc_no,"success")
                            .then(okay => {
                                if (okay) {
                                    window.location.href = 'index';
                                    window.open('document?id='+data.model.id, '_blank');
                                }
                            });
                        } else {
                            swal("Oops..","Quotation is over the discount threshold! Please wait for the admin to approve. Ref No: "+data.model.doc_no,"error")
                            .then(okay => {
                                if (okay) {
                                    window.location.href = "index";
                                }
                            });
                        }
                    }
                });
            }
        });
    });*/
    $(document).on('click', '#generate-quotation-btn', function(){
        swal({
            title: 'Are you sure?',
            html: "You will be generating quotation, please allow pop-ups for this site. For more information, visit this <a href='https://support.google.com/chrome/answer/95472?hl=en&co=GENIE.Platform%3DDesktop'>link.</a>",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                const content = swal.getContainer()
                if (content) {
                    const swalTitle = content.querySelector('.swal2-title');
                    const swalContent = content.querySelector('.swal2-content');
                    const swalCancel = content.querySelector('.swal2-cancel');

                    if (swalTitle) {
                        swalTitle.textContent = 'Please wait..';
                    }
                    if (swalContent) {
                        swalContent.textContent = 'Do not close this modal, system is generating quotation..';
                    }
                    if (swalCancel) {
                        swalCancel.remove();
                    }
                }
                return fetch('generate?id=$id')
                .then(response => {
                    console.log(response);
                    if (!response.ok) {
                        throw new Error(response.statusText)
                    }
                    return response.json()
                })
            },
            allowOutsideClick: () => !swal.isLoading()
            }).then((response) => {
                console.log(response);
            if (response.value.success) {
                swal("Success..","Quotation generated, ref no: "+response.value.model.doc_no,"success")
                .then(okay => {
                    if (okay) {
                        window.location.href = 'index';
                        window.open('document?id='+response.value.model.id, '_blank');
                    }
                });
            } else {
                swal("Oops..","Quotation is over the discount threshold! Please wait for the admin to approve. Ref No: "+response.value.model.doc_no,"error")
                .then(okay => {
                    if (okay) {
                        window.location.href = "index";
                    }
                });
            }
        })
        /*swal("Are you sure?","You will be generating quotation..","warning")
        .then(function() {
            console.log(this.swal);
        })
        .then(okay => {
            if (okay) {
                var keys = $('#product-grid').yiiGridView('getSelectedRows');
                if(keys=="") {
                    swal("Oops..","Please select products before saving!","error");
                    return false;
                }

                $.ajax({
                    url: 'generate?id=$id',
                    type: 'post',
                    success: function(data) {
                        if(data.success) {
                            swal("Success..","Quotation generated, ref no: "+data.model.doc_no,"success")
                            .then(okay => {
                                if (okay) {
                                    window.location.href = 'index';
                                    window.open('document?id='+data.model.id, '_blank');
                                }
                            });
                        } else {
                            swal("Oops..","Quotation is over the discount threshold! Please wait for the admin to approve. Ref No: "+data.model.doc_no,"error")
                            .then(okay => {
                                if (okay) {
                                    window.location.href = "index";
                                }
                            });
                        }
                    }
                });
            }
        });*/
        //var keys = $('#product-grid').yiiGridView('getSelectedRows');
    });

    $('.pjax-delete-link').on('click', function(e) {
        e.preventDefault();
        var deleteUrl = $(this).attr('delete-url');
        var pjaxContainer = $(this).attr('pjax-container');
        var result = confirm('Delete this item, are you sure?');                                
        if(result) {
            $.ajax({
                url: deleteUrl,
                type: 'post',
                error: function(xhr, status, error) {
                    alert('There was an error with your request.' + xhr.responseText);
                }
            }).done(function(data) {
                $('#quotation-grid-container').css('opacity', '0.5');
                $('#quotation-grid-container').prepend('<div class=\"spinner-border text-dark\" style=\"position:absolute;left:50%;top:50%;\"></div>')
                $.pjax.reload({'container': '#' + pjaxContainer, 'timeout': 5000});
            });
        }
    });

JS;

$this->registerJs($js, $this::POS_END);
?>