# Fanta Incrociator - Project Knowledge v3

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
- **Styling**: Bootstrap 5.3+ + SCSS custom
- **HTTP Client**: Axios 1.8.2
- **Router**: Vue Router 4.4.0

### Backend

- **Framework**: Laravel 12 (latest)
- **API**: RESTful architecture
- **Authentication**: Da definire (Probabilmente userò Laravel Sanctum)
- **PHP Version**: 8.2-FPM

### Database

- **DBMS**: MySQL 8.0
- **GUI Tool**: PHPMyAdmin (attualmente in uso)

### DevOps

- **Containerizzazione**: Docker + Docker Compose
- **Ambiente**: Docker
- **OS**: Windows
- **Terminal**: VSCode integrated terminal / PowerShell

### Hosting

- **Fase iniziale**: Locale (sviluppo)
- **Fase futura**: Hosting web con piccoli inserti pubblicitari

---

## 🎯 Funzionalità Core (MVP)

### 1. Ranking Dinamico delle Squadre

**Status**: ✅ COMPLETATO

**Descrizione**: Sistema di classificazione delle squadre Serie A in fasce (tier).

**Implementazione**:

- ✅ Migration `teams` creata e funzionante
- ✅ Seeder con 20 squadre Serie A
- ✅ Model `Team.php` con relationships
- ✅ API CRUD completa
- ✅ Auto-ricalcolo EasyScore dopo modifica tier (sia modifica tier singola che massiva)

**Tier List**:

```javascript
{
  1.0: ["Inter", "Juventus", "Milan", "Napoli", "Roma"],
  1.5: ["Atalanta", "Bologna", "Como"],
  2.0: ["Atalanta", "Lazio", "Sassuolo", "Torino", "Udinese"],
  2.5: ["Cagliari", "Cremonese", "Fiorentina", "Lecce"],
  3.0: ["Genoa", "Parma", "Pisa", "Verona"]
}
```

**API Endpoints**:

- `GET /api/teams` - Elenco squadre con tier
- `GET /api/teams/{id}` - Dettaglio singola squadra
- `PUT /api/teams/{id}/tier` - Modifica tier di una squadra (+ auto-ricalcolo ES)
- `POST /api/teams/reorder` - Riordino multiplo squadre (+ auto-ricalcolo ES)

**Database Schema (teams)**:

```sql
id BIGINT UNSIGNED (PK, auto-increment)
nome VARCHAR(100) UNIQUE
tier DECIMAL(2,1)
logo_url VARCHAR(255) NULL
created_at TIMESTAMP
updated_at TIMESTAMP
```

---

### 2. Calendario Serie A

**Status**: ✅ COMPLETATO

**Implementazione**:

- ✅ Migration `games` con colonne EasyScore
- ✅ Service `FootballDataService` per importazione API
- ✅ Command `import:serieagames` per caricare calendario
- ✅ Auto-aggiornamento giornata corrente da API

**API Esterna**: Football-Data.org (free tier)

**Database Schema (games)**:

```sql
id BIGINT UNSIGNED (PK)
home_team_id BIGINT UNSIGNED (FK → teams)
away_team_id BIGINT UNSIGNED (FK → teams)
giornata TINYINT UNSIGNED
data_partita DATETIME NULL
season SMALLINT UNSIGNED DEFAULT 2025
home_easy_score DECIMAL(3,1) NULL
away_easy_score DECIMAL(3,1) NULL
home_goals TINYINT UNSIGNED NULL
away_goals TINYINT UNSIGNED NULL
created_at TIMESTAMP
updated_at TIMESTAMP

Indexes:
- giornata
- season, giornata
- home_team_id, season
- away_team_id, season

Unique:
- home_team_id, giornata, season
- away_team_id, giornata, season
```

**Command Laravel**:

```bash
php artisan import:serieagames [season]
```

**Features**:

- Importa 380 partite stagione completa
- Aggiorna automaticamente giornata corrente
- Gestisce partite duplicate

---

### 3. Calcolo EasyScore

**Status**: ✅ COMPLETATO

**Algoritmo**:

```javascript
// Per ogni partita:
home_easy_score = away_team.tier + 0.5; // Bonus casa
away_easy_score = home_team.tier; // No bonus trasferta

// Esempio:
// Inter (Tier 1.0) vs Lecce (Tier 3.0) in casa
// home_easy_score (Inter) = 3.0 + 0.5 = 3.5
// away_easy_score (Lecce) = 1.0
```

**Logica**:

- Valore più ALTO = partita più FACILE
- Valore più BASSO = partita più DIFFICILE
- Bonus +0.5 per partite in casa

