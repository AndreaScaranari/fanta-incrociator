# Fanta Incrociator - Project Knowledge

## 📋 Project Overview

**Nome**: Fanta Incrociator  
**Tipo**: Web Application  
**Developer**: Andrea Scaranari  
**Obiettivo**: Fornire uno strumento di analisi per il fantacalcio a listone con mercato frequente, aiutando gli utenti a identificare opportunità di acquisto basate sugli incroci di calendario e sul ranking delle squadre.

### Value Proposition

Il fantacalcio a listone permette di vendere/comprare giocatori sfruttando le fluttuazioni di mercato. Fanta Incrociator permette di:

- Individuare giocatori a basso costo con partite facili in arrivo
- Identificare squadre complementari per rotazioni strategiche
- Prendere decisioni basate su dati oggettivi (EasyScore)
- Sostituire titolari costosi con sorprese ad alto potenziale

---

## 🛠 Stack Tecnologico

### Frontend

- **Framework**: Vue.js 3 con **Composition API**
- **Build Tool**: Vite
- **Styling**: Bootstrap 5.3+
- **Note**: Non serve Nuxt perché non è necessaria la SEO

### Backend

- **Framework**: Laravel (latest stable version)
- **API**: RESTful architecture
- **Authentication**: Da definire (Laravel Sanctum consigliato per SPA)

### Database

- **DBMS**: MySQL
- **GUI Tool**: Opzioni disponibili:
  - PHPMyAdmin (già utilizzato)
  - HeidiSQL (alternativa più moderna e performante)
  - MySQL Workbench
  - DBeaver (open source, multi-database)

### DevOps

- **Containerizzazione**: Docker + Docker Compose
- **Ambiente**: Sostituzione di XAMPP con Docker per uniformità e portabilità
- **OS**: Windows
- **Terminal**: VSCode integrated terminal / PowerShell

### Hosting

- **Fase iniziale**: Locale (sviluppo)
- **Fase futura**: Hosting web con piccoli inserti pubblicitari

---

## 🎯 Funzionalità Core (MVP)

### 1. Ranking Dinamico delle Squadre ✅ Priority: HIGH

**Descrizione**: Sistema di classificazione delle squadre Serie A in fasce (tier).

**Tier List Iniziale**:

```javascript
{
  1: ["Juventus", "Inter", "Napoli", "Milan", "Roma"],
  1.5: ["Atalanta", "Bologna"],
  2: ["Como", "Fiorentina", "Lazio"],
  2.5: ["Udinese", "Torino", "Cagliari", "Parma", "Cremonese", "Sassuolo"],
  3: ["Lecce", "Pisa", "Genoa", "Verona"]
}
```

**API Requirements**:

- `GET /api/teams` - Elenco squadre con tier
- `GET /api/teams/{id}` - Dettaglio singola squadra
- `PUT /api/teams/{id}/tier` - Modifica tier di una squadra
- `POST /api/teams/reorder` - Riordino multiplo squadre

**Database Schema (teams)**:

```sql
id (PK)
nome VARCHAR(100)
tier DECIMAL(2,1)
logo_url VARCHAR(255) [optional]
created_at TIMESTAMP
updated_at TIMESTAMP
```

### 2. Calcolo EasyScore ✅ Priority: HIGH

**Algoritmo Attuale**:

```javascript
function getTierValue(squadra) {
  // Trova il tier della squadra
  // Valore base dal tierList
}

// Per ogni partita:
let valoreTier = getTierValue(avversario);
if (casa) {
  valoreTier += 0.5; // Bonus casa
}
easyScore += valoreTier;
```

**Logica**:

- Valore più ALTO = partite più FACILI
- Valore più BASSO = partite più DIFFICILI
- Bonus +0.5 se la squadra gioca IN CASA contro quell'avversario

**API Requirements**:

- `GET /api/easy-score?team={team}&giornate={n}` - Calcola EasyScore per n giornate successive
- `GET /api/easy-score/all?giornate={n}` - EasyScore di tutte le squadre

**Parametri**:

- Numero di giornate da considerare (user-defined)
- Squadra o tutte le squadre

### 3. Visualizzazione Grafica EasyScore ✅ Priority: HIGH

**Obiettivo**: Tabella colorata per comparare facilmente le squadre e trovare complementarità.

**Requisiti UI/UX**:

