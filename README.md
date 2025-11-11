# fort-cosmetics
|# 🧩 Projeto FortCosmetics

Sistema de gerenciamento de cosméticos e bundles, desenvolvido para sincronização de dados entre ambiente local e servidor remoto.  
O projeto segue arquitetura modular, utiliza containers Docker e está preparado para execução em ambiente de desenvolvimento e produção.

---

## 🚀 Instruções para rodar o projeto localmente

### **Pré-requisitos**
Antes de começar, verifique se você possui instalado:
- [Docker](https://www.docker.com/) e [Docker Compose](https://docs.docker.com/compose/)
- [PHP 8.2+](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [Node.js 18+](https://nodejs.org/)
- [Git](https://git-scm.com/)

---

### **Passos para rodar o projeto**

1. **Clone o repositório**
   ```bash
   git clone <url-do-repositorio>
   cd fort-cosmetics
   cp .env.example .env
   docker-compose up -d --build
   docker exec -it fortsync_app bash
   composer install
   npm install
   npm run dev
   http://localhost:8000
🧠 Tecnologias utilizadas
Backend

PHP 8.2

Laravel 12 — Framework principal

Composer — Gerenciador de dependências

MySQL — Banco de dados relacional

Docker — Orquestração dos serviços

Frontend

Blade Templates — Engine de templates do Laravel

Tailwind CSS (via Vite) — Estilização adicional

Infraestrutura e DevOps

Docker Compose — Gestão de ambiente local

Scheduler + Queue Workers — Execução assíncrona de tarefas

Git + CodeCommit — Controle de versão

Vite — Build rápido para assets front-end

⚙️ Decisões técnicas relevantes

📦 Estrutura modular MVC
O sistema segue um padrão MVC customizado, inspirado no Laravel, garantindo separação clara entre regras de negócio, camada de visualização e persistência de dados.

🔄 Sincronização via containers
A arquitetura Docker foi adotada para simplificar o setup do ambiente e garantir compatibilidade entre os ambientes de desenvolvimento e produção.

🧰 Uso do padrão Repository
Aplicado para desacoplar regras de negócio da camada de persistência, facilitando manutenção e testes unitários.

🔐 Logs e Scheduler dedicados
O container fortsync_scheduler executa rotinas automáticas e grava logs individualmente (storage/logs/scheduler.log), facilitando o monitoramento.






