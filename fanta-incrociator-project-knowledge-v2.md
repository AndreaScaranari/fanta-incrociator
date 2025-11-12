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
- **Styling**: Bootstrap 5.3+ + SCSS
- **HTTP Client**: Axios 1.8.2 (sicuro)
- **Router**: Vue Router 4.4.0
- **Note**: Non serve Nuxt perché non è necessaria la SEO

### Backend

- **Framework**: Laravel 12 (latest)
- **API**: RESTful architecture
- **Authentication**: Da definire (Laravel Sanctum consigliato per SPA)
- **PHP Version**: 8.2-FPM

### Database

- **DBMS**: MySQL 8.0
- **GUI Tool**: PHPMyAdmin (attualmente in uso)
- **Alternative**: HeidiSQL, MySQL Workbench, DBeaver

### DevOps

- **Containerizzazione**: Docker + Docker Compose
- **Ambiente**: Docker al posto di XAMPP
- **OS**: Windows
- **Terminal**: VSCode integrated terminal / PowerShell

### Hosting

- **Fase iniziale**: Locale (sviluppo)
- **Fase futura**: Hosting web con piccoli inserti pubblicitari

---

## 🎯 Funzionalità Core (MVP)

### 1. Ranking Dinamico delle Squadre ✅ Priority: HIGH - COMPLETATO

**Status**: ✅ COMPLETATO IN PHASE 2

**Descrizione**: Sistema di classificazione delle squadre Serie A in fasce (tier).

**Implementazione**:
- ✅ Migration `teams` creata e funzionante
- ✅ Seeder con 20 squadre Serie A
- ✅ Model `Team.php` con relationships
- ✅ API CRUD completa

**Tier List**:

```javascript
{
  1.0: ["Juventus", "Inter", "Napoli", "Milan", "Roma"],
  1.5: ["Atalanta", "Bologna"],
  2.0: ["Como", "Fiorentina", "Lazio"],
  2.5: ["Udinese", "Torino", "Cagliari", "Parma", "Cremonese", "Sassuolo"],
  3.0: ["Lecce", "Pisa", "Genoa", "Verona"]
}
```

**API Endpoints Implementate**:

- `GET /api/teams` - Elenco squadre con tier (ordinabili)
- `GET /api/teams/{id}` - Dettaglio singola squadra
- `PUT /api/teams/{id}/tier` - Modifica tier di una squadra
- `POST /api/teams/reorder` - Riordino multiplo squadre in una sola richiesta

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

### 2. Calcolo EasyScore ⏳ Priority: HIGH - IN PROGRESS

**Status**: ⏳ Prossima phase (Phase 3)

**Algoritmo da Implementare**:

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

**API Requirements** (da implementare):

- `GET /api/easy-score?team={team}&giornate={n}` - Calcola EasyScore per n giornate successive
- `GET /api/easy-score/all?giornate={n}` - EasyScore di tutte le squadre

---

### 3. Visualizzazione Grafica EasyScore ⏳ Priority: HIGH

**Status**: ⏳ Prossima phase (Phase 4)

**Obiettivo**: Tabella colorata per comparare facilmente le squadre e trovare complementarità.

---

### 4. Sezione Statistiche 📊 Priority: MEDIUM

**Status**: ⏳ Prossima phase (Phase 5)

---

## 🚀 Roadmap e Sviluppo Incrementale

### Phase 1: Setup & Infrastructure 🏗️ - ✅ COMPLETATO

**Goal**: Ambiente funzionante con Docker

**Deliverable**: Container funzionanti, Laravel che risponde a Vue

**Status**: ✅ COMPLETATO
- ✅ Docker Compose con 5 container (PHP, MySQL, Nginx, Node, PhpMyAdmin)
- ✅ Laravel 12 funzionante
- ✅ Vue 3 + Vite funzionante
- ✅ Database MySQL configurato

---

### Phase 2: CRUD Squadre & Ranking 📝 - ✅ COMPLETATO

