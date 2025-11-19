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
    
    // reduce() somma tutti gli elementi di un array
    // Parte da 0 (valore iniziale) e per ogni partita aggiunge l'EasyScore
    return matches.reduce((total, match) => {
      // La squadra gioca in casa in questa partita?
      const isHome = match.home_team_id === teamId;
      
      // Prende l'EasyScore corretto (casa o trasferta)
      // Se manca, usa 0 come fallback
      const easyScore = isHome ? 
        parseFloat(match.home_easy_score || 0) : 
        parseFloat(match.away_easy_score || 0);
      
      // Somma questo EasyScore al totale
      return total + easyScore;
    }, 0);  // 0 = valore iniziale della somma
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
        totalEasyScore: totalEasyScore.toFixed(1),  // ES totale formattato (es: "12.5")
        giornate: giornateData                   // Array con dati di ogni giornata
      };
    });
  });


  // COMPUTED: Lista Giornate Disponibil
  /**
   * Genera array [1, 2, 3, ..., 38] per popolare i select
   */
  const availableGiornate = computed(() => {
    // Array.from crea un array di lunghezza 38
    // (_, i) => i + 1 trasforma indici 0-37 in valori 1-38
    return Array.from({ length: 38 }, (_, i) => i + 1);
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
    easyScoreTable,        // Tabella completa già calcolata
    availableGiornate,     // Array [1...38]

    // Methods (funzioni chiamabili)
    fetchAllData,          // Chiamato all'avvio
    getTeamMatches,        // Utility (opzionale esporre)
    calculateTeamEasyScore,  // Utility (opzionale esporre)
    getOpponentForGiornata   // Utility (opzionale esporre)
  };
}