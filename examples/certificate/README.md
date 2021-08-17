## Acessando sua conta no Nubank por certificado

## Gerando um certificado

1 - Preencha os dados de credenciais no arquivo credentials.php. Se ele não existir, renomeie o arquivo credentials.example.php para credentials.php

2 - Na pasta raiz rode o seguinte comando:

```php
php cli/cli.php
```

3 - Preencha as perguntas do CLI e você terá seu certificado gerado na pasta cli

## Vendo dados da sua conta em uma página web

Agora que você já gerou o seu certificado, vamos criar um servidor web para a sua página com o seguinte comando:

```php
php -S localhost:3333 examples/certificate/
```

Acesse o endereço http://localhost:3333 e veja dados da sua conta disponíveis.