**Goal**: Gestione dinamica del ranking

**Deliverable**: Utente può modificare tier squadre tramite UI

**Status**: ✅ COMPLETATO (18 Novembre 2025)

**Tasks Completate**:

- ✅ Migration tabella `teams`
- ✅ Seeder con squadre Serie A e tier iniziali
- ✅ Model `Team.php` con relationships (preparato per Phase 3)
- ✅ Controller `TeamController.php` con tutti gli endpoint CRUD
- ✅ Routes API registrate in `bootstrap/app.php`
- ✅ Composable `useTeams.js` per gestione stato e logica
- ✅ Service `api.js` per configurazione Axios
- ✅ Componenti Vue 3 Composition API:
  - ✅ `views/TeamList.vue` - Homepage con lista squadre raggruppate per tier
  - ✅ `views/TeamRankingEditor.vue` - Editor per modificare tier (batch update)
  - ✅ `components/Titolone.vue` - Header riutilizzabile
  - ✅ `components/Loader.vue` - Spinner di caricamento
  - ✅ `components/ErrorMsg.vue` - Visualizzazione errori
  - ✅ `components/WarningMsg.vue` - Messaggi warning
  - ✅ `components/TeamsRankingList.vue` - Visualizzazione squadre per tier
  - ✅ `components/UpdateButton.vue` - Pulsante aggiorna riutilizzabile
- ✅ Router Vue 3 con gestione dinamica dei title
- ✅ Bootstrap 5.3 + SCSS integrati correttamente
- ✅ Alias Vite (`@/`) configurati

**Problemi Risolti Durante Phase 2**:

- ❌ Laravel 12 non riconosceva `RouteServiceProvider` (soluzione: usare `bootstrap/app.php`)
- ❌ `.value` in Composition API confuso (spiegazione: `ref()` wrappa i valori)
- ❌ Alias `@/` non funzionava in SCSS scoped (soluzione: configurare in `vite.config.js`)
- ❌ Bootstrap import complesso (soluzione: importare il bundle compilato)

**Note Importanti**:

- Composition API usata per tutti i componenti
- Emit pattern implementato per comunicazione figlio-padre
- Short-circuit evaluation usato per validazioni
- Optional chaining (`?.`) per accesso sicuro alle proprietà

---

### Phase 3: Calendario & EasyScore 📅 - ⏳ PROSSIMA

**Goal**: Calcolo e visualizzazione EasyScore

**Tasks Pianificati**:

- [ ] Migration tabella `matches` (calendario)
- [ ] Integrazione API esterna per calendario Serie A
- [ ] Implementazione algoritmo EasyScore in Laravel
- [ ] API endpoint per EasyScore
- [ ] Composable `useEasyScore.js`
- [ ] Componente Vue per selezione giornate
- [ ] Lista squadre con EasyScore calcolato

**Deliverable**: Sistema calcola EasyScore dinamicamente

---

### Phase 4: Visualizzazione Avanzata 🎨 - ⏳ FUTURA

**Goal**: Heatmap e grafici

**Tasks Pianificati**:

- [ ] Componente Heatmap Vue
- [ ] Color coding (verde → rosso)
- [ ] Vista comparativa giornate
- [ ] Export/Screenshot tabella (opzionale)

---

### Phase 5: Statistiche & Integrazioni 📊 - ⏳ FUTURA

**Goal**: Dati real-time da API esterne

---

### Phase 6: Features Avanzate ⚡ - ⏳ FUTURA

---

## 🔌 API Esterne da Integrare (Phase 3+)

### 1. Calendario Serie A

**Opzioni**:

- **Football-Data.org**: Free tier generoso, Serie A inclusa ⭐ CONSIGLIATO
- **API-Football** (RapidAPI): Dati completi, free tier limitato
- **TheSportsDB**: Gratuita ma meno aggiornata
- **Scraping Lega Serie A**: Possibile ma manutenzione complessa

### 2. Statistiche Squadre

