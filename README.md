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

## Banco de Dados

### Estrutura da Tabela `productions`

```sql
CREATE TABLE productions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_line VARCHAR(255) NOT NULL,           -- Nome da linha (ex: TV OLED)
    quantity_produced INT NOT NULL,               -- Quantidade total produzida
    quantity_defects INT NOT NULL,                -- Quantidade de defeitos
    efficiency DECIMAL(5,2) NOT NULL,             -- Eficiência (ex: 98.50)
    production_date DATE NOT NULL,                -- Data da produção
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Dados de Exemplo

Para popular a tabela com dados de exemplo, execute os seguintes comandos SQL:

```sql
-- Inserir dados de exemplo para diferentes linhas de produto
INSERT INTO productions (product_line, quantity_produced, quantity_defects, efficiency, production_date, created_at, updated_at) VALUES
('TV OLED 55"', 150, 3, 98.00, '2026-03-25', NOW(), NOW()),
('TV OLED 65"', 120, 2, 98.33, '2026-03-25', NOW(), NOW()),
('TV LED 43"', 200, 8, 96.00, '2026-03-25', NOW(), NOW()),
('TV LED 50"', 180, 5, 97.22, '2026-03-25', NOW(), NOW()),
('Soundbar Premium', 300, 6, 98.00, '2026-03-25', NOW(), NOW()),
('Home Theater', 80, 4, 95.00, '2026-03-25', NOW(), NOW()),
('TV OLED 55"', 145, 4, 97.24, '2026-03-26', NOW(), NOW()),
('TV OLED 65"', 118, 3, 97.46, '2026-03-26', NOW(), NOW()),
('TV LED 43"', 195, 7, 96.41, '2026-03-26', NOW(), NOW()),
('TV LED 50"', 175, 6, 96.57, '2026-03-26', NOW(), NOW()),
('Soundbar Premium', 290, 8, 97.24, '2026-03-26', NOW(), NOW()),
('Home Theater', 85, 3, 96.47, '2026-03-26', NOW(), NOW()),
('TV OLED 55"', 152, 2, 98.68, '2026-03-27', NOW(), NOW()),
('TV OLED 65"', 125, 1, 99.20, '2026-03-27', NOW(), NOW()),
('TV LED 43"', 205, 9, 95.61, '2026-03-27', NOW(), NOW()),
('TV LED 50"', 182, 4, 97.80, '2026-03-27', NOW(), NOW()),
('Soundbar Premium', 310, 5, 98.39, '2026-03-27', NOW(), NOW()),
('Home Theater', 78, 5, 93.59, '2026-03-27', NOW(), NOW());
```

Ou utilize o seeder do Laravel:

```bash
docker-compose exec app php artisan db:seed --class=ProductionSeeder
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

## CI/CD

Este projeto utiliza GitHub Actions para CI/CD. A pipeline executa automaticamente:

- Instalação de dependências
- Migrações do banco
- Execução de seeders
- Testes automatizados (PHPUnit)
- Build de assets

Para mais detalhes, veja o arquivo `.github/workflows/ci.yml`.

## Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova feature'`)
4. Push para a branch (`git push origin feature/nova-feature`)
5. Abra um Pull Request
