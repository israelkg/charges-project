# 📚 Projeto: Cobranças (Symfony)

Este é um projeto de gerenciamento de Cobranças Online. Desenvolvido com o framework Symfony, focado em implementar funcionalidades de CRUD de cobranças, Exportação, Análise de Dados Financeiros e uma interface intuitiva e limpa.

---

## 📚 Índice

* 📄 [Descrição Detalhada](#-descrição-detalhada)
* ✨ [Funcionalidades](#-funcionalidades)
* ⚙️ [Tecnologias Usadas](#-tecnologias-usadas)

---

## 📄 Descrição Detalhada

Este projeto é uma aplicação completa para **gerenciamento de cobranças**, desenvolvida com **Symfony** e voltada para facilitar a emissão, controle e análise de faturas. O sistema conta com uma interface moderna, intuitiva e responsiva, oferecendo recursos avançados como:

- **Dashboard com Análises Inteligentes**: exibe dados resumidos de cobranças emitidas, atrasadas, número de clientes e insights sobre pagamentos, usando gráficos interativos com Chart.js.
- **Modo Claro/Escuro**: o usuário pode alternar entre temas, melhorando a experiência visual e acessibilidade.
- **Exportação em PDF**: é possível exportar tanto o dashboard completo quanto cobranças individuais em PDF, com todos os dados detalhados e o QR Code do PIX incluso.
- **CRUD completo de cobranças**: com suporte a clientes, itens, prazos, e diferentes tipos de pagamento e envio.

Além disso, o projeto segue as melhores práticas de desenvolvimento com Symfony e oferece uma arquitetura sólida e escalável.

---

## ✨ Funcionalidades

Atualmente, o sistema oferece:

### 🔁 Gerenciamento de Cobranças
- Controle total de cobranças podendo criar, editar, visualizar e excluir.
- Informações completas: cliente, valor, vencimento, forma de pagamento, envio por e-mail/whatsapp/sms, itens cobrados, e descrição.
- Geração automática de **PIX com QR Code** para pagamento.
  
### 📊 Dashboard com Análises Inteligentes
- Visualização geral de:
  - Total de cobranças emitidas
  - Total de atrasos
  - Número de clientes únicos
- Insights como:
  - Faixa de valores mais pagos
  - Média de atraso nos pagamentos
  - Serviços com mais retorno
- Gráficos dinâmicos com [Chart.js](https://www.chartjs.org/)

### 🎨 Tema Escuro e Claro
- Alternância de tema com persistência da escolha do usuário.
- Interface responsiva com foco em acessibilidade.

### 📄 Exportação em PDF
- Exportar **qualquer cobrança** com seus dados, QR Code PIX, datas e status.
- Exportar o **dashboard completo** com os KPIs e gráficos.

### 📋 Listagem
- Tabela com todas as cobranças emitidas.
- Exportação de tabela de cobranças no excel
- Opções de visualização rápida e ação.

---

## ⚙️ Tecnologias Usadas

### 🔧 Backend (Symfony)
- **Symfony 7.3** – Estrutura principal do projeto
- **Doctrine MongoDB ODM** – Integração com banco de dados MongoDB
- **Symfony Form** – Criação e manipulação de formulários
- **Symfony Validator** – Validações robustas nos dados de entrada
- **Symfony Security** – Sistema de autenticação e autorização
- **Symfony Mailer** – Envio de e-mails com cobranças

### 🖥️ Frontend
- **Twig** – Template engine para renderização das views
- **Chart.js** – Geração de gráficos interativos no dashboard
- **Tema Claro/Escuro** – Implementado com Context API + JavaScript (frontend vanilla)
- **Tailwind** – Estilização

### 🧾 PDF e PIX
- **Dompdf** – Geração de PDFs para cobranças e relatórios
- **Endroid QrCode** – Criação de QR Codes para pagamento via PIX

### 📦 Outros
- **Symfony Asset** – Gerenciamento de assets (JS/CSS/Imagens)
- **Composer** – Gerenciador de dependências PHP
- **MongoDB** – Banco de dados NoSQL
---