- Heatmap/tabella con colori dal verde (facile) al rosso (difficile)
- Vista comparativa: squadre in righe, giornate in colonne
- Filtro per numero di giornate future (3, 5, 8, 10)
- Ordinamento per EasyScore totale
- Highlight per squadre "complementari" (automatico o manuale)

**Tecnologie Suggerite**:

- Chart.js / ApexCharts per grafici
- CSS Grid + gradiente colori per heatmap
- Tabella responsive Bootstrap

### 4. Sezione Statistiche 📊 Priority: MEDIUM

**Metriche da Tracciare**:

**A. Performance Squadre**:

- Gol fatti / Gol subiti
- Punti ultimi 5 match (trend forma)
- Clean sheets per difese
- Media gol per partita

**B. Performance Giocatori**:

- Fantavoto medio ultime N giornate
- Trend crescita/decrescita valore
- Bonus/Malus accumulati

**API Esterne Necessarie**:

- Dati statistiche Serie A
- Valori fantacalcio giocatori
- (vedi sezione API Esterne)

---

## 🚀 Roadmap e Sviluppo Incrementale

### Phase 1: Setup & Infrastructure 🏗️

**Goal**: Ambiente funzionante con Docker

**Tasks**:

- [ ] Setup Docker Compose (PHP, MySQL, Nginx)
- [ ] Installazione Laravel + configurazione
- [ ] Setup Vue 3 + Vite
- [ ] Configurazione database e migrations
- [ ] Primo test "Hello World" full-stack

**Deliverable**: Container funzionanti, Laravel che risponde a Vue

---

### Phase 2: CRUD Squadre & Ranking 📝

**Goal**: Gestione dinamica del ranking

**Tasks**:

- [ ] Migration tabella `teams`
- [ ] Seeder con squadre Serie A e tier iniziali
- [ ] API Laravel per CRUD squadre
- [ ] Componenti Vue per visualizzare/modificare ranking
- [ ] Drag & drop per riordino tier (opzionale, nice-to-have)

**Deliverable**: Utente può modificare tier squadre tramite UI

---

### Phase 3: Calendario & EasyScore 📅

**Goal**: Calcolo e visualizzazione EasyScore

**Tasks**:

- [ ] Migration tabella `matches` (calendario)
- [ ] Integrazione API esterna per calendario Serie A
- [ ] Implementazione algoritmo EasyScore in Laravel
- [ ] API endpoint per EasyScore
- [ ] Componente Vue per selezione giornate
- [ ] Lista squadre con EasyScore

**Deliverable**: Sistema calcola EasyScore dinamicamente

---

### Phase 4: Visualizzazione Avanzata 🎨

**Goal**: Heatmap e grafici

**Tasks**:

- [ ] Componente Heatmap Vue
- [ ] Color coding (verde → rosso)
- [ ] Vista comparativa giornate
- [ ] Export/Screenshot tabella (opzionale)

**Deliverable**: Interfaccia grafica intuitiva per confronti

---

### Phase 5: Statistiche & Integrazioni 📊

**Goal**: Dati real-time da API esterne

**Tasks**:

- [ ] Integrazione API statistiche Serie A
- [ ] Tabella `players` e `player_stats`
- [ ] Cronjob/scheduler Laravel per update automatici
- [ ] Dashboard statistiche con grafici
- [ ] Filtri e ricerche avanzate

**Deliverable**: Statistiche aggiornate automaticamente

---

### Phase 6: Features Avanzate ⚡ (Future)

**A. EasyScore Potenziato**:

- Differenziazione difensori/attaccanti
- Considerare gol fatti/subiti avversario
- Peso diverso per casa/trasferta basato su stats reali

**B. Gestione Rosa Personale**:

- Salvataggio rosa utente
- Previsioni crescita valore
- Expected performance per periodo selezionato
- Alert su opportunità di mercato

**C. Integrazioni Bookmakers**:

- Quote scommesse per ranking più oggettivo
- Confronto quote con EasyScore
- Predictions basate su dati scommesse

---

## 🔌 API Esterne da Integrare

### 1. Calendario Serie A

**Necessità**: Scaricare fixture completo con date e orari

**Opzioni**:

- **API-Football** (RapidAPI): Dati completi, free tier limitato
- **Football-Data.org**: Free tier generoso, Serie A inclusa
- **TheSportsDB**: Gratuita ma meno aggiornata
- **Scraping Lega Serie A**: Possibile ma manutenzione complessa

