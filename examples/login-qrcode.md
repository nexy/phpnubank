# Autenticação por QRCode
Ao utilizar a autenticação por QRCode, você estará reproduzindo o fluxo de acesso feito no app web:

## Precisamos gerar um QRCode

```php
use Nubank\Nubank;
use Nubank\Requests\Authentication\WithQrCode;

$qrData = WithQrCode::getQrData();

/*
Aqui você coloca uma chamada a um html com a imagem

<form>
<img src="<?php echo $qrData->qr; ?>" width="300">
<input type="hidden" name="uuid" value="<?php echo $qrData->content; ?>">

<div>
  <label>CPF</label>
  <input type="text" name="cpf">
</div>

<div>
  <label>Password</label>
  <input type="password" name="password">
</div>

<button class="btn btn-primary">Login no Nu</button>
</form>
*/
```

<img src="img/qrcode-web.png" width="650" alt="Exemplo do app web do Nubank"/>

Dessa maneira sempre será necessário utilizar o celular para escanear o QRCode e autorizar o acesso.

## Recebendo o formulário e utilizando os dados do form

```php
use Nubank\Nubank;

$client = new \GuzzleHttp\Client;

$nu = new Nubank($client);
$nu->authenticateWithQrCode(
    $_POST['cpf'],
    $_POST['password'],
    $_POST['uuid']
);

// Agora que você está autenticado...
echo $saldo = $nu->getAccountBalance();
```