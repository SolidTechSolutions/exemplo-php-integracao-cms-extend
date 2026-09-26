# 🇧🇷 SolidSign API - Exemplo de Integração: Estender Assinatura CMS/CAdES (PHP)

Faz upgrade de uma assinatura CMS/CAdES existente para um nível de baseline maior, sem re-assinar o documento.

## Requisitos

- PHP 8.1+, Slim 4, Guzzle
- Um token JWT válido (`POST /solidsign/auth/token`)
- Front-end de referência (opcional): [`exemplo-react-cms-extend`](https://github.com/SolidTechSolutions/exemplo-react-cms-extend)

## Como rodar

```bash
composer install && cp .env.example .env   # editar .env
php -S 0.0.0.0:8097 -t public
```

## Como funciona

`POST /api/cms/extend/form` — recebe um formulário (`multipart/form-data`) e repassa pra SolidSign API real, devolvendo a resposta como está.

## Variáveis do formulário

| Campo | Significado | Obrigatório |
|---|---|---|
| `document[i]` | Assinatura(s) CMS a estender | Sim |
| `targetLevel` | Nível alvo (ex.: `ADRA`, `CADES_LTA`) | Sim |
| `signatureIndex` | Índice da assinatura a estender | Não |
| `authorization` | Token JWT (Bearer) — sobrescreve o configurado | Não |
| `baseUrl` | URL base da SolidSign API — sobrescreve o configurado | Não |

---

# 🇬🇧 SolidSign API - Integration Example: Extend CMS/CAdES Signature (PHP)

Upgrades an existing CMS/CAdES signature to a higher baseline level, without re-signing the document.

## Requirements

- PHP 8.1+, Slim 4, Guzzle
- A valid JWT token (`POST /solidsign/auth/token`)
- Reference front-end (optional): [`exemplo-react-cms-extend`](https://github.com/SolidTechSolutions/exemplo-react-cms-extend)

## Running

```bash
composer install && cp .env.example .env   # edit .env
php -S 0.0.0.0:8097 -t public
```

## How it works

`POST /api/cms/extend/form` — accepts a form (`multipart/form-data`) and forwards it to the real SolidSign API, returning the response as-is.

## Form fields

| Field | Meaning | Required |
|---|---|---|
| `document[i]` | CMS signature(s) to extend | Yes |
| `targetLevel` | Target level (e.g. `ADRA`, `CADES_LTA`) | Yes |
| `signatureIndex` | Index of the signature to extend | No |
| `authorization` | JWT (Bearer) token — overrides the configured one | No |
| `baseUrl` | SolidSign API base URL — overrides the configured one | No |
