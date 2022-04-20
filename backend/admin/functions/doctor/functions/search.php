<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Admin</title>

    <?php include_once(__DIR__ . "./../../../style/style_css.php"); ?>
</head>

<body>

    <div class="d-flex">
        <!-- Sidebar -->

        <!-- End Sidebar -->

        <div class="w-100">
            <!-- Navbar -->

            <!-- End Navbar -->

            <!-- Content -->
            <div id="content">
                <section class="py-3 bg-grey">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <h1 class="font-weight-bold mb-0">Sản phẩm</h1>
                                <p class="lead text-muted">Trang quản lý thông tin sản phẩm</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Content main-->
                <section class="content-main py-2">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="search"  aria-label="search" aria-describedby="basic-addon1">
                                        </div>
                                        <div id="data_content" class="table-responsive">
                                            <!-- Search result show here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- End content main -->
            </div>
            <!-- End Content -->
        </div>
    </div>

    <?php include_once(__DIR__ . "./../../../style/style_js.php"); ?>

    <script>
        $(document).ready(function() {

            function load_data(page, query = '') {
                $.ajax({
                    url: "fetch_data.php",
                    method: "POST",
                    data: {
                        page: page,
                        query: query
                    },
                    success: function(data) {
                        $('#data_content').html(data);
                    }
                });
            }

            load_data(1);

            $('#search').keyup(function() {
                var search = $(this).val();
                load_data(1, search);
            });

            $(document).on('click', '.page-link', function() {
                var page = $(this).data('page_number');
                var search = $('#search').val();
                load_data(page, search);
            });

        });
    </script>
</body>

</html>