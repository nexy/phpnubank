# Pix
Está implementado o suporte a consulta de chaves do Pix e solicitação de cobrança (QRCode).

## Solicitando um pagamento

Vamos usar o exemplo de uma autenticação por certificado e fazer a solicitação de um QRCode para pagamento de um pix de R$ 15,00.

```php
$nu = new Nubank($client);

$nu->authenticateWithCert(
  $_POS['cpf'],
  $_POST['password'],
  realpath("<CAMINHO_PARA_A_PASTA_DO_PACOTE>/cert/cert.p12")
);

// Agora que você está autenticado...

//Aqui você consegue todas as suas chaves
$pix = $nu->getAvailablePixKeys();

//Aqui você pega a primeira chave da lista
$chave0 = current($pix->keys)

//Agora você gera a requisição ao nubank para gerar o código de pagamento
$qrData = $nu->createAvailablePixPaymentQrCode(
  $pix->account_id,
  15,
  $chave0
);

/*
Aqui você coloca uma chamada a um html com a imagem

<img src="<?php echo $qrData->qr; ?>" width="300">
*/
```