**Raccomandazione**: Football-Data.org per iniziare (free tier: 10 req/min)

### 2. Statistiche Squadre

**Dati necessari**: Gol, punti, clean sheets, forma recente

**Fonte**: Stessa API del calendario (Football-Data.org copre anche stats)

### 3. Dati Fantacalcio

**Necessità**: Valori giocatori, fantavoti, bonus/malus

**Opzioni**:

- **Fantacalcio.it**: Potrebbero avere API ufficiali
- **Fantagazzetta**: Verificare disponibilità API
- **Scraping**: Ultima opzione, da usare responsabilmente

**Note**: Verificare ToS per uso dati, specialmente se il sito diventerà pubblico con ads

### 4. Integrazioni Future (Bookmakers)

- Odds API (aggregatore quote)
- BetFair API
- API singoli bookmakers

---

## 📐 Convenzioni e Best Practices

### Laravel Backend

**Structure**:

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── TeamController.php
│   │   │   ├── MatchController.php
│   │   │   ├── EasyScoreController.php
│   │   │   └── StatsController.php
│   └── Resources/
│       └── (API Resources per serializzazione)
├── Models/
│   ├── Team.php
│   ├── Match.php
│   ├── Player.php
│   └── PlayerStat.php
├── Services/
│   ├── EasyScoreService.php
│   └── ExternalApiService.php
└── Jobs/
    └── UpdateStatsJob.php (per cron)
```

**Naming Conventions**:

- Controllers: Singular + Controller (TeamController)
- Models: Singular, PascalCase (Team, Match)
- Routes API: Plural, kebab-case (`/api/easy-scores`)
- Methods: camelCase

**API Response Format**:

```json
{
  "success": true,
  "data": {},
  "message": "Optional message"
}
```

### Vue 3 Frontend

**Structure**:

```
src/
├── components/
│   ├── teams/
│   │   ├── TeamList.vue
│   │   ├── TeamRankingEditor.vue
│   ├── easyscore/
│   │   ├── EasyScoreTable.vue
│   │   ├── EasyScoreHeatmap.vue
│   └── stats/
│       └── StatsBoard.vue
├── composables/
│   ├── useTeams.js
│   ├── useEasyScore.js
│   └── useApi.js
├── views/
│   ├── Home.vue
│   ├── Ranking.vue
│   ├── EasyScore.vue
│   └── Stats.vue
├── services/
│   └── api.js (axios config)
└── App.vue
```

**Composition API Pattern**:

```javascript
// composables/useTeams.js
import { ref, computed } from "vue";
import api from "@/services/api";

export function useTeams() {
  const teams = ref([]);
  const loading = ref(false);

  const fetchTeams = async () => {
    loading.value = true;
    try {
      const response = await api.get("/teams");
      teams.value = response.data.data;
    } catch (error) {
      console.error(error);
    } finally {
      loading.value = false;
    }
  };

  return {
    teams,
    loading,
    fetchTeams,
  };
}
```

**Component Naming**: PascalCase (TeamList.vue)

### Database Conventions

**Tables**: plural, snake_case (teams, matches, player_stats)

**Columns**:

- Primary Key: `id` (auto-increment)
- Foreign Keys: `{table_singular}_id` (team_id, match_id)
- Timestamps: `created_at`, `updated_at` (Laravel convention)

**Indexes**: Su FK e colonne frequentemente filtrate

---

## 🐳 Docker Setup

### File docker-compose.yml (Suggerimento)

```yaml
version: "3.8"

services:
  app:
    build:
      context: ./docker/php
      dockerfile: Dockerfile
    container_name: fanta-incrociator-app
    volumes:
      - ./backend:/var/www/html
    networks:
      - fanta-network

  nginx:
    image: nginx:alpine
    container_name: fanta-incrociator-nginx
    ports:
      - "8080:80"
    volumes:
      - ./backend:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
    networks:
      - fanta-network

  mysql:
    image: mysql:8.0
    container_name: fanta-incrociator-mysql
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: fanta_incrociator
      MYSQL_USER: fanta_user
      MYSQL_PASSWORD: fanta_password
    ports:
      - "3306:3306"
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - fanta-network

  frontend:
    image: node:20-alpine
    container_name: fanta-incrociator-frontend
    working_dir: /app
    volumes:
      - ./frontend:/app
    ports:
      - "5173:5173"
    command: sh -c "npm install && npm run dev -- --host"
    networks:
      - fanta-network

