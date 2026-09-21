# Planejamento & Execução: Cadastro de Novos Produtos

**Data:** 2026-09-21  
**Status:** ✅ Concluído e Verificado  
**Origem:** `Novos Cadastros/` (Coturnos, LINHA PASSEIO, Linha Sintetico, linha infatil)

---

## 1. Visão Geral

Mapeamento, ingestão e estruturação dos 7 novos produtos fornecidos na pasta `Novos Cadastros/`, com atenção à categorização (incluindo a categoria `sintetico` de produção [cj-calcados-prd.sql](file:///Users/danilosantos/Documents/Workspace/cj-calcados/cj-calcados-prd.sql)), variações de material/cor, amostras de bordados e tratamento de imagens com geração de variantes WebP locais.

---

## 2. Inventário dos Novos Produtos Cadastrados

| Pasta | Código | Nome do Produto | Material / Acabamento | Cores | Grade | Categoria | Coleção | Imagens |
|---|---|---|---|---|---|---|---|---|
| **Coturnos** | `7000` | Coturno Adventure Nobuck | Couro Nobuck, Sola Adventure | Café | 35 ao 44 (11 tam.) | `coturno` | `premium` | 2 WebP |
| **Coturnos** | `7001` | Coturno Adventure Látego | Couro Látego, Sola Adventure | Café | 35 ao 44 (11 tam.) | `coturno` | `premium` | 2 WebP |
| **LINHA PASSEIO** | `7007` | Botina Nobuck Café Sola Vaquejada Bordado Linhas | Couro Nobuck, Sola Vaquejada, Bordado Linhas | Chocolate | 36 ao 44 (11 tam.) | `botina-passeio` | `premium` | 3 WebP |
| **Linha Sintetico** | `7006-14` | Botina Bidin Relax Sola de Borracha (Sem C.A) | Sintético Bidin, Sola Segurança | Preto | 35 ao 45 (11 tam.) | `sintetico` | `classica` | 2 WebP |
| **linha infatil** | `7010` | Botina Infantil Texana Nobuck Sola Ram | Couro Nobuck, Sola Ram, Bordado Texana A | Café | 22 ao 32 (14 tam.) | `infantil` | `infantil` | 3 WebP |
| **Linha Sintetico** | `7011` | Botina Sintético Infantil Texana Sola Ram | Sintético Café, Sola Ram, Bordado Texana A | Café | 22 ao 32 (14 tam.) | `sintetico` | `infantil` | 3 WebP |
| **Linha Sintetico** | `7012` | Botina Texana Camurça Caramelo Sola Ram Café | Camurça Caramelo Sintético, Sola Ram | Camurça Caramelo | 36 ao 44 (11 tam.) | `sintetico` | `classica` | 3 WebP |

---

## 3. Mapeamento de Ativos e Imagens

1. **7000 (Coturno Nobuck):** Capa `7000.webp` + Amostra de Couro `7000_nobuk_cafe.webp`
2. **7001 (Coturno Látego):** Capa `7001.webp` + Amostra de Couro `7001_latego_chocolate.webp`
3. **7007 (Botina Passeio Vaquejada Bordada):** Capa `7007.webp` + Amostra de Couro `7007_nobuk_cafe.webp` + Detalhe do Bordado Linhas B `7007_borbado_padrao_b.webp`
4. **7006-14 (Bidin):** Capa `7006.webp` + Amostra de Sintético Bidin `7006_bidin.webp`
5. **7010 (Infantil Texana Nobuck):** Capa `7010.webp` + Amostra de Couro `7010_nobuk_cafe.webp` + Detalhe do Bordado Texana A `7010_bordado_padrao_a.webp`
6. **7011 (Sintético Infantil Texana):** Capa `7011.webp` + Amostra Sintético `7011_sintetico_cafe.webp` + Detalhe do Bordado Texana A `7011_bordado_padrao_a.webp`
7. **7012 (Texana Camurça Caramelo):** Capa `7012.webp` + Amostra Camurça `7012_cor_camurca_caramelo.webp` + Detalhe do Bordado Texana A `7012_bordado_padrao_a.webp`

---

## 4. Tarefas Executadas

- [x] **Disponibilização dos Ativos no Container:** Mapeado via cópia para `storage/novos-cadastros`.
- [x] **Criação da Categoria `sintetico`:** Cadastrada com ID 8 conforme convenção de produção em `cj-calcados-prd.sql`.
- [x] **Aprimoramento do `DocxProductParser`:** Suporte a traços Unicode (`–`) na grade de medidas, `Cor(es).`, detecção de ausência de CA e correção do título de 7010.
- [x] **Comando de Importação Idempotente:** Criado `php artisan catalog:import-novos-cadastros` e seeder `NovosCadastrosSeeder`.
- [x] **Ingestão WebP:** Todas as 18 imagens (capas + variações de material e bordado) processadas via `ImageIngestor` com 3 variantes cada (`original`, `thumb`, `cover`).
- [x] **Sincronização Title Case:** Mapeados novos solados, couros e cores em `SyncProductVariations.php`.

---

## 5. ✅ PHASE X COMPLETE
- 7 novos produtos cadastrados e ativos no banco de dados.
- Capas e galerias de imagens geradas em formato WebP local em `storage/app/public/products/{id}/`.
- Rotas de categoria (`/categorias/sintetico`) e produto (`/produtos/...`) respondendo HTTP 200 OK.
- Data: 2026-09-21
