
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

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" aria-label="Ninth navbar example">
            <div class="container-xl">
                <a class="navbar-brand" href="#">PHP Nubank</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample07XL" aria-controls="navbarsExample07XL" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarsExample07XL">
                    <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Github</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container mt-5">
            <div class="row">
                <div class="col-6">
                    <h4>Sua conta</h4>

                    <div class="mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-subtitle">Saldo da sua conta</div>
                                <h4 class="card-title"><?php echo 'R$ ' . number_format($saldo, 2, ',', '.'); ?></h4>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="card-subtitle">Resultado dos seus investimentos</div>
                                <h4 class="card-title"><?php echo $investimentos; ?></h4>
                            </div>
                        </div>
                    </div>

                    <h4>Últimas 5 movimentações</h4>

                    <ul class="list-group mt-3">
                        <?php 
                        $i = 0;
                        foreach($feed as $item) {
                            $i++;
                            if ($i >= 6) continue;
                            ?>
                            <li class="list-group-item">
                                <span class="pl-2"><?php echo $item['title']; ?></span>
                                <span class="pl-2"><?php echo $item['postDate']; ?></span>
                                <p><?php echo $item['detail']; ?></p>
                            </li>
                        <?php } ?>
                    </ul>
                </div>

                <div class="col-6">
                    <h4>Receba um pagamento com pix</h4>
                    <form>
                        <p>Minhas chaves pix</p>
                        <ul class="list-group my-3">
                            <?php foreach($pix->keys as $chave) {?>
                                <li class="list-group-item d-flex align-items-center">
                                    <input type="radio" name="chave_pix" value="<?php echo $chave['id']; ?>">
                                    <div class="px-4">
                                        <span class="badge bg-secondary"><?php echo $chave['kind']; ?></span>
                                        <h5><?php echo $chave['value']; ?></h5>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>

                        <p class="mt-2">Escolha o valor</p>
                        <div class="btn-group mb-3" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="montante" id="btnradio1" value="5" autocomplete="off">
                            <label class="btn btn-lg btn-outline-secondary" for="btnradio1">R$ 5,00</label>

                            <input type="radio" class="btn-check" name="montante" id="btnradio2" value="10" autocomplete="off">
                            <label class="btn btn-lg btn-outline-secondary" for="btnradio2">R$ 10,00</label>

                            <input type="radio" class="btn-check" name="montante" id="btnradio3" value="15" autocomplete="off">
                            <label class="btn btn-lg btn-outline-secondary" for="btnradio3">R$ 15,00</label>
                        </div>

                        <div class="mb-3 px-2 row">
                            <button class="btn btn-primary">Gerar qrcode</button>
                        </div>

                    </form>

                    <?php if (isset($qrcode)) { ?>
                    <div class="card">
                        <div class="card-body text-center">
                            <p class="lead">Aponte a câmera do seu aplicativo de pagamento</p>
                            <img src="<?php echo $qrcode->qr_code; ?>" width="200">
                            <p style="font-size: 11px;"><?php echo $qrcode->payment_code; ?></p>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>

    </main>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
</html>