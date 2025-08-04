
# 📦 Laravel API - Ambiente Docker

Este projeto é uma API em Laravel configurada para rodar dentro de containers Docker. Abaixo, você encontrará instruções para configurar o ambiente, executar a aplicação e rodar as migrations.

---

## 🐳 Requisitos

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado no seu sistema.

### Como instalar o Docker no Windows:

1. Acesse [https://www.docker.com/products/docker-desktop/](https://www.docker.com/products/docker-desktop/)
2. Baixe a versão para Windows.
3. Execute o instalador e siga os passos.
4. Após a instalação, reinicie o computador se solicitado.
5. Verifique a instalação executando o comando no terminal:
   ```bash
   docker --version
   ```

---

## 🚀 Subindo a aplicação com Docker

### 1. Clonar o repositório

```bash
git clone https://github.com/IgorOliveira852/app-catalogo-filmes.git
cd app-catalogo-filmes
```

### 2. Copiar o .env

```bash
cp .env.example .env
```

Ajuste as variáveis conforme necessário, especialmente:

```
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root
```

### 3. Subir os containers

1. Na pasta raiz rode o comando:
```bash
docker compose up --build -d
```

Esse comando irá:

- Construir as imagens (caso necessário)
- Subir o container da aplicação Laravel
   1. Roda o composer install
   2. Roda as migrations
   3. Roda as seeders (comentado)
   4. Roda os testes automatizados
- Subir o container do banco de dados MySQL
- Subir o cointainer do VueJS

---

## 🛠️ Comandos úteis dentro do Docker

### Acessar o container da aplicação:

```bash
docker exec -it laravel-app bash
```

Dentro do bash do container, você pode rodar comandos Artisan, Composer ou qualquer outro comando PHP.

### Rodar migrations:

```bash
php artisan migrate
```

### Instalar dependências (caso necessário):

```bash
composer install
```

---

## 📬 Acessando a API

A aplicação estará disponível em:

```
http://localhost:8000
```

Você pode testar os endpoints com ferramentas como Postman ou Insomnia.

---

## 📂 Estrutura dos containers

- `app`: container da aplicação Laravel (PHP-FPM + Composer)
- `mysql`: container com MySQL
- `nginx`: (opcional) caso esteja configurando com proxy reverso

---

## 🧪 Testes

Para rodar os testes dentro do container:

```bash
php artisan test
```

---

## ✅ Próximos passos

- Documentação dos endpoints da API: https://www.notion.so/KingHost-1fa8029ce0cc80e5a788fb1aae54e7d6?pvs=4

---

## 🧑‍💻 Contribuindo

Pull requests são bem-vindos! Para mudanças maiores, abra uma issue antes para discutirmos o que você gostaria de modificar.

---

## 📝 Licença

Este projeto está sob a licença MIT.
