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

## 3. Padronização Visual & Componentes Oficiais

1. **Galeria do Produto:** Apenas a foto do calçado (`cover`) é mantida na galeria de fotos do produto, eliminando retalhos de couro e triângulos de bordado da visualização de capa/carrossel.
2. **Cores & Couros Disponíveis (`LeatherSwatchHelper`):**
   - **Nobuck (`7000`, `7007`, `7010`):** Exibe o mostruário oficial de 4 opções: Café, Camel, Ferrugem e Castor com fotos reais WebP e modal de textura HD.
   - **Látego (`7001`):** Exibe o mostruário oficial de 4 opções: Pinhão, Chocolate, Palha e Preto.
   - **Sintético / Bidin / Camurça (`7006-14`, `7011`, `7012`):** Swatches reais WebP gerados em `public/images/swatches/sintetico/` (Preto/Bidin, Café, Caramelo) com tonalidades e texturas fiéis.
3. **Opções de Bordados Disponíveis (`EmbroideryHelper`):**
   - **Modelos com Grade Oficial (`7007`, `7010`):** Exibem a grade completa **"Desenhos & Pespontos (6 opções)"** (Padrão A, B, C, D, F, G).
   - **Modelo 7011:** Configurado com **opção exclusiva única (Bordado Padrão A)** utilizando a foto real `Novos Cadastros/Linha Sintetico/bordado padrão a.jfif` (badge de `1 opção`).
   - **Modelos sem Bordado (`7000`, `7001`, `7006-14`, `7012`):** Não exibem a seção de bordados.
   - Subtítulo normalizado para *"Opção de Bordado Disponível"* e especificação *"Opção de Bordado Personalizado"* apenas para modelos que possuem bordado.

---

## 4. Tarefas Executadas

- [x] **Disponibilização dos Ativos no Container:** Mapeado via cópia para `storage/novos-cadastros`.
- [x] **Criação da Categoria `sintetico`:** Cadastrada com ID 8 conforme convenção de produção em `cj-calcados-prd.sql`.
- [x] **Aprimoramento do `DocxProductParser`:** Suporte a traços Unicode (`–`) na grade de medidas, `Cor(es).`, detecção de ausência de CA e correção do título de 7010.
- [x] **Comando de Importação Idempotente:** Criado `php artisan catalog:import-novos-cadastros` e seeder `NovosCadastrosSeeder`.
- [x] **Padronização das Galerias:** Apenas a foto do calçado fica em `product_images`, evitando poluição visual no carrossel.
- [x] **Geração de Swatches Sintéticos WebP:** Imagens otimizadas criadas em `public/images/swatches/sintetico/` (incluindo as texturas oficiais de `7011 - couro sintético cor café.png` e `7012 - couro camurça cor caramelo.png`).
- [x] **Integração com Mostruário de Couros & Bordados:**
  - `LeatherSwatchHelper`: Suporte completo a Sintético (Bidin, Café, Camurça Caramelo) e mapeamento oficial dos couros.
  - `EmbroideryHelper`: 7007 e 7010 com grade de 6 opções; 7011 com Bordado Padrão A exclusivo; 7012 sem bordado.
- [x] **Sincronização Title Case:** Mapeados novos solados, couros e cores em `SyncProductVariations.php`.
- [x] **Atualização do Modelo 7006:** Título, código e slug normalizados sem o sufixo de variante `/14` (`7006 BOTINA BIDIN RELAX SOLA DE BORRACHA`), e amostra de couro Bidin atualizada com o novo arquivo `7006/bidin.jpg`.
- [x] **Atualização do Modelo 7012:** Foto da bota substituída pelo novo `7012.jpg`, textura atualizada para `7012 - couro camurça cor caramelo.png` e opções de bordado removidas.
- [x] **Atualização do Modelo 7007:** Foto da bota substituída por `7007.jpg`, opção de couro configurada exclusivamente para `Nobuck Café` com amostra atualizada de `nobuk café.jpg`, e bordado configurado exclusivamente para `Bordado Padrão B` com a imagem oficial `borbado padrão B.jfif`.

---

## 5. ✅ Conclusão e Testes
- 7 novos produtos cadastrados e ativos no banco de dados.
- Mostruários de Couro e Opções de Bordados 100% alinhados aos componentes padrão de produção.
- Todos os 82 testes automatizados passando com sucesso.
- Data: 2026-09-21
