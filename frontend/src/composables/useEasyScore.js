import { ref, computed } from 'vue';
import api from '@/services/api';

export function useEasyScore() {

  // STATE - Dati reattivi
  // Array di tutte le 380 partite della stagione
  const games = ref([]);
  
  // Array delle 20 squadre Serie A
  const teams = ref([]);
  
  // Numero della giornata corrente (es: 12)
  const currentGiornata = ref(1);
  
  // Giornata di inizio del range selezionato dall'utente (default: corrente)
  const fromGiornata = ref(1);
  
  // Giornata di fine del range selezionato dall'utente (default: corrente + 4)
  const toGiornata = ref(38);
  
  // Flag per mostrare/nascondere il loader durante le chiamate API
  const loading = ref(false);
  
  // Messaggio di errore (null se tutto ok)
  const error = ref(null);

  // Array di ID squadre selezionate
  const selectedTeams = ref([]);

  // METODO 1: Caricamento Dati Iniziale
  /**
   * Carica tutti i dati necessari all'avvio della pagina:
   * - Tutte le partite della stagione
   * - Tutte le squadre
   * - La giornata corrente
   */
  const fetchAllData = async () => {
    loading.value = true;  // Mostra loader
    error.value = null;    // Reset errori precedenti

    try {
      // Promise.all esegue le 3 chiamate API IN PARALLELO
      // Invece di aspettare una dopo l'altra (sequenziale)
      const [gamesRes, teamsRes, giornataRes] = await Promise.all([
        api.get('/games'),                                    // ~380 partite
        api.get('/teams'),                                    // ~20 squadre
        api.get('/current-giornata')                          // Giornata corrente
      ]);

      // Estrae i dati dalle risposte e li salva nello state reattivo
      games.value = gamesRes.data.data;               // Array partite
      teams.value = teamsRes.data.data;               // Array squadre
      currentGiornata.value = giornataRes.data.data.giornata;  // Numero (es: 12)
      
      // Imposta i select di default:
      // - Inizio = giornata corrente
      // - Fine = corrente + 4 (max 38)
      fromGiornata.value = currentGiornata.value;
      toGiornata.value = Math.min(currentGiornata.value + 4, 38);

      // Inizializzo tutti i team nell'array per il filtro
      selectedTeams.value = teams.value.map(t => t.id);

    } catch (err) {
      // Se qualcosa va storto, salva il messaggio di errore
      error.value = err.message || 'Errore nel caricamento dei dati';
      console.error('Errore fetchAllData:', err);
    } finally {
      // Sia in caso di successo che errore, nascondi il loader
      loading.value = false;
    }
  };

  // METODO 2: Filtra Partite di una Squadra
    /**
   * Trova tutte le partite di una squadra specifica
   * nel range di giornate selezionato (fromGiornata → toGiornata)
   * 
   * @param {number} teamId - ID della squadra (es: 1 = Juventus)
   * @returns {Array} - Array di partite filtrate
   */
  const getTeamMatches = (teamId) => {
    // filter() crea un nuovo array con solo gli elementi che soddisfano le condizioni
    return games.value.filter(game => {
      // Condizione 1: La partita è nel range di giornate selezionato?
      const isInRange = game.giornata >= fromGiornata.value && 
                        game.giornata <= toGiornata.value;
      
      // Condizione 2: La squadra gioca in questa partita (casa O trasferta)?
      const isTeamInvolved = game.home_team_id === teamId || 
                             game.away_team_id === teamId;
      
      // Ritorna true solo se ENTRAMBE le condizioni sono vere
      return isInRange && isTeamInvolved;
    });
  };

  // METODO 3: Calcola EasyScore Totale
  /**
   * Somma gli EasyScore di tutte le partite di una squadra
   * nel range selezionato
   * 
   * @param {number} teamId - ID della squadra
   * @returns {number} - Somma degli EasyScore (es: 12.5)
   */
  const calculateTeamEasyScore = (teamId) => {
    // Prende tutte le partite della squadra nel range
    const matches = getTeamMatches(teamId);

    // se non ci sono match, setta il valore a 0
    if (matches.length === 0) return 0;
    
    // reduce() somma tutti gli elementi di un array
    // Parte da 0 (valore iniziale) e per ogni partita aggiunge l'EasyScore
    const total = matches.reduce((sum, match) => {
      // La squadra gioca in casa in questa partita?
      const isHome = match.home_team_id === teamId;
      
      // Prende l'EasyScore corretto (casa o trasferta)
      // Se manca, usa 0 come fallback
      const easyScore = isHome ? 
        parseFloat(match.home_easy_score || 0) : 
        parseFloat(match.away_easy_score || 0);
      
      // Somma questo EasyScore al totale
      return sum + easyScore;
    }, 0);  // 0 = valore iniziale della somma

    // Fa la media
    const average = total / matches.length;

    // Restituisce il risultato
    return average.toFixed(1);
  };


  // METODO 4: Trova Avversario in una Giornata
  /**
   * Trova quale squadra affronta una squadra specifica
   * in una giornata specifica
   * 
   * @param {number} teamId - ID della squadra
   * @param {number} giornata - Numero giornata (es: 15)
   * @returns {Object|null} - Dati avversario o null se non gioca
   */
  const getOpponentForGiornata = (teamId, giornata) => {
    // find() trova il PRIMO elemento che soddisfa le condizioni
    // (ogni squadra gioca max 1 partita per giornata)
    const match = games.value.find(game => 
      game.giornata === giornata &&  // Giornata corretta?
      (game.home_team_id === teamId || game.away_team_id === teamId)  // Squadra coinvolta?
    );

    // Se non trova nessuna partita, ritorna null
    if (!match) return null;

    // La squadra gioca in casa?
    const isHome = match.home_team_id === teamId;
    
    // ID dell'avversario (se casa → prendi trasferta, se trasferta → prendi casa)
    const opponentId = isHome ? match.away_team_id : match.home_team_id;
    
    // Trova l'oggetto squadra completo dall'ID
    const opponent = teams.value.find(t => t.id === opponentId);
    
    // Ritorna oggetto con tutte le info necessarie per visualizzare
    return {
      name: opponent?.nome || 'N/A',  // Nome avversario (o N/A se non trovato)
      isHome: isHome,                  // true = casa, false = trasferta
      easyScore: isHome ? match.home_easy_score : match.away_easy_score  // EasyScore di questa partita
    };
  };

  // COMPUTED: Tabella Completa 
  /**
   * Proprietà computed che si ricalcola AUTOMATICAMENTE
   * ogni volta che cambiano: teams, games, fromGiornata, toGiornata
   * 
   * Genera la struttura dati completa per visualizzare la tabella
   */
  const easyScoreTable = computed(() => {
    // map() trasforma ogni squadra in un oggetto con tutti i dati necessari
    return teams.value.map(team => {
      // Calcola EasyScore totale per questa squadra
      const totalEasyScore = calculateTeamEasyScore(team.id);
      
      // Array vuoto che conterrà i dati di ogni giornata
      const giornateData = [];

      // Loop da giornata iniziale a giornata finale
      // Esempio: se fromGiornata=12 e toGiornata=16 → loop 5 volte
      for (let g = fromGiornata.value; g <= toGiornata.value; g++) {
        // Per ogni giornata, trova l'avversario
        giornateData.push({
          giornata: g,                            // Numero giornata
          opponent: getOpponentForGiornata(team.id, g)  // Dati avversario (o null)
        });
      }

      // Ritorna oggetto completo per questa riga della tabella
      return {
        team: team,                              // Oggetto squadra completo
        totalEasyScore: totalEasyScore,          // ES medio 
        giornate: giornateData                   // Array con dati di ogni giornata
      };
    });
  });

  // COMPUTED: Lista Giornate Disponibili
  /**
  * Ottiene la prima data di una giornata e la formatto in ITA
  */
  const getGiornataDate = (giornata) => {
      const match = games.value.find(g => g.giornata === giornata);
      if (!match?.data_partita) return null;
      
      const date = new Date(match.data_partita);
      return date.toLocaleDateString('it-IT', { 
          day: '2-digit', 
          month: '2-digit', 
          year: 'numeric' 
      });
  };

  /**
  * Lista delle giornate disponibili con date
  */
  const availableGiornate = computed(() => {
      const result = [];
      for (let i = 0; i < 38; i++) {
          const giornata = i + 1;
          result.push({
              giornata: giornata,
              data: getGiornataDate(giornata)
          });
      }
      return result;
  });

  // COMPUTED: tabella filtrata
  const filteredEasyScoreTable = computed(() => {
      return easyScoreTable.value.filter(row => 
          selectedTeams.value.includes(row.team.id)
      );
  });

  // seleziona tutto
  const selectAllTeams = () => {
      selectedTeams.value = teams.value.map(t => t.id);
  };

  // deseleziona tutto
  const deselectAllTeams = () => {
      selectedTeams.value = [];
  };

  // COMPUTED: calcola l'easy score giornata per giornata
  const easyScoreTotals = computed(() => {
      const totals = [];
      
      for (let g = fromGiornata.value; g <= toGiornata.value; g++) {
          let count = 0;
          
          // Per ogni squadra (FILTRATA!)
          filteredEasyScoreTable.value.forEach(row => {
              const giornataData = row.giornate.find(gd => gd.giornata === g);
              if (giornataData?.opponent?.easyScore >= 2.5) {
                  count++;
              }
          });
          
          totals.push({ giornata: g, count: count });
      }
      
      return totals;
  });

  // EXPORT: Cosa esponiamo al componente
  return {
    // State (refs modificabili)
    games,
    teams,
    currentGiornata,
    fromGiornata,      // Il componente può modificare questi
    toGiornata,        // per cambiare il range visualizzato
    loading,
    error,

    // Computed (readonly, si aggiornano automaticamente)
    // easyScoreTable,        // Tabella completa già calcolata
    availableGiornate,     // Array [1...38]

    // Computed per tabella con filtro
    selectedTeams,
    filteredEasyScoreTable,  // Invece di easyScoreTable
    selectAllTeams,
    deselectAllTeams,
    easyScoreTotals,

    // Methods (funzioni chiamabili)
    fetchAllData,          // Chiamato all'avvio
    getTeamMatches,        // Utility (opzionale esporre)
    calculateTeamEasyScore,  // Utility (opzionale esporre)
    getOpponentForGiornata   // Utility (opzionale esporre)
  };
}