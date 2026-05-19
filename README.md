# 🛍️ Bazar Mix da Jô

Loja virtual (vitrine online) em **PHP 8.3+** e **MySQL** para um bazar feminino. O sistema exibe produtos em cards estilo marketplace (tipo AliExpress) e direciona o cliente para o **WhatsApp** com mensagem automática contendo o título e preço do produto.

> **Sem carrinho, sem checkout, sem pagamento online.** O único canal de contato/compra é o WhatsApp.

---

## ✅ Requisitos do Servidor

- PHP 8.3 ou superior
- MySQL 5.7+ ou MariaDB 10.3+
- Extensão **PDO MySQL** habilitada
- Servidor Apache ou Nginx (compatível com cPanel/Hostinger)
- Permissão de escrita na pasta `uploads/products`

---

## 🚀 Instalação

### 1. Enviar arquivos

Envie **todo o conteúdo** do projeto para a pasta pública da hospedagem (normalmente `public_html`).

### 2. Criar o banco de dados

No painel da hospedagem (phpMyAdmin, cPanel, etc.), crie um banco de dados MySQL. Exemplo: `bazar_mix_jo`.

### 3. Importar o SQL

Importe o arquivo `database/database.sql` pelo phpMyAdmin ou via terminal:

```bash
mysql -u usuario -p nome_do_banco < database/database.sql
```

### 4. Configurar a conexão

Edite o arquivo `config/database.php` com os dados do seu banco:

```php
const DB_HOST = 'localhost';
const DB_NAME = 'bazar_mix_jo';
const DB_USER = 'seu_usuario';
const DB_PASS = 'sua_senha';
```

### 5. Permissão de escrita

Garanta que a pasta `uploads/products` tenha permissão de escrita (chmod 755 ou 775):

```bash
chmod -R 755 uploads/
```

---

## 🔑 Administrador Inicial

O SQL já cria um usuário administrador:

| Campo | Valor                    |
|-------|--------------------------|
| Email | `admin@bazarmixjo.com`   |
| Senha | `Admin@123`              |

> ⚠️ **Recomendamos alterar a senha** após o primeiro acesso. Para gerar um novo hash de senha:
>
> ```php
> <?php echo password_hash('sua-nova-senha', PASSWORD_DEFAULT);
> ```
>
> Atualize o campo `password_hash` na tabela `admins` via phpMyAdmin.

---

## 🌐 Como Acessar

| Página | URL |
|--------|-----|
| **Loja pública** | `https://seudominio.com/` |
| **Painel admin** | `https://seudominio.com/admin/login.php` |

---

## 🏷️ Categorias

No painel administrativo, acesse **Categorias** para:

- ✅ Cadastrar novas categorias
- ✏️ Editar nome da categoria
- 🔄 Ativar/desativar categoria (categorias desativadas não aparecem na loja)
- 🗑️ Excluir categoria (produtos vinculados ficam sem categoria)

**Categorias iniciais incluídas no SQL:**

Roupas • Sapatos • Bolsas • Acessórios • Casa • Infantil • Diversos

As categorias ativas aparecem como filtros rápidos (pills) na loja pública.

---

## 📦 Produtos

No painel administrativo, acesse **Produtos** para:

- ✅ Cadastrar novo produto (título, descrição, preço, categoria, até 4 fotos)
- ✏️ Editar produto existente
- 📸 Enviar, substituir ou remover fotos individualmente
- 🔄 Ativar/desativar produto (checkbox)
- 🗑️ Excluir produto (remove fotos do servidor)

**Regras de exibição na loja pública:**
- O produto precisa estar **ativo**
- A categoria do produto precisa estar **ativa**
- Ambas as condições devem ser verdadeiras para o produto aparecer

---

## 💬 WhatsApp

O botão **"Mais Detalhes"** nos cards de produto usa o número:

```
+1 352 989 0272
```

E abre o WhatsApp com mensagem automática no formato:

