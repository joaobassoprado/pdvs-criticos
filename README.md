# 📣 Sistema de Reclamações de Empresas

Sistema simples para **registrar e editar reclamações relacionadas a empresas**, com controle básico de prioridade, motivo e permissões de acesso 🗂️.

---

## 🎯 Objetivo

Centralizar o registro de reclamações de forma organizada, permitindo edição e rastreio de alterações.

---

## 🛠️ Tecnologias

-   Laravel
-   Livewire Volt
-   Blade / Mary UI
-   Tailwind CSS

---

## ✅ Funcionalidades

-   Cadastro de reclamações
-   Edição de reclamações existentes
-   Definição de prioridade
-   Associação com empresa e motivo
-   Controle de acesso por permissão
-   Registro de quem alterou (`updated_by`)

---

## 🚦 Prioridades

-   Baixa
-   Média
-   Alta
-   Urgente

---

## 🔐 Controle de Acesso

O acesso às telas é controlado via **Gates do Laravel**. Usuários sem permissão são redirecionados para a tela 403.

---

## 📌 Observações

-   Reclamações utilizam **soft delete**
-   Validações são feitas no backend
-   Sistema de uso interno

---

Sistema desenvolvido com foco em simplicidade, clareza e manutenção 🚀.