**Fonte**: Stessa API del calendario (Football-Data.org copre anche stats)

### 3. Dati Fantacalcio

**Opzioni**:

- **Fantacalcio.it**: API ufficiali da verificare
- **Fantagazzetta**: Disponibilità API da verificare
- **Scraping**: Ultima opzione

---

## 📐 Convenzioni e Best Practices

### Laravel Backend

**Structure**:

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── TeamController.php ✅
│   │   │   ├── MatchController.php (Phase 3)
│   │   │   ├── EasyScoreController.php (Phase 3)
│   │   │   └── StatsController.php (Phase 5)
│   └── Resources/
│       └── (API Resources per serializzazione)
├── Models/
│   ├── Team.php ✅
│   ├── Match.php (Phase 3)
│   ├── Player.php (Phase 5)
│   └── PlayerStat.php (Phase 5)
├── Services/
│   ├── EasyScoreService.php (Phase 3)
│   └── ExternalApiService.php (Phase 3)
└── Jobs/
    └── UpdateStatsJob.php (Phase 5 - cron)
```

**Naming Conventions**:

- Controllers: Singular + Controller (TeamController)
- Models: Singular, PascalCase (Team, Match)
- Routes API: Plural, kebab-case (`/api/teams`)
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
├── views/
│   ├── TeamList.vue ✅
│   ├── TeamRankingEditor.vue ✅
│   ├── EasyScore.vue (Phase 3)
│   └── Stats.vue (Phase 5)
├── components/
│   ├── Titolone.vue ✅
│   ├── Loader.vue ✅
│   ├── ErrorMsg.vue ✅
│   ├── WarningMsg.vue ✅
│   ├── UpdateButton.vue ✅
│   ├── TeamsRankingList.vue ✅
│   ├── teams/
│   │   └── (componenti team-specifici)
│   ├── easyscore/
│   │   ├── EasyScoreTable.vue (Phase 3)
│   │   └── EasyScoreHeatmap.vue (Phase 4)
│   └── stats/
│       └── StatsBoard.vue (Phase 5)
├── composables/
│   ├── useTeams.js ✅
│   ├── useEasyScore.js (Phase 3)
│   └── useApi.js
├── services/
│   └── api.js ✅
├── router/
│   └── index.js ✅
├── assets/
│   └── style/
│       ├── _colors.scss ✅
│       └── style.scss ✅
└── App.vue ✅
```

**Composition API Pattern** (Implementato):

```javascript
// composables/useTeams.js
import { ref, computed } from "vue";
import api from "@/services/api";

export function useTeams() {
  const teams = ref([]);
  const loading = ref(false);
  const error = ref(null);

  const fetchTeams = async () => {
    loading.value = true;
    error.value = null;
    try {
      const response = await api.get("/teams");
      teams.value = response.data.data;
    } catch (err) {
      error.value = err.message;
    } finally {
      loading.value = false;
    }
  };

  return { teams, loading, error, fetchTeams };
}
```

**Component Naming**: PascalCase (TeamList.vue, UpdateButton.vue)

### Database Conventions

**Tables**: plural, snake_case (teams, matches, player_stats)

**Columns**:

- Primary Key: `id` (auto-increment)
- Foreign Keys: `{table_singular}_id` (team_id, match_id)
- Timestamps: `created_at`, `updated_at` (Laravel convention)

**Indexes**: Su FK e colonne frequentemente filtrate

---

## 🐳 Docker Setup - COMPLETATO

### File docker-compose.yml (Working)

5 servizi attivi:

```yaml
services:
  app:           # PHP 8.2-FPM con Laravel
  nginx:         # Web server
  mysql:         # Database
  frontend:      # Node 20 Alpine con Vue 3 + Vite
  phpmyadmin:    # GUI Database
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

### Color Palette (_colors.scss)

```scss
// Palette Sito
$purple: #7B0EAE;
$black: #080708;
$white: #E6E8E6;
$blue: #7A89C2;
$green: #4f7146;

