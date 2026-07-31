# Catálogo Digital Premium

> Plataforma moderna de catálogo digital B2B/B2C para apresentação de produtos, geração de contatos via WhatsApp e gestão administrativa de alta performance.

---

## 🚀 Quick Start (Início Rápido)

Com o **Docker** e o **Makefile** inclusos no projeto, você consegue colocar a aplicação para rodar localmente em menos de 5 minutos:

### 1. Clonar o repositório & configurar o ambiente
```bash
cp .env.example .env
```

### 2. Subir os containers Docker
```bash
make up
```

### 3. Instalar dependências e preparar o banco de dados
```bash
make install
```
*(Executa `composer install`, gera a chave da aplicação, roda as migrations e popula os seeders)*

### 4. Acessar a Aplicação
- **Aplicação Web:** `http://localhost`
- **Painel Administrativo:** `http://localhost/admin`

---

## 🔑 Credenciais de Teste

| Perfil | E-mail | Senha | Acesso / Permissões |
|--------|--------|-------|----------------------|
| **Super Admin** | `admin@cjcalcados.com.br` | `password` | Acesso total ao sistema |
| **Editor** | `editor@cjcalcados.com.br` | `password` | Gestão de produtos (sem acesso a Banners/Marketing) |
| **Company Admin** | `company@cjcalcados.com.br` | `password` | Administração da Unidade / Matriz |

---

## ✨ Funcionalidades Principais

- 🛍️ **Catálogo Público de Alta Performance:** Grid responsivo com ordenação, paginação e lazy-loading de imagens.
- 🔍 **Filtros e Busca Instantânea:** Filtragem por Categoria, Coleção, Marca, Cor, Material, Tamanho, Lançamentos e Destaques.
- 📱 **Integração com WhatsApp:** Botão direto para iniciar conversas com vendedores contendo a referência exata do produto.
- 🖼️ **Galeria & Upload Inteligente:** Processamento automático de imagens para formato WebP, miniaturas e armazenamento via S3 / MinIO.
- 🛡️ **Painel Administrativo Robusto:** Gestão completa de Produtos, Categorias, Marcas, Banners, Usuários e Permissões (RBAC).
- 📊 **Analytics & Dashboard:** Métricas de visualização de produtos e contagens de cliques direcionados ao WhatsApp.
- 🎨 **Design System Premium:** Visual limpo e sofisticado, inspirado em referências como Apple, Nike e Adidas.

---

## 🛠️ Tech Stack & Arquitetura

### Backend
- **PHP 8.4+** & **Laravel 12**
- **PostgreSQL** (Banco relacional) & **Redis** (Cache/Queues)
- **MinIO / AWS S3** (Armazenamento de mídia)
- **Spatie Packages:** Permission, MediaLibrary e Activitylog

### Frontend
- **Blade & Livewire 3** (Interatividade reativa server-side)
- **Alpine.js** (Comportamento cliente leve)
- **Tailwind CSS v4** (Estilização moderna e responsiva)
- **Vite** (Asset bundler rápido)

### Arquitetura de Código
Aplicação estruturada em **Clean Architecture / DDD Modular**:
```
app/
├── Actions/        # Casos de uso de ação única (Single Responsibility)
├── DTOs/           # Data Transfer Objects fortemente tipados
├── Enums/          # Enumerações de domínio
├── Helpers/        # Funções auxiliares (ex: gerador de link WhatsApp)
├── Models/         # Modelos Eloquent
├── Policies/       # Lógica de autorização e permissões
├── Repositories/   # Abstração de acesso a dados
└── Services/       # Serviços de domínio e integração
```

---

## ⚡ Comandos Úteis (Makefile)

O projeto disponibiliza atalhos práticos via `make`:

```bash
make help       # Lista todos os comandos disponíveis no Makefile
make up         # Sobe os containers da aplicação em segundo plano
make down       # Encerra os containers Docker
make shell      # Abre o terminal interativo (bash) no container Laravel
make fresh      # Reseta o banco de dados e executa os seeders (migrate:fresh --seed)
make test       # Executa a suíte de testes com Pest PHP
make pint       # Executa o Laravel Pint para formatação de código (PSR-12)
make stan       # Executa a análise estática com Larastan (PHPStan)
make lint       # Executa a verificação completa (Pint + Stan)
make assets     # Compila os assets do frontend (npm run build)
```

---

## 🧪 Testes e Qualidade de Código

Garantimos a confiabilidade e legibilidade do código através de ferramentas automatizadas:

```bash
# Rodar suíte de testes (Pest PHP)
make test

# Analisar código estaticamente (Larastan)
make stan

# Formatar código no padrão PSR-12 (Laravel Pint)
make pint

# Lint completo (Pint + Larastan)
make lint
```

---