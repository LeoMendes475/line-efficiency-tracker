# LG Production Dashboard

Um dashboard de produção para monitorar linhas de produtos LG, desenvolvido com Laravel e Docker.

## Tecnologias Utilizadas

- **Laravel 7** - Framework PHP
- **Docker & Docker Compose** - Containerização
- **MySQL** - Banco de dados
- **Chart.js** - Gráficos
- **Tailwind CSS** - Estilização (via CDN)

## Pré-requisitos

- Docker
- Docker Compose

## Como Rodar o Projeto

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/LeoMendes475/line-efficiency-tracker.git
   cd line-efficiency-tracker
   ```

2. **Suba os containers:**
   ```bash
   docker-compose up -d
   ```

3. **Instale as dependências do PHP:**
   ```bash
   docker-compose exec app composer install
   ```

4. **Instale as dependências do Node.js (opcional, para assets):**
   ```bash
   docker-compose exec app npm install
   docker-compose exec app npm run dev
   ```

5. **Configure o ambiente:**
   - Copie o arquivo `.env.example` para `.env` (se existir) ou configure as variáveis no `.env` dentro do container.
   - Certifique-se de que as configurações de banco de dados no `.env` apontem para o serviço MySQL do Docker.

6. **Execute as migrações:**
   ```bash
   docker-compose exec app php artisan migrate
   ```

7. **Execute os seeders para popular o banco:**
   ```bash
   docker-compose exec app php artisan db:seed
   ```

8. **Acesse o projeto:**
   - Abra o navegador em `http://localhost:8000` (ou a porta configurada no docker-compose.yml).

## Comandos Úteis

- **Parar os containers:**
  ```bash
  docker-compose down
  ```

- **Ver logs:**
  ```bash
  docker-compose logs -f app
  ```

- **Executar comandos no container da aplicação:**
  ```bash
  docker-compose exec app bash
  ```

- **Limpar cache do Laravel:**
  ```bash
  docker-compose exec app php artisan cache:clear
  docker-compose exec app php artisan config:clear
  ```

## Estrutura do Projeto

- `app/` - Código da aplicação Laravel
- `database/migrations/` - Migrações do banco
- `database/seeders/` - Seeders para dados de exemplo
- `resources/views/` - Views Blade
- `docker-compose.yml` - Configuração do Docker
- `Dockerfile` - Imagem Docker da aplicação

## Funcionalidades

- Dashboard com métricas de produção
- Filtros por linha de produto
- Busca por linha específica
- Paginação de resultados
- Gráficos de eficiência

## Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova feature'`)
4. Push para a branch (`git push origin feature/nova-feature`)
5. Abra um Pull Request
