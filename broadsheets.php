<?php
require_once('bootstrap.php');

use Src\Controller\AdminController;

$expose = new AdminController();
require_once('inc/page-data.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?= require_once("inc/head.php") ?>
</head>

<body>
  <?= require_once("inc/header.php") ?>

  <?= require_once("inc/sidebar.php") ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Broadsheets</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Broadsheets</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Recent Sales -->
        <div class="col-12">

          <div class="card recent-sales overflow-auto">

            <div class="filter">
              <a class="icon" href="javascript:void()" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="View & Download Broadsheets">
                <i class="bi bi-download"></i>
              </a>
            </div>

            <div class="card-body">
              <h5 class="card-title">Broadsheets</h5>
              <form action="" class="mb-4">
                <div class="row">
                  <div class="col-4">
                    <label for="cert-type" class="form-label">Certificate Type</label>
                    <select name="cert-type" id="cert-type" class="form-select">
                      <option value="" hidden>Choose Certificate</option>
                      <option value="WASSCE">WASSCE</option>
                      <option value="SSCE">SSCE</option>
                      <option value="BACCALAUREATE">BACCALAUREATE</option>
                    </select>
                  </div>
                  <div class="col-4">
                    <label for="program" class="form-label">Programmes</label>
                    <select name="program" id="program" class="form-select">
                      <option value="" hidden>Choose Programme</option>
                      <?php
                      $data = $expose->fetchPrograms(0);
                      foreach ($data as $ft) {
                      ?>
                        <option value="<?= $ft['id'] ?>"><?= $ft['name'] ?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                </div>
              </form>
              <div id="info-output"></div>
              <table class="table table-borderless datatable table-striped table-hover">
                <thead class="table-dark">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col" rowspan="2">Name</th>
                    <th scope="col" colspan="4">Core Subjects</th>
                    <th scope="col" colspan="4">Elective Subjects</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col">M</th>
                    <th scope="col">E</th>
                    <th scope="col">I</th>
                    <th scope="col">S</th>
                    <th scope="col">E1</th>
                    <th scope="col">E2</th>
                    <th scope="col">E3</th>
                    <th scope="col">E4</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                  </tr>

                  <?php
                  $appIDs = $expose->getAllApplicantsID();
                  $index = 0;
                  foreach ($appIDs as $appID) {
                    $index += 1;
                    $subjs = $expose->getApplicantsSubjects($appID["id"]);
                    //$status = $ft["declaration"] == 1 ? '<span class="badge text-bg-success">Submitted</span>' : '<span class="badge text-bg-danger">In Progress</span>';
                  ?>
                    <tr>
                      <th scope="row"><?= $index ?></th>
                      <td><?= !empty($subjs[0]["middle_name"]) ? $subjs[0]["first_name"] . " " . $subjs[0]["middle_name"] . " " . $subjs[0]["last_name"] : $subjs[0]["first_name"] . " " . $subjs[0]["last_name"] ?></td>
                      <?php
                      for ($i = 0; $i < count($subjs); $i++) {
                        if ($subjs[$i]["type"] == "core") {
                      ?>
                          <td><?= $subjs[$i]["grade"] ?></td>
                        <?php
                        }
                      }
                      for ($i = 0; $i < count($subjs); $i++) {
                        if ($subjs[$i]["type"] == "elective") {
                        ?>
                          <td><?= $subjs[$i]["grade"] ?></td>
                      <?php
                        }
                      }
                      ?>
                      <td scope="col"></td>
                      <td><button class="btn btn-success btn-xs">Admit</button></td>
                    </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
              <div class="mt-4" style="float:right">
                <button class="btn btn-primary">Create Broadsheet</button>
              </div>
              <div class="clearfix"></div>
            </div>

          </div>
        </div><!-- End Recent Sales -->

        <!-- Right side columns -->
        <!-- End Right side columns -->

      </div>
    </section>

  </main><!-- End #main -->

  <?= require_once("inc/footer-section.php") ?>

  <script>
    $(document).ready(function() {

      $(".form-select").change("blur", function(e) {
        e.preventDefault();
        data = {
          "country": $("#country").val(),
          "type": $("#type").val(),
          "program": $("#program").val(),
        }

        var id = this.id

        $.ajax({
          type: "POST",
          url: "endpoint/applicants",
          data: data,
          success: function(result) {
            console.log(result);

            if (result.success) {
              $("tbody").html('');
              $.each(result.message, function(index, value) {
                let status = value.declaration == 1 ? '<span class="badge text-bg-success">Submitted</span>' : '<span class="badge text-bg-danger">In Progress</span>';
                $("tbody").append(
                  '<tr>' +
                  '<th scope="row"><a href="#">' + value.id + '</a></th>' +
                  '<td>' + value.first_name + ' ' + value.last_name + '</td>' +
                  '<td>' + value.nationality + '</td>' +
                  '<td>' + value.app_type + '</td>' +
                  '<td>' + value.first_prog + '</td>' +
                  '<td>' + status + '</td>' +
                  '<td><b><a href="applicant-info.php?q=' + value.id + '">Open</a></b></td>' +
                  '</tr>');
              });

            } else {
              $("tbody").html('');
              $("#info-output").html(
                '<div class="alert alert-info alert-dismissible fade show" role="alert">' +
                '<i class="bi bi-info-circle me-1"></i>' + result.message +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>'
              );
            }

            if (id == "type") {
              $.ajax({
                type: "GET",
                url: "endpoint/programs",
                data: {
                  "type": $("#type").val(),
                },
                success: function(result) {
                  console.log(result);
                  if (result.success) {
                    $("#program").html('<option value="All">All</option>');
                    $.each(result.message, function(index, value) {
                      $("#program").append('<option value="' + value.name + '">' + value.name + '</option>');
                    });
                  }
                },
                error: function(error) {
                  console.log(error);
                }
              });
            }

          },
          error: function(error) {
            console.log(error);
          }
        });
      });

      $(".printer").click(function() {
        let c = "c=" + $("#country").val();
        let t = "&t=" + $("#type").val();
        let p = "&p=" + $("#program").val();
        window.open("print-document.php?" + c + t + p, "_blank");
      });

      function getUrlVars() {
        var vars = {};
        var parts = window.location.href.replace(
          /[?&]+([^=&]+)=([^&]*)/gi,
          function(m, key, value) {
            vars[key] = value;
          }
        );
        return vars;
      }

      //Use a default value when param is missing
      function getUrlParam(parameter, defaultvalue) {
        var urlparameter = defaultvalue;
        if (window.location.href.indexOf(parameter) > -1) {
          urlparameter = getUrlVars()[parameter];
        }
        return urlparameter;
      }

      if (getUrlVars()["status"] != "" || getUrlVars()["status"] != undefined) {
        if (getUrlVars()["exttrid"] != "" || getUrlVars()["exttrid"] != undefined) {}
      }


    });
  </script>

</body>

</html>