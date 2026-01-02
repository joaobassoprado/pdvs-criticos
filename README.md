# 📋 Sistema de Formulário – PDVs Críticos

Sistema desenvolvido para **avaliação de PDVs**, permitindo o registro de respostas por questionário, vinculado a um ponto de venda e responsável.

O objetivo é **identificar criticidade**, **organizar dados** e **facilitar análises** através de um formulário simples e eficiente.

---

## 🚀 Funcionalidades

- ✅ Seleção de **PDV**
- ✅ Campo para **responsável**
- ✅ Listagem dinâmica de **perguntas**
- ✅ Respostas padronizadas (1, 2 ou 3)
- ✅ Salvamento das respostas no banco de dados
- ✅ Estrutura preparada para relatórios futuros

---

## 🛠️ Tecnologias Utilizadas

- **PHP**
- **Laravel**
- **Livewire (Volt)**
- **MySQL**
- **Blade**
- **Tailwind CSS**

---

## 🧩 Estrutura do Sistema

### Entidades principais:
- **PDVs** – Pontos de venda cadastrados
- **Perguntas** – Perguntas do questionário
- **Respostas** – Respostas vinculadas ao PDV e à pergunta

### Relacionamentos:
- Um **PDV** possui várias **Respostas**
- Uma **Pergunta** pode aparecer em várias **Respostas**

---

## 🗂️ Estrutura do Projeto (simplificada)