volumes:
  mysql_data:

networks:
  fanta-network:
    driver: bridge
```

**PowerShell Commands**:

```powershell
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# View logs
docker-compose logs -f app

# Access app container
docker exec -it fanta-incrociator-app bash

# Access MySQL
docker exec -it fanta-incrociator-mysql mysql -u fanta_user -p
```

---

## 🧮 Algoritmo EasyScore - Dettagli

### Versione Attuale (da Prototipo)

```javascript
// Tier values
const tierList = {
  1: ["Juventus", "Inter", "Napoli", "Milan", "Roma"],      // Top clubs
  1.5: ["Atalanta", "Bologna"],                             // Upper-mid
  2: ["Como", "Fiorentina", "Lazio"],                       // Mid
  2.5: ["Udinese", "Torino", ...],                          // Lower-mid
  3: ["Lecce", "Pisa", "Genoa", "Verona"]                   // Bottom
}

// Calculate EasyScore
for each partita:
  valoreTier = getTierValue(avversario)  // Base difficulty
  if (squadra_gioca_in_casa):
    valoreTier += 0.5                     // Home advantage
  easyScore += valoreTier

// Lower score = easier fixtures
```

**Esempio**:

- Juventus (Tier 1) vs Como (casa) → 2 + 0.5 = 2.5
- Juventus (Tier 1) vs Como (trasferta) → 2 (no bonus)
- Lecce (Tier 3) vs Como (casa) → 2 + 0.5 = 2.5
- Lecce (Tier 3) vs Como (trasferta) → 2

### Versione Futura Potenziata

**Differenziazione Difesa/Attacco**:

```
EasyScore Attaccanti = f(tier avversario, gol_subiti_avversario, casa/trasferta)
EasyScore Difensori = f(tier avversario, gol_fatti_avversario, casa/trasferta)
```

---

## 💡 Note Tecniche Importanti

### Windows & PowerShell Specifics

**Path Handling**:

- Usare forward slash `/` anche su Windows quando possibile
- Laravel/PHP gestiscono automaticamente i path
- Docker monta volumi con sintassi Unix

**Comandi PowerShell Utili**:

```powershell
# Navigazione
cd C:\Users\Andrea\Projects\fanta-incrociator

# Permissions (se necessario per Docker)
Set-ExecutionPolicy RemoteSigned -Scope CurrentUser

# Laravel Artisan
php artisan migrate
php artisan db:seed
php artisan serve

# Composer
composer install
composer update