> *Oi, bem vindo ao meu Bazar! Me chamo Joelma e você se interessou pelo produto [título] pelo preço de [preço]. Logo mais entro em contato com você!*

Para alterar o número do WhatsApp, edite a constante `WHATSAPP_NUMBER` em `config/helpers.php`.

---

## 🏠 Hospedagem Hostinger / cPanel

1. Acesse o **Gerenciador de Arquivos** no painel de controle
2. Envie o conteúdo do projeto para `public_html`
3. Crie o banco em **Bancos de dados MySQL** (usuário + banco)
4. Importe `database/database.sql` no **phpMyAdmin**
5. Edite `config/database.php` com host, banco, usuário e senha
6. Verifique se `uploads/products` está com permissão de escrita
7. Acesse `/admin/login.php` e comece a cadastrar categorias e produtos

---

## 🔒 Segurança Incluída

- 🔐 Login com sessão PHP (session_regenerate_id)
- 🔑 Senhas com `password_hash()` e `password_verify()`
- 🛡️ Consultas com PDO e **prepared statements** (proteção contra SQL Injection)
- 🔤 Escape de textos com `htmlspecialchars()` (proteção contra XSS)
- ✅ Validação de campos obrigatórios em formulários
- 🖼️ Upload limitado a **JPG, JPEG, PNG e WEBP** (verificação por `getimagesize`)
- 📏 Tamanho máximo de **2 MB** por foto
- 🔀 Renomeação automática das imagens (hash aleatório)
- 🗑️ Exclusão/substituição automática de fotos antigas ao editar produtos
- 🤖 `robots: noindex, nofollow` em todas as páginas administrativas

---

## 📁 Estrutura do Projeto

```
/
├── index.php                  # Redireciona para public/index.php
├── README.md
│
├── config/
│   ├── database.php           # Conexão PDO com MySQL
│   └── helpers.php            # Funções auxiliares (sessão, upload, formatação)
│
├── database/
│   └── database.sql           # Script completo de criação do banco
│
├── admin/
│   ├── auth.php               # Middleware de autenticação
│   ├── login.php              # Tela de login
│   ├── logout.php             # Encerrar sessão
│   ├── dashboard.php          # Resumo com estatísticas
│   ├── partials_nav.php       # Navegação compartilhada
│   ├── products.php           # Listagem de produtos
│   ├── product_create.php     # Cadastrar produto
│   ├── product_edit.php       # Editar produto
│   ├── product_delete.php     # Excluir produto
│   ├── product_form.php       # Formulário compartilhado de produto
│   ├── categories.php         # Listagem de categorias
│   ├── category_create.php    # Cadastrar categoria
│   ├── category_edit.php      # Editar categoria
│   ├── category_delete.php    # Excluir categoria
│   └── category_form.php      # Formulário compartilhado de categoria
│
├── public/
│   └── index.php              # Loja pública (vitrine de produtos)
│
├── uploads/
│   └── products/              # Fotos dos produtos (geradas pelo upload)
│
└── assets/
    ├── css/
    │   ├── style.css          # CSS da loja pública
    │   └── admin.css          # CSS do painel administrativo
    └── js/
        ├── main.js            # JS da loja (galeria, swipe, animações)
        └── admin.js           # JS do admin (validação, preview de fotos)
```

---

## 🎨 Design & UX

- **Tema**: Vermelho + branco, estilo marketplace feminino, acolhedor e moderno
- **Fontes**: Outfit (títulos) + Inter (corpo) via Google Fonts
- **Responsivo**: Desktop (4+ cards), Tablet (2-3 cards), Celular (2 ou 1 card)
- **Galeria**: Navegação por setas + swipe touch no celular + dots indicadores
- **Animações**: Cards com fade-in ao scroll, hover effects, coração pulsante no rodapé
- **Botão WhatsApp**: Verde com ícone SVG e efeito hover
- **Filtros**: Busca por texto, filtro por categoria (pills e dropdown), filtro por faixa de preço
