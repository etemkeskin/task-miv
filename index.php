<?php 
    require __DIR__.'/vendor/autoload.php'
?>
<!doctype html>
<html lang="tr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <title>Mivento Assessment</title>

    <style>
      .container {
        margin-top: 2rem !important;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-5">
          <div class="alert alert-danger" id="alert-danger" style="display: none;"></div>
          <div class="alert alert-success" id="alert-success" style="display: none;"></div>
          <form class="needs-validation" id="upload-csv-form" method="post" action="upload-csv.php" name="upload_csv" enctype="multipart/form-data" novalidate>
            <div class="mb-3">
              <label for="campaign-name" class="form-label">Kampanya Adı</label>
              <input type="text" class="form-control" id="campaign-name" required name="campaign_name"/>
            </div>
            <div class="mb-3">
              <select class="form-select" required name="campaign_date">
                <option selected disabled value=""  >Tarih Seçin</option>
                <option value="2022-07">Temmuz 2022</option>
                <option value="2022-08">Ağustos 2022</option>
                <option value="2022-09">Eylül 2022</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="campaign-file" class="form-label">Dosya Yükleyin</label>
              <input class="form-control" type="file" id="campaign-file" required  name="campaign_file"/>
            </div>
            <div class="d-grid">
              <button class="btn btn-primary btn-block" type="submit" id="btnSubmit">Yükle</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Example starter JavaScript for disabling form submissions if there are invalid fields -->
    <script>
      (function () {
        'use strict';

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation');

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
          .forEach(function (form) {
            form.addEventListener('submit', function (event) {
              if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
              }

              form.classList.add('was-validated');
            }, false);
          });
      })();
    </script>
    <script>
        $(document).ready(function (e) {
            $("#upload-csv-form").on('submit',(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "upload-csv.php",
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend : function()
                    {
                        $("#btnSubmit").attr("disabled", true);
                        $("#err").fadeOut();
                    },
                    success: function(data)
                    {
                        const obj = JSON.parse(data);

                        if (obj.success) {
                            let alertSuccess = $('#alert-success').show();
                            alertSuccess.empty();
                            alertSuccess.show();
                            alertSuccess.append(obj.message);

                            if (typeof obj.errors !== 'undefined' && obj.errors.length > 0) {
                                let alertDanger = $('#alert-danger').show();
                                alertDanger.empty();
                                alertDanger.show();
                                alertDanger.append(obj.message);

                                let txt = "";
                                let errors = obj.errors;
                                for (let x in errors ) {
                                    txt += "<div> *" + errors[x] + "</div>";
                                }
                                alertDanger.append(txt);
                            }

                        }else{
                            let alertSuccess = $('#alert-success').hide();
                            let alertDanger = $('#alert-danger').show();
                            alertDanger.empty();
                            alertDanger.show();
                            alertDanger.append(obj.message+"<br>");

                            let txt = "";
                            let errors = obj.errors;
                            for (let x in errors ) {
                                txt += "<div> *" + errors[x] + "</div>";                          
                            }
                            alertDanger.append(txt);
                            alertDanger.append(obj.error+"<br>");
                            
                        }
                        
                        // view uploaded file.
                        $("#preview").html(data).fadeIn();
                        $("#upload-csv-form")[0].reset(); 
                        
                        $('#btnSubmit').attr("disabled", false);
                    },
                    error: function(e) 
                    {
                        $("#err").html(e).fadeIn();
                        $('#btnSubmit').attr("disabled", false);
                    }          
                });
            }));
        });
    </script>
  </body>
</html>

