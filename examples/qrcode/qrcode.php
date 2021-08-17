
<!doctype html>
    <html lang="en" class="h-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Hugo 0.87.0">
        <title>PHP Nubank - login com qrcode</title>

        <!-- Bootstrap core CSS -->
        <link href="https://getbootstrap.com/docs/5.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">

        <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
              font-size: 3.5rem;
          }
      }
  </style>
</head>
<body class="d-flex flex-column h-100">

    <!-- Begin page content -->
    <main class="flex-shrink-0">
        <form action="?" method="post">
            <div class="text-center">
                <img src="<?php echo $qrData->qr; ?>" width="300">

                <h4><?php echo $qrData->content; ?></h4>
                <input type="hidden" name="uuid" value="<?php echo $qrData->content; ?>">
                <button class="btn btn-primary">Login no Nu</button>
            </div>
        </form>
    </main>
</body>
</html>