**Implementazione**:

- ✅ Service `EasyScoreService` con logica calcolo
- ✅ Command `calculate:easyscore` per ricalcolo batch
- ✅ Auto-ricalcolo dopo import calendario
- ✅ Auto-ricalcolo dopo modifica tier squadre
- ✅ Eager loading per performance (`with(['homeTeam', 'awayTeam'])`)

**Command Laravel**:

```bash
php artisan calculate:easyscore
```

---

### 4. Giornata Corrente

**Status**: ✅ COMPLETATO

**Implementazione**:

- ✅ Tabella `settings` per configurazioni globali
- ✅ Model `Setting` con metodi `get()` e `set()`
- ✅ Auto-aggiornamento giornata durante import calendario
- ✅ API endpoint per frontend

**Database Schema (settings)**:

```sql
id BIGINT UNSIGNED (PK)
key VARCHAR(255) UNIQUE
value TEXT NULL
created_at TIMESTAMP
updated_at TIMESTAMP
```

**API Endpoint**:

```
GET /api/current-giornata
Response: { success: true, data: { giornata: 12 } }
```

**Utilizzo**:

```php
// Set
Setting::set('current_giornata', $currentGiornata);

// Get
$giornata = Setting::get('current_giornata', 1);
```

---

### 5. Visualizzazione EasyScore

**Status**: ✅ COMPLETATO

**Obiettivo**: Tabella interattiva con heatmap per comparare facilmente le squadre.

**Features Implementate**:

#### A. Filtri Giornate

- ✅ Select "Da Giornata" con date formattate (DD/MM/YYYY)
- ✅ Select "A Giornata" con date formattate
- ✅ Default: dalla giornata corrente alle prossime 5
- ✅ Giornata corrente selezionata ed evidenziata con "(Corrente)"

#### B. Filtro Squadre

- ✅ Checkbox per ogni squadra (ordinate alfabeticamente)
- ✅ Pulsante "Seleziona Tutte" (default: tutte selezionate)
- ✅ Pulsante "Deseleziona Tutte"
- ✅ Filtraggio reattivo della tabella

#### C. Tabella EasyScore

- ✅ Colonna squadra (nome)
- ✅ Colonna EasyScore Medio (media degli ES nel range)
- ✅ Colonne giornate con avversario + icona casa/trasferta
- ✅ Color coding automatico basato su tier SCSS

#### D. Riga Totali

- ✅ Ultima riga tabella con conteggio squadre ES ≥ 2.0 per giornata
- ✅ Conta solo squadre filtrate
- ✅ Background grigio

#### E. Info e Legenda

- ✅ Alert informativo con numero giornate e squadre visualizzate
- ✅ Legenda con spiegazione icone e colori
- ✅ Messaggio "Nessuna squadra selezionata" se filtro vuoto

**Color Coding (classi SCSS)**:

```scss
$tier-1-0: #00830b !important; // Verde scuro (ES ≥ 3.0)
$tier-1-5: #28c835 !important; // Verde (ES ≥ 2.5)
$tier-2-0: #cbb900 !important; // Giallo (ES ≥ 2.0)
$tier-2-5: #fb8500 !important; // Arancione (ES ≥ 1.5)
$tier-3-0: #d62828 !important; // Rosso (ES < 1.5)
```

**Icone**:

- 🏠 = Partita in casa (font-weight: bold)
- ✈️ = Partita in trasferta

**Composable `useEasyScore.js`**:

Gestisce tutta la logica:

- Caricamento dati parallelo (games, teams, giornata corrente)
- Filtraggio partite per squadra e range giornate
- Calcolo EasyScore medio per squadra
- Generazione tabella completa con dati giornata per giornata
- Filtraggio tabella basato su checkbox
- Calcolo totali per giornata

**Key Functions**:

- `fetchAllData()` - Carica tutti i dati iniziali
- `getTeamMatches(teamId)` - Filtra partite di una squadra
- `calculateTeamEasyScore(teamId)` - Calcola media ES squadra
- `getOpponentForGiornata(teamId, giornata)` - Trova avversario
- `getGiornataDate(giornata)` - Formatta data giornata
- `selectAllTeams()` / `deselectAllTeams()` - Gestione checkbox

**Computed Properties**:

- `easyScoreTable` - Tabella completa (tutte le squadre)
- `filteredEasyScoreTable` - Tabella filtrata (solo squadre selezionate)
- `availableGiornate` - Array giornate con date formattate
- `easyScoreTotals` - Conteggio squadre ES ≥ 2.5 per giornata
- `sortedTeams` - Squadre ordinate alfabeticamente

---