// Colori Tier
$tier-1: #00830b;     // Verde scuro
$tier-1-5: #28c835;   // Verde
$tier-2: #cbb900;     // Giallo
$tier-2-5: #fb8500;   // Arancione
$tier-3: #d62828;     // Rosso
```

### Bootstrap Integration

- ✅ Bootstrap 5.3 importato correttamente
- ✅ SCSS con variabili custom
- ✅ Vite alias configurato (`@/` funzionante)
- ✅ Composizione componenti atomici

---

## 💡 Composition API - Key Concepts

### `ref()` - Reattività

```javascript
const count = ref(0)
// Accesso: count.value
```

### `computed()` - Proprietà Calcolate

```javascript
const doubled = computed(() => count.value * 2)
// Si ricalcola solo quando le dipendenze cambiano
```

### `defineProps()` - Props

```javascript
defineProps({
  title: String,
  count: Number
})
```

### `defineEmits()` - Eventi

```javascript
const emit = defineEmits(['update'])
emit('update')  // Invia evento al genitore
```

### Short-Circuit Evaluation

```javascript
value && executeIfTrue()    // Esegue solo se value è truthy
value || fallback()         // Fallback se value è falsy
```

### Optional Chaining (`?.`)

```javascript
object?.property    // Sicuro - ritorna undefined se object è null
```

---

## 📝 Checklist per Ogni Nuova Feature

- [ ] Migration database creata e testata
- [ ] Model Laravel con relationships
- [ ] Controller con metodi necessari
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
- [Axios](https://axios-http.com/)
- [Vue Router](https://router.vuejs.org/)

### Problemi Comuni Risolti

1. **`.value` in Composition API**
   - Necessario perché `ref()` wrappa i valori
   - Nel template Vue accede automaticamente a `.value`

2. **Alias `@/` in SCSS scoped**
   - Configurare in `vite.config.js` con `resolve.alias`
   - Per SCSS scoped: usare import relativi come fallback

3. **Optional chaining `?.`**
   - Sicuro per accedere a proprietà potenzialmente undefined
   - Ritorna undefined senza errori se l'oggetto non esiste

4. **Short-circuit evaluation**
   - `&&` valuta solo il secondo operando se il primo è truthy
   - Utile per validazioni e controlli di sicurezza

---

## 🎯 Success Metrics

**MVP Completed When**:

- ✅ Utente può modificare ranking squadre
- ⏳ Sistema calcola EasyScore per N giornate (Phase 3)
- ⏳ Heatmap visualizza dati in modo intuitivo (Phase 4)
- ⏳ Calendario Serie A integrato (Phase 3)

**Phase 2 Success**:

- ✅ CRUD squadre funzionante
- ✅ UI stilizzata e responsive (da fare mobile)
- ✅ Frontend e Backend comunicano perfettamente
- ✅ Componenti riutilizzabili

**Production Ready** (Future):

- [ ] Hosting configurato
- [ ] Analytics integrato
- [ ] Ads system implementato
- [ ] Mobile-responsive completo
- [ ] 50+ utenti beta test positivi

---

## 🔄 Version History

- **v0.1 (Prototype - Settembre 2025)**: File HTML standalone con JS vanilla
- **v0.9 (Phase 1 - Novembre 2025)**: Setup Docker completo
- **v1.0 (Phase 2 - 18 Novembre 2025)**: CRUD squadre + UI completa ✅
- **v1.5 (Phase 3 - TBD)**: Calendario + EasyScore
- **v2.0 (Phase 4-5 - TBD)**: Heatmap + Statistiche
- **v3.0 (Future)**: Advanced stats, user accounts, predictions

---

## 📞 Support & Collaboration

**Developer**: Andrea Scaranari  
**Project Start**: Settembre 2025  
**Last Update**: 18 Novembre 2025  
**Status**: Phase 2 Completata ✅ | Phase 3 In Preparazione

---

_Questo documento è living documentation e viene aggiornato al completamento di ogni phase._