# NPM (per Vue)
npm install
npm run dev
npm run build
```

### VSCode Extensions Consigliate

**PHP/Laravel**:

- Laravel Extension Pack
- PHP Intelephense
- Laravel Blade Snippets

**Vue**:

- Vue Language Features (Volar)
- Vue VSCode Snippets

**General**:

- Docker (Microsoft)
- ESLint
- Prettier
- GitLens

### Git Workflow

**.gitignore** deve includere:

```
# Laravel
/vendor
.env
/storage/*.key
/public/hot

# Vue/Node
node_modules/
dist/
.DS_Store

# Docker
docker/mysql/data/

# IDE
.vscode/
.idea/
```

---

## 🎨 UI/UX Guidelines

### Color Scheme (EasyScore Heatmap)

**Scala Colori**:

- **Verde scuro** (#2d6a4f): EasyScore alto (partite facili)
- **Verde**: (#52b788)
- **Giallo**: (#fee440): EasyScore medio
- **Arancione**: (#fb8500)
- **Rosso**: (#d62828): EasyScore molto basso (partite difficili)

### Layout Priorità

1. **Dashboard principale**: Panoramica veloce EasyScore top teams
2. **Heatmap**: Vista comparativa dettagliata
3. **Ranking editor**: Pagina dedicata per modifiche tier
4. **Stats**: Sezione analytics approfondita

### Responsive Design

- Mobile-first approach (molti utenti fanno fantacalcio da smartphone)
- Tabelle scrollabili orizzontalmente su mobile
- Grafici ridimensionabili

---

## 📝 Checklist per Ogni Nuova Feature

Quando sviluppo una nuova funzionalità:

- [ ] Migration database creata e testata
- [ ] Model Laravel con relationships
- [ ] Controller con metodi CRUD necessari
- [ ] Route API registrate
- [ ] API Resource per risposta (se necessario)
- [ ] Validazione request (FormRequest)
- [ ] Composable Vue per logica riutilizzabile
- [ ] Componente Vue per UI
- [ ] Integrazione nell'app Vue (route, navigation)
- [ ] Test funzionalità (manuale o automated)
- [ ] Documentazione API (se serve)

---

## 📚 Risorse Utili

### Documentazione Ufficiale

- [Vue 3 Docs](https://vuejs.org/)
- [Laravel Docs](https://laravel.com/docs)
- [Vite](https://vitejs.dev/)
- [Docker Compose](https://docs.docker.com/compose/)

### Tutorial & Guide

- Laracasts (Laravel + Vue)
- Vue Mastery (Composition API)
- Docker for PHP Developers

### Community

- Laravel Italia (Facebook group)
- Vue.js Italia (Discord)
- Stack Overflow

---

## 🎯 Success Metrics

**MVP Completed When**:

- ✅ Utente può modificare ranking squadre
- ✅ Sistema calcola EasyScore per N giornate
- ✅ Heatmap visualizza dati in modo intuitivo
- ✅ Calendario Serie A integrato e aggiornabile

**Phase 2 Success**:

- ✅ Statistiche real-time integrate
- ✅ Sistema suggerisce opportunità di mercato
- ✅ Performance ottimizzate (< 2s load time)

**Production Ready**:

- ✅ Hosting configurato
- ✅ Analytics integrato
- ✅ Ads system implementato
- ✅ Mobile-responsive completo
- ✅ 50+ utenti beta test positivi

---

## 🚨 Known Issues & Limitations (Prototipo Attuale)

Dal file HTML prototipo:

1. **Calendario hardcoded**: Solo giornate 7-11 presenti
2. **Nessuna persistenza**: Modifiche tier perdute al refresh
3. **No API esterne**: Dati manuali e statici
4. **UI basica**: Solo lista testuale, no grafici
5. **No filtri**: Impossibile scegliere range giornate
6. **Case sensitivity**: Nomi squadre devono matchare esattamente

**Tutti questi problemi verranno risolti nella webapp completa.**

---

## 📞 Support & Collaboration

**Developer**: Andrea Scaranari
**Project Start**: Novembre 2025
**Status**: Planning & Architecture phase

## 🚀 Setup Completato (Step 1 - Novembre 2025)

### Ambiente Docker Attivo

**Infrastructure:**

- ✅ Docker Compose con 4 container (app PHP, nginx, mysql, frontend Node)
- ✅ Laravel 11 con PHP 8.2-FPM
- ✅ Vue 3 con Vite
- ✅ MySQL 8.0
- ✅ Nginx web server

**Configurazioni:**

- Backend accessible: http://localhost:8080
- Frontend accessible: http://localhost:5173
- Database: `fanta_incrociator` (user: fanta_user)
- Laravel migrations: executed (tables created)

**Current Structure:**

```
C:\dev\fanta-incrociator/
├── backend/          (Laravel completo)
├── frontend/         (Vue 3 + Vite completo)
├── docker/
│   ├── php/Dockerfile
│   └── nginx/default.conf
├── docker-compose.yml
└── .gitignore
```

**Dockerfile Finale (Working):**

- PHP 8.2-FPM Alpine
- Estensioni: pdo, pdo_mysql, bcmath
- Composer installato
- Nota: json e tokenizer sono built-in, fileinfo rimosso (non essenziale)

**Known Working Commands:**

```powershell
docker-compose up -d        # Avvia tutto
docker-compose down         # Ferma tutto
docker exec -it fanta-incrociator-app sh    # Accedi al container
php artisan migrate         # Esegui migrazioni (dentro container)
```

### Step 2 - Prossimo Obiettivo

Implementare il ranking dinamico delle squadre e il calcolo EasyScore.
**Next Steps**: Docker setup + Laravel installation

---

## 🔄 Version History

- **v0.1 (Prototype)**: File HTML standalone con JS vanilla
- **v1.0 (Target)**: Vue 3 + Laravel + Docker - MVP complete
- **v2.0 (Future)**: Advanced stats, user accounts, predictions

---

_Questo documento è living documentation e verrà aggiornato durante lo sviluppo del progetto._