## 🚀 Roadmap e Sviluppo

### Phase 1: Setup & Infrastructure

**Goal**: Ambiente funzionante con Docker

**Status**: ✅ COMPLETATO

- ✅ Docker Compose con 5 container (PHP, MySQL, Nginx, Node, PhpMyAdmin)
- ✅ Laravel 12 funzionante
- ✅ Vue 3 + Vite funzionante
- ✅ Database MySQL configurato

---

### Phase 2: CRUD Squadre & Ranking

**Goal**: Gestione dinamica del ranking

**Status**: ✅ COMPLETATO

**Tasks Completate**:

- ✅ Migration tabella `teams`
- ✅ Seeder con squadre Serie A e tier iniziali
- ✅ Model `Team.php` con relationships
- ✅ Controller `TeamController.php` con CRUD
- ✅ Routes API registrate
- ✅ Composable `useTeams.js`
- ✅ Service `api.js` per Axios
- ✅ Componenti Vue 3:
  - `views/TeamList.vue` - Homepage con lista squadre per tier
  - `views/TeamRankingEditor.vue` - Editor tier (batch update)
  - `components/Titolone.vue` - Header
  - `components/Loader.vue` - Spinner
  - `components/ErrorMsg.vue` - Errori
  - `components/WarningMsg.vue` - Warning
  - `components/TeamsRankingList.vue` - Lista squadre per tier
  - `components/UpdateButton.vue` - Pulsante aggiorna
- ✅ Router Vue 3 con title dinamici
- ✅ Bootstrap 5.3 + SCSS integrati
- ✅ Alias Vite (`@/`) configurati

---

### Phase 3: Calendario & EasyScore

**Goal**: Calcolo e visualizzazione EasyScore completa

**Status**: ✅ COMPLETATO

**Tasks Completate**:

- ✅ Migration tabella `games` con colonne EasyScore
- ✅ Model `Game.php` con relationships e scopes
- ✅ Service `FootballDataService` per API Football-Data.org
- ✅ Command `ImportSerieAGames` per import calendario
- ✅ Command `CalculateEasyScore` per calcolo batch
- ✅ Service `EasyScoreService` con algoritmo calcolo
- ✅ Migration tabella `settings`
- ✅ Model `Setting.php` con helper methods
- ✅ Controller `GameController.php` per API games
- ✅ Controller `SettingController.php` per giornata corrente
- ✅ Auto-ricalcolo EasyScore dopo import/modifica tier
- ✅ Composable `useEasyScore.js` con logica completa
- ✅ View `EasyScore.vue` con tabella interattiva
- ✅ Filtri giornate con date formattate
- ✅ Filtro squadre con checkbox
- ✅ Color coding basato su tier SCSS
- ✅ Riga totali con conteggio ES ≥ 2.5
- ✅ Legenda e info visualizzate
- ✅ Responsive design

**Deliverable**: ✅ Sistema completo di analisi EasyScore con UI interattiva

---

### Phase X: Visualizzazione Avanzata ⏳ FUTURA

**Goal**: Grafici e analytics avanzate

**Tasks Pianificati**:

- [ ] Grafici trend EasyScore nel tempo
- [ ] Comparazione diretta tra 2 squadre
- [ ] Export/Screenshot tabella
- [ ] Vista calendario grafica
- [ ] Heatmap alternativa con Chart.js

---

### Phase Y: Statistiche & Integrazioni ⏳ FUTURA

**Goal**: Dati real-time da API esterne

**Tasks Pianificati**:

- [ ] Integrazione statistiche gol fatti/subiti
- [ ] Tabella `players` e `player_stats`
- [ ] Cronjob/scheduler Laravel per update automatici
- [ ] Dashboard statistiche con grafici
- [ ] Filtri e ricerche avanzate

---

### Phase Z: Features Avanzate ⏳ FUTURA

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

## 📌 API Esterne Integrate

### 1. Football-Data.org ✅ IN USO

**Utilizzo**: Calendario Serie A completo con date e risultati

**Endpoint**: `https://api.football-data.org/v4/competitions/2019/matches`

**Features**:

- ✅ Import 380 partite stagione
- ✅ Aggiornamento giornata corrente (`currentMatchday`)
- ✅ Date partite formattate
- ✅ Mapping nomi squadre

**API Key**: Memorizzata in `.env` (variabile `FOOTBALL_DATA_API_KEY`)

**Free Tier**: 10 requests/minute

---

## 🏗 Convenzioni e Best Practices

### Laravel Backend

**Structure Attuale**:

