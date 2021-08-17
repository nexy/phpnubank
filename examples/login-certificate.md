# Autenticação por Usuário e Senha com certificado
Ao utilizar a autenticação com certificado, você estará reproduzindo o fluxo de acesso pelo app.

![Fluxo de geração de certificado](./img/diagram.PNG)

Por esse fluxo você receberá um email do Nubank com o código de acesso e será gerado um certificado (Que você deve manter em um lugar seguro)

Com o certificado gerado, é possível fazer o login sem interação humana (Ideal para scripts)

Após instalar a biblioteca, utilize o comando a seguir na pasta deste pacote:

```
php cli/cli.php

```

Esse comando solicita ao nubank que gera um código que é enviado para o seu e-mail. Assim que você receber esse e-mail com o código, copie e cole no terminal. O 'cli' estará esperando por esse código.

Se executado com sucesso, será gerado um arquivo `cert.p12` na pasta cert. **Mantenha ele no lugar**. Esse certificado será utilizado pela lib para se comunicar com o servidor do Nubank.

```php
$nu = new Nubank($client);

$nu->authenticateWithCert(
  $_POS['cpf'],
  $_POST['password'],
  realpath("<CAMINHO_PARA_A_PASTA_DO_PACOTE>/cert/cert.p12")
);

// Agora que você está autenticado...
echo $saldo = $nu->getAccountBalance();
```