```
app/
├── Console/
│   └── Commands/
│       ├── ImportSerieAGames.php ✅
│       └── CalculateEasyScore.php ✅
├── Http/
│   └── Controllers/
│       └── Api/
│           ├── TeamController.php ✅
│           ├── GameController.php ✅
│           └── SettingController.php ✅
├── Models/
│   ├── Team.php ✅
│   ├── Game.php ✅
│   └── Setting.php ✅
└── Services/
    ├── FootballDataService.php ✅
    └── EasyScoreService.php ✅
```

**Naming Conventions**:

- Controllers: Singular + Controller (TeamController)
- Models: Singular, PascalCase (Team, Game)
- Routes API: Plural, kebab-case (`/api/teams`)
- Methods: camelCase
- Commands: VerbNoun (ImportSerieAGames)

**API Response Format**:

```json
{
  "success": true,
  "data": {},
  "message": "Optional message"
}
```

---

### Vue 3 Frontend

**Structure Attuale**:

```
src/
├── views/
│   ├── TeamList.vue ✅
│   ├── TeamRankingEditor.vue ✅
│   └── EasyScore.vue ✅
├── components/
│   ├── Titolone.vue ✅
│   ├── Loader.vue ✅
│   ├── ErrorMsg.vue ✅
│   ├── WarningMsg.vue ✅
│   ├── UpdateButton.vue ✅
│   └── TeamsRankingList.vue ✅
├── composables/
│   ├── useTeams.js ✅
│   └── useEasyScore.js ✅
├── services/
│   └── api.js ✅
├── router/
│   └── index.js ✅
├── assets/
│   └── style/
│       ├── _colors.scss ✅
│       └── style.scss ✅
├── App.vue ✅
└── main.js ✅
```

**Composition API Pattern**:

```javascript
// Composable pattern
export function useExample() {
  const data = ref([]);
  const loading = ref(false);
  const error = ref(null);

  const fetchData = async () => {
    loading.value = true;
    try {
      const response = await api.get("/endpoint");
      data.value = response.data.data;
    } catch (err) {
      error.value = err.message;
    } finally {
      loading.value = false;
    }
  };

  return { data, loading, error, fetchData };
}
```

**Component Naming**: PascalCase (TeamList.vue, UpdateButton.vue)

---

### Database Conventions

**Tables**: plural, snake_case (teams, games, settings)

**Columns**:

- Primary Key: `id` (auto-increment)
- Foreign Keys: `{table_singular}_id` (team_id, game_id)
- Timestamps: `created_at`, `updated_at` (Laravel convention)

**Indexes**: Su FK e colonne frequentemente filtrate

---

## 🐳 Docker Setup - COMPLETATO

### Servizi Attivi

5 container funzionanti:

```yaml
services:
  app: # PHP 8.2-FPM con Laravel
  nginx: # Web server (porta 8080)
  mysql: # Database (porta 3306)
  frontend: # Node 20 Alpine con Vue 3 + Vite (porta 5173)
  phpmyadmin: # GUI Database (porta 8081)
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
docker exec -it fanta-incrociator-app sh

# Access MySQL
docker exec -it fanta-incrociator-mysql mysql -u fanta_user -p

# Access Frontend
docker exec -it fanta-incrociator-frontend sh
```

---

## 🎨 Styling - COMPLETATO

### Color Palette (\_colors.scss)

```scss
// Palette Sito
$purple: #7b0eae;
$black: #080708;
$white: #e6e8e6;
$full-white: #ffffff;
$blue: #7a89c2;
$green: #4f7146;

// Colori Tier (con !important per override Bootstrap)
$tier-1-0: #00830b !important; // Verde scuro
$tier-1-5: #28c835 !important; // Verde
$tier-2-0: #cbb900 !important; // Giallo
$tier-2-5: #fb8500 !important; // Arancione
$tier-3-0: #d62828 !important; // Rosso
```

### Bootstrap Integration

- ✅ Bootstrap 5.3 importato correttamente
- ✅ SCSS con variabili custom
- ✅ Vite alias configurato (`@/` funzionante)
- ✅ Utility classes per tier (color + background)
- ✅ `!important` usato per override Bootstrap tables

---

## 📚 Risorse Utili

### Documentazione Ufficiale

- [Vue 3 Docs](https://vuejs.org/)
- [Laravel Docs](https://laravel.com/docs)
- [Vite](https://vitejs.dev/)
- [Docker Compose](https://docs.docker.com/compose/)
- [Axios](https://axios-http.com/)
- [Vue Router](https://router.vuejs.org/)
- [Football-Data.org API](https://www.football-data.org/documentation/quickstart)

---

_Questo documento è living documentation e viene aggiornato al completamento di ogni phase._
