-- ============================================
-- POPOLAMENTO DATABASE uniNotes
-- Database per piattaforma condivisione appunti
-- ============================================

USE uniNotes;

-- ============================================
-- INSERIMENTO UTENTI (25 utenti + 3 admin)
-- ============================================

INSERT INTO utenti (isAdmin, username, email, password) VALUES
-- Admin (password: admin123)
(TRUE, 'admin_marco', 'marco.admin@uninotes.it', '$2y$10$gYT.l6keERbhJzw/LOuVXOk4BdrLkL15fbFGMr8AiUpBIfD8lT7si'),
(TRUE, 'admin_laura', 'laura.admin@uninotes.it', '$2y$10$gYT.l6keERbhJzw/LOuVXOk4BdrLkL15fbFGMr8AiUpBIfD8lT7si'),
(TRUE, 'admin_giovanni', 'giovanni.admin@uninotes.it', '$2y$10$gYT.l6keERbhJzw/LOuVXOk4BdrLkL15fbFGMr8AiUpBIfD8lT7si'),

-- Utenti normali (password: password123)
(FALSE, 'ale_rossi', 'alessandro.rossi@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'chiara_bianchi', 'chiara.bianchi@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'luca_verdi', 'luca.verdi@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'sara_neri', 'sara.neri@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'marco_ferrari', 'marco.ferrari@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'giulia_romano', 'giulia.romano@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'andrea_colombo', 'andrea.colombo@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'francesca_ricci', 'francesca.ricci@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'matteo_marino', 'matteo.marino@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'elena_greco', 'elena.greco@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'davide_bruno', 'davide.bruno@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'valentina_gallo', 'valentina.gallo@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'simone_conti', 'simone.conti@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'martina_de_luca', 'martina.deluca@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'federico_costa', 'federico.costa@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'alice_fontana', 'alice.fontana@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'lorenzo_caruso', 'lorenzo.caruso@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'sofia_vitale', 'sofia.vitale@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'gabriele_moretti', 'gabriele.moretti@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'anna_lombardi', 'anna.lombardi@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'riccardo_barbieri', 'riccardo.barbieri@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'beatrice_ferrara', 'beatrice.ferrara@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'filippo_santoro', 'filippo.santoro@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'camilla_marini', 'camilla.marini@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'michele_russo', 'michele.russo@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'giorgia_villa', 'giorgia.villa@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(FALSE, 'pietro_serra', 'pietro.serra@studenti.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ============================================
-- INSERIMENTO SSD (8 settori)
-- ============================================

INSERT INTO ssd (nome, descrizione) VALUES
('ING-INF/05', 'Sistemi di Elaborazione delle Informazioni - Include architettura dei calcolatori, sistemi operativi e reti di calcolatori'),
('ING-INF/04', 'Automatica - Teoria dei sistemi, controlli automatici e robotica'),
('MAT/05', 'Analisi Matematica - Calcolo differenziale e integrale, serie e successioni'),
('MAT/03', 'Geometria - Algebra lineare, geometria analitica e spazi vettoriali'),
('ING-INF/03', 'Telecomunicazioni - Teoria dei segnali, comunicazioni digitali e reti'),
('INF/01', 'Informatica - Algoritmi, strutture dati, programmazione e basi di dati'),
('FIS/01', 'Fisica Sperimentale - Meccanica, termodinamica, elettromagnetismo e onde'),
('MAT/09', 'Ricerca Operativa - Ottimizzazione, teoria dei grafi e programmazione lineare');

-- ============================================
-- INSERIMENTO CORSI (15 corsi)
-- ============================================

INSERT INTO corsi (nome, descrizione, idssd) VALUES
('Analisi Matematica I', 'Corso fondamentale di analisi per ingegneria: limiti, derivate, integrali, studio di funzione. Comprende teoria e numerosi esercizi applicativi.', 3),
('Analisi Matematica II', 'Approfondimento dell analisi: funzioni a più variabili, integrali multipli, equazioni differenziali ordinarie e serie di Fourier.', 3),
('Algebra Lineare e Geometria', 'Spazi vettoriali, matrici, determinanti, sistemi lineari, autovalori e autovettori. Applicazioni alla geometria analitica nel piano e nello spazio.', 4),
('Fisica Generale I', 'Meccanica classica: cinematica e dinamica del punto materiale e dei sistemi, lavoro ed energia, quantità di moto, moti oscillatori.', 7),
('Fisica Generale II', 'Termodinamica, elettromagnetismo e ottica. Leggi di Maxwell, circuiti elettrici, campi magnetici e onde elettromagnetiche.', 7),
('Programmazione I', 'Introduzione alla programmazione con linguaggio C: variabili, strutture di controllo, funzioni, array, puntatori. Sviluppo di algoritmi di base e debugging.', 6),
('Algoritmi e Strutture Dati', 'Studio approfondito di algoritmi di ordinamento, ricerca, grafi e alberi. Complessità computazionale, analisi asintotica. Implementazione in C/C++.', 6),
('Basi di Dati', 'Progettazione di database relazionali: modello ER, normalizzazione, SQL avanzato. Transazioni, concorrenza, ottimizzazione query.', 6),
('Sistemi Operativi', 'Architettura e funzionamento dei sistemi operativi: gestione processi, memoria, file system. Sincronizzazione e deadlock.', 1),
('Reti di Calcolatori', 'Architettura delle reti: modello ISO/OSI e TCP/IP. Protocolli di routing, trasporto (TCP/UDP), applicazione (HTTP, DNS, FTP).', 1),
('Architettura degli Elaboratori', 'Organizzazione hardware del calcolatore: CPU, memoria, bus, I/O. Assembly MIPS/x86, pipeline, cache, memoria virtuale.', 1),
('Controlli Automatici', 'Teoria dei sistemi dinamici lineari: trasformata di Laplace, funzioni di trasferimento, stabilità. Progetto di controllori PID.', 2),
('Teoria dei Segnali', 'Analisi di segnali nel tempo e in frequenza: trasformata di Fourier, campionamento, filtraggio. Segnali aleatori e rumore.', 5),
('Ricerca Operativa', 'Problemi di ottimizzazione: programmazione lineare (metodo del simplesso), teoria dei grafi, problemi di flusso, cammini minimi.', 8),
('Ingegneria del Software', 'Metodologie di sviluppo software: ciclo di vita, UML, design patterns, testing. Gestione progetti con Git.', 6);

-- ============================================
-- INSERIMENTO APPUNTI (70 appunti totali)
-- Mix di articoli lunghi, medi e corti
-- ============================================

-- Analisi Matematica I (corso 1) - 5 appunti
INSERT INTO appunti (titolo, contenuto, data_pubblicazione, numero_visualizzazioni, stato, idutente, idcorso) VALUES
('Limiti e Continuità - Guida Completa', 
'# LIMITI E CONTINUITÀ

## Definizione di Limite
Il limite di f(x) per x che tende a x0 è L se i valori di f(x) si avvicinano arbitrariamente a L quando x si avvicina a x0.

### Definizione formale (ε-δ)
∀ε > 0, ∃δ > 0 : 0 < |x - x0| < δ ⟹ |f(x) - L| < ε

## Limiti notevoli
1. lim(x→0) sin(x)/x = 1
2. lim(x→0) (1-cos(x))/x² = 1/2
3. lim(x→0) (e^x - 1)/x = 1
4. lim(x→∞) (1 + 1/x)^x = e

## Continuità
Una funzione f è continua in x0 se:
1. Esiste f(x0)
2. Esiste lim(x→x0) f(x)
3. lim(x→x0) f(x) = f(x0)

### Tipi di discontinuità
- Prima specie (salto): limiti destro e sinistro esistono ma sono diversi
- Seconda specie: almeno un limite non esiste
- Terza specie (eliminabile): limite esiste ma diverso da f(x0)

## Teoremi fondamentali
- Teorema di Weierstrass: funzione continua su [a,b] ammette massimo e minimo
- Teorema dei valori intermedi: se f continua e f(a) < y < f(b), ∃c: f(c) = y
- Teorema degli zeri: se f continua e f(a)·f(b) < 0, ∃c: f(c) = 0

## Esercizi tipo
**Es 1**: lim(x→0) (sin(3x))/(5x) = 3/5
**Es 2**: lim(x→∞) (2x³-5x²+1)/(3x³+7x-2) = 2/3
**Es 3**: Studiare continuità di f(x) = {x² se x≤1, 2x se x>1}', 
'2024-09-15 10:30:00', 342, 'approvato', 4, 1),

('Derivate - Formulario Completo', 
'# DERIVATE

## Definizione
f\'(x0) = lim(h→0) [f(x0+h) - f(x0)]/h

Interpretazione: coefficiente angolare della tangente.

## Derivate fondamentali
- (x^n)\' = n·x^(n-1)
- (e^x)\' = e^x
- (a^x)\' = a^x · ln(a)
- (ln(x))\' = 1/x
- (sin(x))\' = cos(x)
- (cos(x))\' = -sin(x)
- (tan(x))\' = 1/cos²(x)
- (arctan(x))\' = 1/(1+x²)

## Regole di derivazione
1. Linearità: (af + bg)\' = af\' + bg\'
2. Prodotto: (f·g)\' = f\'·g + f·g\'
3. Quoziente: (f/g)\' = (f\'·g - f·g\')/g²
4. Composizione: (f∘g)\' = f\'(g(x))·g\'(x)

## Applicazioni
- f\' > 0 ⟹ f crescente
- f\' < 0 ⟹ f decrescente
- f\' = 0 ⟹ punto stazionario
- f\'\'(x0) > 0 ⟹ minimo locale
- f\'\'(x0) < 0 ⟹ massimo locale', 
'2024-09-20 14:15:00', 289, 'approvato', 6, 1),

('Integrali - Tecniche Base', 
'# INTEGRALI

## Integrale Indefinito
∫f(x)dx = F(x) + c dove F\'(x) = f(x)

### Integrali immediati
- ∫x^n dx = x^(n+1)/(n+1) + c
- ∫1/x dx = ln|x| + c
- ∫e^x dx = e^x + c
- ∫sin(x) dx = -cos(x) + c
- ∫cos(x) dx = sin(x) + c
- ∫1/(1+x²) dx = arctan(x) + c

## Integrale Definito
∫[a,b] f(x)dx = F(b) - F(a)

Rappresenta l\'area tra grafico e asse x.

## Metodi di integrazione

### 1. Sostituzione
Se ∫f(x)dx difficile, pongo y = g(x)
Esempio: ∫2x·e^(x²)dx, pongo y = x² → ∫e^y dy = e^(x²) + c

### 2. Per parti
∫f(x)g\'(x)dx = f(x)g(x) - ∫f\'(x)g(x)dx

Esempio: ∫x·e^x dx = x·e^x - e^x + c

### 3. Frazioni parziali
Per integrare P(x)/Q(x) con grado(P) < grado(Q)

## Proprietà integrale definito
- ∫[a,b] [f+g] = ∫[a,b] f + ∫[a,b] g
- ∫[a,b] k·f = k·∫[a,b] f
- ∫[a,c] f = ∫[a,b] f + ∫[b,c] f', 
'2024-09-28 16:45:00', 256, 'approvato', 11, 1),

('Serie Numeriche', 
'# SERIE NUMERICHE

Una serie è Σaₙ (somma infinita).

## Criteri di convergenza
- Confronto: se 0 ≤ aₙ ≤ bₙ e Σbₙ converge, allora Σaₙ converge
- Rapporto: lim|aₙ₊₁/aₙ| < 1 ⟹ converge
- Radice: lim ⁿ√|aₙ| < 1 ⟹ converge
- Leibniz (serie alternate): se aₙ monotona decrescente → 0, converge

## Serie notevoli
- Geometrica: Σq^n converge se |q|<1 (somma = 1/(1-q))
- Armonica: Σ1/n diverge
- Armonica generalizzata: Σ1/n^p converge se p>1', 
'2024-10-05 09:20:00', 178, 'approvato', 15, 1),

('Esercizi Risolti Analisi I', 
'# ESERCIZI SVOLTI

## 1. Studio di funzione
f(x) = (x²-1)/(x²-4)

**Dominio**: x ≠ ±2
**Simmetria**: pari
**Intersezioni**: x = ±1 (asse x), y = 1/4 (asse y)
**Asintoti**: y=1 (orizzontale), x=±2 (verticali)
**Derivata**: f\'(x) = -6x/(x²-4)²
**Crescenza**: x<0, Decrescenza: x>0
**Massimo**: (0, 1/4)

## 2. Integrale definito
∫[0,π/2] sin²(x)dx = π/4

## 3. Limite
lim(x→∞) (2x²+3x)/(5x²-1) = 2/5

## 4. Serie
Σ n/(n²+1) diverge (confronto con Σ1/n)', 
'2024-10-12 11:00:00', 412, 'approvato', 4, 1);

-- Algoritmi e Strutture Dati (corso 7) - 6 appunti
INSERT INTO appunti (titolo, contenuto, data_pubblicazione, numero_visualizzazioni, stato, idutente, idcorso) VALUES
('Complessità Computazionale - Notazione Big-O', 
'# COMPLESSITÀ

## Notazioni Asintotiche

### O-grande
f(n) = O(g(n)) se ∃c,n₀: ∀n>n₀, f(n) ≤ c·g(n)
Limite superiore asintotico.

### Ω-grande
f(n) = Ω(g(n)) se ∃c,n₀: ∀n>n₀, f(n) ≥ c·g(n)
Limite inferiore asintotico.

### Θ-grande
f(n) = Θ(g(n)) se f(n) = O(g(n)) E f(n) = Ω(g(n))
Limite stretto.

## Classi di Complessità
Dal più veloce al più lento:
1. O(1) - Costante
2. O(log n) - Logaritmica
3. O(n) - Lineare
4. O(n log n) - Linearitmica
5. O(n²) - Quadratica
6. O(2ⁿ) - Esponenziale
7. O(n!) - Fattoriale

## Regole calcolo
- Cicli sequenziali: O(n) + O(m) = O(n+m)
- Cicli annidati: O(n) · O(m) = O(n·m)
- Chiamate ricorsive: usare Teorema Master

## Teorema Master
Per T(n) = aT(n/b) + f(n):
- Caso 1: f(n) = O(n^(log_b(a)-ε)) ⟹ T(n) = Θ(n^(log_b(a)))
- Caso 2: f(n) = Θ(n^(log_b(a))) ⟹ T(n) = Θ(n^(log_b(a)) · log n)
- Caso 3: f(n) = Ω(n^(log_b(a)+ε)) ⟹ T(n) = Θ(f(n))

## Esempi
- Ricerca binaria: T(n) = T(n/2) + O(1) → O(log n)
- Merge sort: T(n) = 2T(n/2) + O(n) → O(n log n)', 
'2024-09-18 15:30:00', 445, 'approvato', 7, 7),

('Alberi Binari di Ricerca (BST)', 
'# ALBERI BINARI DI RICERCA

## Definizione
Albero binario dove per ogni nodo:
- Sottoalbero sinistro: tutti nodi < nodo corrente
- Sottoalbero destro: tutti nodi > nodo corrente

## Struttura in C
```c
typedef struct Node {
    int key;
    struct Node *left, *right;
} Node;
```

## Operazioni

### Ricerca - O(h)
```c
Node* search(Node* root, int key) {
    if(root == NULL || root->key == key)
        return root;
    if(key < root->key)
        return search(root->left, key);
    return search(root->right, key);
}
```

### Inserimento - O(h)
```c
Node* insert(Node* root, int key) {
    if(root == NULL) {
        Node* n = malloc(sizeof(Node));
        n->key = key;
        n->left = n->right = NULL;
        return n;
    }
    if(key < root->key)
        root->left = insert(root->left, key);
    else
        root->right = insert(root->right, key);
    return root;
}
```

### Visita In-Order (ordine crescente)
```c
void inorder(Node* root) {
    if(root) {
        inorder(root->left);
        printf("%d ", root->key);
        inorder(root->right);
    }
}
```

## Complessità
- h = altezza albero
- Caso migliore (bilanciato): h = log n → O(log n)
- Caso peggiore (degenerato): h = n → O(n)

## Bilanciamento
Per h = O(log n):
- AVL Trees: differenza altezze ≤ 1
- Red-Black Trees: proprietà colore nodi', 
'2024-09-25 10:00:00', 398, 'approvato', 12, 7),

('Algoritmi di Ordinamento', 
'# ALGORITMI DI ORDINAMENTO

## 1. Bubble Sort - O(n²)
Confronta coppie adiacenti e scambia.
```c
void bubble(int arr[], int n) {
    for(int i=0; i<n-1; i++)
        for(int j=0; j<n-i-1; j++)
            if(arr[j] > arr[j+1])
                swap(&arr[j], &arr[j+1]);
}
```

## 2. Selection Sort - O(n²)
Seleziona minimo e posiziona all\'inizio.

## 3. Insertion Sort - O(n²)
Costruisce array ordinato un elemento alla volta.
Efficiente per array piccoli o quasi ordinati.

## 4. Merge Sort - O(n log n)
Divide, ordina ricorsivamente, fonde.
```c
void merge(int arr[], int l, int m, int r) {
    // Crea array temp e fondi
}
void mergeSort(int arr[], int l, int r) {
    if(l < r) {
        int m = l + (r-l)/2;
        mergeSort(arr, l, m);
        mergeSort(arr, m+1, r);
        merge(arr, l, m, r);
    }
}
```

## 5. Quick Sort - O(n log n) medio
Scegli pivot, partiziona, ordina ricorsivamente.
Caso peggiore: O(n²)

## 6. Heap Sort - O(n log n)
Costruisci heap, estrai massimo ripetutamente.

## Confronto
| Algoritmo | Tempo medio | Spazio | Stabile |
|-----------|-------------|--------|---------|
| Bubble    | O(n²)       | O(1)   | Sì      |
| Selection | O(n²)       | O(1)   | No      |
| Insertion | O(n²)       | O(1)   | Sì      |
| Merge     | O(n log n)  | O(n)   | Sì      |
| Quick     | O(n log n)  | O(log n)| No     |
| Heap      | O(n log n)  | O(1)   | No      |', 
'2024-10-01 14:20:00', 521, 'approvato', 7, 7),

('Liste Concatenate', 
'# LISTE

## Lista Semplice
```c
typedef struct Node {
    int data;
    struct Node* next;
} Node;
```

## Operazioni

### Inserimento in testa - O(1)
```c
void insertHead(Node** head, int data) {
    Node* n = malloc(sizeof(Node));
    n->data = data;
    n->next = *head;
    *head = n;
}
```

### Inserimento in coda - O(n)
```c
void insertTail(Node** head, int data) {
    Node* n = malloc(sizeof(Node));
    n->data = data;
    n->next = NULL;
    
    if(*head == NULL) {
        *head = n;
        return;
    }
    
    Node* temp = *head;
    while(temp->next)
        temp = temp->next;
    temp->next = n;
}
```

### Ricerca - O(n)
### Cancellazione - O(n)

## Lista Doppia
Ogni nodo ha anche `prev` pointer.
Permette scorrimento bidirezionale.', 
'2024-10-08 16:30:00', 267, 'approvato', 17, 7),

('Grafi - Rappresentazione e Visite', 
'# GRAFI

## Definizione
G = (V, E) dove V = vertici, E = archi

**Orientato**: archi hanno direzione
**Non orientato**: archi bidirezionali

## Rappresentazioni

### Matrice di Adiacenza
M[i][j] = 1 se esiste arco tra i e j.
- Spazio: O(V²)
- Verifica arco: O(1)
- Ideale per grafi densi

### Liste di Adiacenza
Array di liste. Ogni vertice ha lista vicini.
- Spazio: O(V + E)
- Ideale per grafi sparsi

```c
typedef struct Node {
    int vertex;
    struct Node* next;
} Node;

typedef struct Graph {
    int numVertices;
    Node** adjLists;
} Graph;
```

## Visite

### DFS (Depth-First Search)
Esplora in profondità. Usa stack (ricorsione).
```c
void DFS(Graph* g, int v, bool visited[]) {
    visited[v] = true;
    printf("%d ", v);
    
    Node* temp = g->adjLists[v];
    while(temp) {
        if(!visited[temp->vertex])
            DFS(g, temp->vertex, visited);
        temp = temp->next;
    }
}
```
Complessità: O(V + E)

### BFS (Breadth-First Search)
Esplora per livelli. Usa coda.
Complessità: O(V + E)

## Applicazioni
- DFS: cicli, componenti connesse, topological sort
- BFS: cammino minimo (non pesato)', 
'2024-10-15 11:45:00', 389, 'approvato', 22, 7),

('Stack e Code', 
'# STACK E CODE

## Stack (LIFO)
```c
#define MAX 100
typedef struct {
    int items[MAX];
    int top;
} Stack;

void push(Stack* s, int x) {
    if(s->top == MAX-1) return;
    s->items[++s->top] = x;
}

int pop(Stack* s) {
    if(s->top == -1) return -1;
    return s->items[s->top--];
}
```

## Coda (FIFO)
```c
typedef struct {
    int items[MAX];
    int front, rear;
} Queue;

void enqueue(Queue* q, int x) {
    if(q->rear == MAX-1) return;
    q->items[++q->rear] = x;
}

int dequeue(Queue* q) {
    if(q->front > q->rear) return -1;
    return q->items[q->front++];
}
```

## Applicazioni
- Stack: valutazione espressioni, backtracking
- Coda: BFS, scheduling processi', 
'2024-10-20 09:30:00', 456, 'approvato', 12, 7);

-- Basi di Dati (corso 8) - 5 appunti
INSERT INTO appunti (titolo, contenuto, data_pubblicazione, numero_visualizzazioni, stato, idutente, idcorso) VALUES
('Modello ER', 
'# MODELLO ENTITÀ-RELAZIONE

## Componenti

### Entità
Oggetto del mondo reale (es. Studente, Corso).
Si rappresenta con rettangolo.

### Attributi
Proprietà di un\'entità (ovale).
- Semplici vs Composti (indirizzo → via, città, CAP)
- Mono-valore vs Multi-valore
- Derivati (età da data_nascita)
- **Chiave**: identifica univocamente (sottolineato)

### Relazioni
Associazione tra entità (rombo).
Es. Studente ISCRITTO_A Corso

### Cardinalità
- **1:1**: una istanza di A con una di B
- **1:N**: una istanza di A con molte di B
- **N:M**: molte A con molte B

### Partecipazione
- **Totale**: ogni entità deve partecipare (doppia linea)
- **Parziale**: può non partecipare (linea singola)

## Entità Deboli
Non ha chiave propria, dipende da entità forte.
Rettangolo doppio bordo.

## Specializzazione/Generalizzazione
Gerarchia IS-A.
```
    Veicolo
    /     \\
  Auto   Moto
```

Vincoli:
- Totale/Parziale
- Esclusiva/Overlapping', 
'2024-09-22 10:15:00', 378, 'approvato', 8, 8),

('SQL - DDL e DML', 
'# SQL

## DDL (Data Definition Language)

### CREATE TABLE
```sql
CREATE TABLE studenti (
    matricola INT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE,
    CHECK (media >= 0 AND media <= 30)
);
```

### FOREIGN KEY
```sql
CREATE TABLE esami (
    id INT PRIMARY KEY,
    matricola INT,
    voto INT CHECK (voto >= 18 AND voto <= 30),
    FOREIGN KEY (matricola) REFERENCES studenti(matricola)
        ON DELETE CASCADE
);
```

### ALTER TABLE
```sql
ALTER TABLE studenti ADD COLUMN telefono VARCHAR(15);
ALTER TABLE studenti MODIFY email VARCHAR(150);
ALTER TABLE studenti DROP COLUMN telefono;
```

## DML (Data Manipulation Language)

### INSERT
```sql
INSERT INTO studenti VALUES (12345, \'Mario\', \'mario@uni.it\');
INSERT INTO studenti (matricola, nome) VALUES (12346, \'Luca\');
```

### SELECT
```sql
SELECT * FROM studenti;
SELECT nome, email FROM studenti WHERE media > 25;
SELECT * FROM studenti ORDER BY media DESC LIMIT 10;

-- JOIN
SELECT s.nome, e.voto
FROM studenti s
JOIN esami e ON s.matricola = e.matricola;
```

### UPDATE
```sql
UPDATE studenti SET email = \'nuovo@uni.it\' WHERE matricola = 12345;
```

### DELETE
```sql
DELETE FROM studenti WHERE matricola = 12345;
```

## Funzioni Aggregate
```sql
SELECT COUNT(*) FROM studenti;
SELECT AVG(media) FROM studenti;
SELECT cognome, COUNT(*) FROM studenti GROUP BY cognome;
SELECT corso, AVG(voto) FROM esami GROUP BY corso HAVING AVG(voto) >= 25;
```', 
'2024-09-29 14:00:00', 502, 'approvato', 14, 8),

('Normalizzazione', 
'# NORMALIZZAZIONE

Eliminare ridondanze e anomalie.

## Forme Normali

### 1NF (Prima Forma Normale)
Valori atomici, no gruppi ripetuti.

❌ Studente(id, nome, {telefoni})
✅ Studente(id, nome), Telefono(id, numero)

### 2NF (Seconda Forma Normale)
1NF + nessuna dipendenza parziale dalla chiave.

❌ Esame(matricola, corso, nome_studente, nome_corso, voto)
   chiave: (matricola, corso)
   nome_studente dipende solo da matricola
   nome_corso dipende solo da corso

✅ Studente(matricola, nome)
   Corso(codice, nome)
   Esame(matricola, corso, voto)

### 3NF (Terza Forma Normale)
2NF + nessuna dipendenza transitiva.

❌ Studente(id, nome, CAP, città)
   id → CAP, CAP → città ⟹ id → città (transitiva)

✅ Studente(id, nome, CAP)
   CAP(CAP, città)

### BCNF (Boyce-Codd)
Versione più forte di 3NF.
Per ogni X → Y, X deve essere superchiave.

## Quando normalizzare?
- OLTP (transazioni): 3NF/BCNF
- OLAP (analisi): denormalizzare per performance', 
'2024-10-06 16:20:00', 334, 'approvato', 19, 8),

('Transazioni e ACID', 
'# TRANSAZIONI

## Proprietà ACID

### Atomicity (Atomicità)
Tutto o niente.
```sql
START TRANSACTION;
UPDATE conti SET saldo = saldo - 100 WHERE id = 1;
UPDATE conti SET saldo = saldo + 100 WHERE id = 2;
COMMIT;  -- o ROLLBACK
```

### Consistency (Consistenza)
Da stato valido a stato valido. Vincoli sempre rispettati.

### Isolation (Isolamento)
Transazioni concorrenti non si influenzano.

### Durability (Durabilità)
Transazione committata è permanente.

## Livelli di Isolamento
1. READ UNCOMMITTED (dirty read possibile)
2. READ COMMITTED
3. REPEATABLE READ (MySQL default)
4. SERIALIZABLE (massimo isolamento)

```sql
SET TRANSACTION ISOLATION LEVEL READ COMMITTED;
```

## Problemi Concorrenza
- **Dirty Read**: leggi dati non committati
- **Non-Repeatable Read**: rileggendo trovi valori diversi
- **Phantom Read**: rileggendo trovi nuove righe

## Lock
- **Shared Lock (S)**: per lettura
- **Exclusive Lock (X)**: per scrittura

```sql
SELECT * FROM tab WHERE id = 1 FOR UPDATE;  -- X-lock
```

## Deadlock
T1 blocca A, aspetta B
T2 blocca B, aspetta A
→ DBMS rileva e fa rollback di una transazione', 
'2024-10-13 10:30:00', 289, 'approvato', 24, 8),

('Indici e Ottimizzazione Query', 
'# OTTIMIZZAZIONE

## Indici
Strutture per velocizzare ricerche.

```sql
CREATE INDEX idx_cognome ON studenti(cognome);
CREATE UNIQUE INDEX idx_email ON studenti(email);
DROP INDEX idx_cognome ON studenti;
```

### Quando usare indici
- Colonne in WHERE, JOIN, ORDER BY
- Chiavi esterne
- Colonne con alta selettività

### Quando evitare
- Tabelle piccole
- Colonne con pochi valori distinti
- Tabelle con molti INSERT/UPDATE (overhead)

## EXPLAIN
Analizza piano di esecuzione query.

```sql
EXPLAIN SELECT * FROM studenti WHERE cognome = \'Rossi\';
```

Cerca:
- type: ALL (table scan, lento) vs index/ref (veloce)
- key: quale indice usa
- rows: righe esaminate

## Best Practices
1. SELECT solo colonne necessarie (no SELECT *)
2. Usa LIMIT per limitare risultati
3. Indicizza colonne in WHERE/JOIN
4. Evita OR (usa UNION se necessario)
5. Usa INNER JOIN invece di subquery quando possibile
6. Analizza query lente con EXPLAIN

## Query Avanzate
```sql
-- CTE (Common Table Expression)
WITH top_students AS (
    SELECT * FROM studenti WHERE media > 27
)
SELECT * FROM top_students JOIN esami;

-- Window Functions
SELECT nome, voto, 
       RANK() OVER (ORDER BY voto DESC) AS ranking
FROM esami;
```', 
'2024-10-19 15:45:00', 267, 'approvato', 8, 8);

-- Continua con gli altri corsi...
-- Per brevità, inserisco appunti più compatti per i restanti corsi

-- Sistemi Operativi (corso 9) - 4 appunti
INSERT INTO appunti (titolo, contenuto, data_pubblicazione, numero_visualizzazioni, stato, idutente, idcorso) VALUES
('Processi e Thread', 
'# PROCESSI E THREAD

## Processo
Programma in esecuzione. Include: codice, dati, stack, heap, PC, registri.

### Stati
1. New (creato)
2. Ready (pronto)
3. Running (in esecuzione)
4. Waiting (in attesa I/O)
5. Terminated (terminato)

### Context Switch
Salva stato processo corrente, carica nuovo processo. Overhead!

### fork() in Unix
```c
pid_t pid = fork();
if(pid == 0) {
    // Figlio
    execlp("/bin/ls", "ls", NULL);
} else {
    // Padre
    wait(NULL);
}
```

## Thread
Unità di esecuzione dentro processo. Condividono memoria.

### Vantaggi
- Responsiveness
- Condivisione risorse
- Context switch leggero
- Scalabilità multi-core

### pthread
```c
pthread_t thread;
pthread_create(&thread, NULL, func, arg);
pthread_join(thread, NULL);
```

## Race Condition
Accesso concorrente a dati condivisi.
**Soluzione**: mutex, semafori', 
'2024-09-24 11:00:00', 421, 'approvato', 10, 9),

('Scheduling CPU', 
'# SCHEDULING

## Criteri
- CPU Utilization (max)
- Throughput (max)
- Turnaround Time (min)
- Waiting Time (min)
- Response Time (min)

## Algoritmi

### FCFS (First-Come, First-Served)
Non preemptive. Semplice ma convoy effect.

### SJF (Shortest-Job-First)
Esegue job con burst più corto. Ottimale per minimizzare waiting time.
Problema: impossibile conoscere burst futuro.

### SRTF (Shortest Remaining Time First)
Preemptive SJF.

### Priority Scheduling
Priorità ai processi. Problema: starvation.
Soluzione: aging (aumentare priorità col tempo).

### Round Robin (RR)
Ogni processo riceve time quantum. Preemptive.
Buono per time-sharing.

Quantum troppo piccolo → troppi context switch
Quantum troppo grande → si comporta come FCFS

### Multilevel Feedback Queue
Più code con priorità diverse. Processi si spostano tra code.

## Linux CFS (Completely Fair Scheduler)
Ogni processo accumula vruntime. Esegue processo con vruntime minimo.
Implementato con red-black tree.', 
'2024-10-02 14:45:00', 367, 'approvato', 16, 9),

('Gestione Memoria', 
'# GESTIONE MEMORIA

## Paging
Memoria divisa in **frame** (fisici) e **pagine** (logiche) di dimensione fissa (es. 4KB).

**Page Table**: mappa pagine → frame

Indirizzo logico = [numero pagina | offset]
Indirizzo fisico = [numero frame | offset]

**TLB** (Translation Lookaside Buffer): cache per page table.

### Demand Paging
Pagine caricate su richiesta. **Page Fault** se pagina non in memoria.

### Algoritmi Sostituzione Pagine
- **FIFO**: sostituisci pagina più vecchia
- **Optimal**: sostituisci pagina usata più lontano nel futuro (teorico)
- **LRU** (Least Recently Used): sostituisci meno recentemente usata
- **Clock**: approssimazione efficiente di LRU con reference bit

### Thrashing
Sistema passa più tempo a gestire page fault che a eseguire.
**Causa**: troppi processi, working set > RAM

## Segmentation
Memoria divisa in segmenti di dimensione variabile (codice, stack, heap).

## Gerarchia Memoria
Registri → Cache L1/L2/L3 → RAM → SSD → HDD

**Principio località**:
- Temporale: dato usato sarà riusato presto
- Spaziale: dati vicini saranno usati insieme', 
'2024-10-09 09:15:00', 398, 'approvato', 21, 9),

('Sincronizzazione e Deadlock', 
'# SINCRONIZZAZIONE

## Sezione Critica
Codice che accede a risorse condivise.

## Mutex
```c
pthread_mutex_t lock;
pthread_mutex_lock(&lock);
// sezione critica
pthread_mutex_unlock(&lock);
```

## Semafori
Variabile intera per sincronizzazione.
```c
sem_t sem;
sem_init(&sem, 0, 1);
sem_wait(&sem);  // P, decrementa
// sezione critica
sem_post(&sem);  // V, incrementa
```

## Produttore-Consumatore
```c
sem_t empty, full, mutex;
// empty = N slot vuoti
// full = 0 slot pieni
// mutex per accesso buffer
```

## Deadlock
Condizioni necessarie:
1. Mutua esclusione
2. Hold and wait
3. No preemption
4. Attesa circolare

**Prevenzione**: negare almeno una condizione
**Evitare**: algoritmo del banchiere
**Rilevare**: grafo allocazione risorse', 
'2024-10-16 13:30:00', 312, 'approvato', 10, 9);

-- Reti di Calcolatori (corso 10) - 5 appunti
INSERT INTO appunti (titolo, contenuto, data_pubblicazione, numero_visualizzazioni, stato, idutente, idcorso) VALUES
('Modello ISO/OSI e TCP/IP', 
'# MODELLI DI RETE

## ISO/OSI (7 livelli)
7. Applicazione (HTTP, DNS)
6. Presentazione (crittografia, compressione)
5. Sessione
4. Trasporto (TCP, UDP)
3. Rete (IP)
2. Collegamento (Ethernet, Wi-Fi)
1. Fisico

## TCP/IP (4 livelli)
4. Applicazione (7+6+5 OSI)
3. Trasporto
2. Internet (rete OSI)
1. Accesso Rete (2+1 OSI)

## Incapsulamento
Applicazione: [Dati]
Trasporto: [TCP Header | Dati] = Segmento
Rete: [IP Header | Segmento] = Pacchetto
Collegamento: [Frame Header | Pacchetto | Trailer] = Frame

Ogni livello aggiunge header.', 
'2024-09-26 10:45:00', 389, 'approvato', 9, 10),

('IP e Indirizzamento', 
'# PROTOCOLLO IP

## IPv4
32 bit: A.B.C.D (0-255 per ottetto)
Es. 192.168.1.1

### Classi (obsolete)
A: 0.0.0.0 - 127.255.255.255
B: 128.0.0.0 - 191.255.255.255
C: 192.0.0.0 - 223.255.255.255

### Indirizzi Speciali
- 127.0.0.1: localhost
- 10.0.0.0/8, 172.16.0.0/12, 192.168.0.0/16: privati
- 255.255.255.255: broadcast

### CIDR
Notazione: IP/prefisso
192.168.1.0/24 = 24 bit rete, 8 bit host
Subnet mask: /24 → 255.255.255.0

### Subnetting
Dividere rete in sotto-reti.
192.168.1.0/24 → 4 subnet /26:
- 192.168.1.0/26 (0-63)
- 192.168.1.64/26 (64-127)
- 192.168.1.128/26 (128-191)
- 192.168.1.192/26 (192-255)

## IPv6
128 bit in esadecimale.
Es. 2001:0db8:85a3::8a2e:0370:7334

Motivazioni: esaurimento IPv4, semplificazione header.

## ARP
Risolve IP → MAC address.
1. Host A: "Chi ha IP B?" (broadcast)
2. Host B: "Sono io, MAC = XYZ"
3. A memorizza in ARP table

## ICMP
Messaggi di controllo per IP.
- Echo (ping)
- Destination Unreachable
- Time Exceeded (traceroute)', 
'2024-10-03 15:20:00', 445, 'approvato', 18, 10),

('TCP e UDP', 
'# PROTOCOLLI TRASPORTO

## TCP (Transmission Control Protocol)

### Caratteristiche
- Orientato connessione
- Affidabile (garanzia consegna)
- Controllo flusso e congestione
- Full-duplex

### Three-Way Handshake
1. Client → Server: SYN
2. Server → Client: SYN-ACK
3. Client → Server: ACK

### Header
- Sequence Number: numero primo byte
- Acknowledgment Number: prossimo byte atteso
- Flags: SYN, ACK, FIN, RST
- Window Size: controllo flusso

### Controllo Congestione
- Slow Start: crescita esponenziale
- Congestion Avoidance: crescita lineare
- Fast Retransmit/Recovery

### Porte Well-Known
21 (FTP), 22 (SSH), 23 (Telnet), 25 (SMTP), 53 (DNS), 
80 (HTTP), 110 (POP3), 143 (IMAP), 443 (HTTPS), 3306 (MySQL)

## UDP (User Datagram Protocol)

### Caratteristiche
- Connectionless
- Non affidabile
- Nessun controllo flusso/congestione
- Veloce e leggero
- Supporta multicast/broadcast

### Header (solo 8 byte!)
- Source Port
- Destination Port
- Length
- Checksum

### Quando usare UDP
- DNS (query veloci)
- Streaming video/audio (perdita tollerabile)
- Gaming (latenza critica)
- VoIP

## Confronto TCP vs UDP
| TCP | UDP |
|-----|-----|
| Affidabile | Non affidabile |
| Lento | Veloce |
| Overhead alto | Overhead basso |
| Streaming file | Streaming video |', 
'2024-10-10 11:30:00', 412, 'approvato', 23, 10),

('HTTP e DNS', 
'# HTTP

## Caratteristiche
- Protocollo applicativo
- Stateless
- Client-Server
- Usa TCP porta 80 (HTTP), 443 (HTTPS)

## Metodi HTTP
- **GET**: richiedi risorsa
- **POST**: invia dati
- **PUT**: sostituisci risorsa
- **DELETE**: elimina risorsa
- **HEAD**: come GET senza body

## Status Codes
- 2xx: Success (200 OK, 201 Created)
- 3xx: Redirection (301 Moved, 304 Not Modified)
- 4xx: Client Error (400 Bad Request, 404 Not Found)
- 5xx: Server Error (500 Internal Error, 503 Unavailable)

## HTTP/1.1 vs HTTP/2
- HTTP/2: binario, multiplexing, server push, compressione header
- HTTP/3: usa QUIC (UDP) invece di TCP

## HTTPS
HTTP su TLS/SSL. Crittografia end-to-end.
- Confidenzialità
- Integrità
- Autenticazione (certificati)

## Cookies
Mantengono stato.
```
Set-Cookie: session_id=abc123; HttpOnly; Secure
```

# DNS

## Funzione
Traduce nomi dominio → IP
www.google.com → 142.250.185.46

## Gerarchia
```
. (root)
├── com, org, it (TLD)
│   ├── google, wikipedia
│   │   └── www, mail
```

## Record DNS
- **A**: nome → IPv4
- **AAAA**: nome → IPv6
- **CNAME**: alias
- **MX**: mail server
- **NS**: name server
- **TXT**: testo generico

## Query DNS
1. Client → Resolver: "IP di www.example.com?"
2. Resolver → Root: chiedi TLD .com
3. Resolver → TLD .com: chiedi example.com
4. Resolver → example.com NS: IP di www?
5. Resolver → Client: 93.184.216.34

## Caching
Browser, OS, resolver memorizzano per TTL.

## Tool
```bash
nslookup www.google.com
dig www.google.com
host www.google.com
```', 
'2024-10-17 14:15:00', 467, 'approvato', 25, 10),

('Sicurezza di Rete', 
'# SICUREZZA

## Minacce
- Eavesdropping (intercettazione)
- Spoofing (falsificazione identità)
- DoS/DDoS (denial of service)
- MITM (man-in-the-middle)

## Firewall

### Tipi
1. **Packet Filtering**: filtra su IP/porta
2. **Stateful Inspection**: traccia connessioni
3. **Application Gateway**: filtra livello applicativo
4. **Next-Gen (NGFW)**: combina tutti

### iptables (Linux)
```bash
# Blocca tutto per default
iptables -P INPUT DROP
iptables -P OUTPUT ACCEPT

# Permetti SSH
iptables -A INPUT -p tcp --dport 22 -j ACCEPT

# Permetti HTTP/HTTPS
iptables -A INPUT -p tcp --dport 80 -j ACCEPT
iptables -A INPUT -p tcp --dport 443 -j ACCEPT
```

## VPN (Virtual Private Network)

### Funzione
Tunnel crittografato su Internet.

### Vantaggi
- Confidenzialità
- Integrità
- Autenticazione
- Accesso remoto sicuro

### Protocolli
- OpenVPN (open source, popolare)
- WireGuard (moderno, veloce)
- L2TP/IPsec
- IKEv2/IPsec

### IPsec
Protocollo per VPN a livello IP.
- Transport Mode: cifra payload
- Tunnel Mode: cifra tutto pacchetto IP

## IDS/IPS
- **IDS** (Intrusion Detection): rileva attacchi, genera alert
- **IPS** (Intrusion Prevention): previene bloccando traffico

Metodi:
- Signature-based: database attacchi noti
- Anomaly-based: rileva deviazioni

## Best Practices
- Defense in Depth (più livelli sicurezza)
- Least Privilege (minimo accesso necessario)
- Patch regolari
- Logging e monitoring
- Backup', 
'2024-10-24 16:00:00', 356, 'approvato', 9, 10);

-- Altri corsi con meno appunti
-- Programmazione I (corso 6) - 4 appunti
INSERT INTO appunti (titolo, contenuto, data_pubblicazione, numero_visualizzazioni, stato, idutente, idcorso) VALUES
('Puntatori in C', 
'# PUNTATORI

## Definizione
Puntatore = variabile che contiene indirizzo di memoria.

```c
int x = 10;
int *p = &x;  // p punta a x
printf("%d", *p);  // Dereferenziazione: stampa 10
*p = 20;  // Modifica x a 20
```

## Operatori
- **&**: indirizzo di
- **\\***: dereferenziazione

## Puntatori e Array
```c
int arr[5] = {1, 2, 3, 4, 5};
int *p = arr;  // p punta a arr[0]
printf("%d", *(p+1));  // 2
printf("%d", p[2]);    // 3
```

## Allocazione Dinamica
```c
int *p = (int*)malloc(10 * sizeof(int));
if(p == NULL) {
    // Gestione errore
}

for(int i=0; i<10; i++)
    p[i] = i;

free(p);  // SEMPRE liberare!
```

## Errori Comuni
- Puntatore non inizializzato
- Dangling pointer (usare dopo free)
- Memory leak (non liberare memoria)', 
'2024-09-19 09:00:00', 334, 'approvato', 13, 6),

('Strutture in C', 
'# STRUCT

## Definizione
```c
struct Studente {
    char nome[50];
    int matricola;
    float media;
};
```

## Utilizzo
```c
struct Studente s1;
strcpy(s1.nome, "Mario");
s1.matricola = 12345;
s1.media = 27.5;

struct Studente s2 = {"Luca", 12346, 28.0};
```

## Puntatori a struct
```c
struct Studente *p = &s1;
printf("%s", p->nome);  // Equivalente a (*p).nome
p->media = 28.0;
```

## typedef
```c
typedef struct {
    char nome[50];
    int eta;
} Persona;

Persona p1;  // Non serve "struct"
```

## Array di struct
```c
struct Studente classe[30];
classe[0] = s1;
```

struct organizza dati correlati!', 
'2024-09-27 11:30:00', 245, 'approvato', 20, 6),

('File I/O', 
'# FILE IN C

## Apertura
```c
FILE *fp = fopen("file.txt", "r");  // r, w, a, r+, w+, a+
if(fp == NULL) {
    perror("Errore");
    return 1;
}
```

## Lettura
```c
// Carattere
int c;
while((c = fgetc(fp)) != EOF)
    putchar(c);

// Riga
char buffer[256];
while(fgets(buffer, sizeof(buffer), fp) != NULL)
    printf("%s", buffer);

// Formattato
int num;
char str[50];
fscanf(fp, "%d %s", &num, str);
```

## Scrittura
```c
FILE *fp = fopen("output.txt", "w");
fprintf(fp, "Hello %s!", "World");
fputs("Riga\\n", fp);
```

## Chiusura
```c
fclose(fp);  // SEMPRE!
```

## File Binari
```c
struct Dati d = {10, 3.14};
FILE *fp = fopen("data.bin", "wb");
fwrite(&d, sizeof(d), 1, fp);
fclose(fp);

fp = fopen("data.bin", "rb");
fread(&d, sizeof(d), 1, fp);
fclose(fp);
```', 
'2024-10-04 15:45:00', 289, 'approvato', 5, 6),

('Preprocessore C', 
'# PREPROCESSORE

## #include
```c
#include <stdio.h>      // Libreria standard
#include "myheader.h"   // Header locale
```

## #define
```c
#define PI 3.14159
#define MAX_SIZE 100
#define SQUARE(x) ((x) * (x))  // Sempre parentesi!
#define MAX(a,b) ((a) > (b) ? (a) : (b))
```

## Compilazione Condizionale
```c
#ifdef DEBUG
    printf("Debug: x = %d\\n", x);
#endif

#ifndef MAX_VALUE
    #define MAX_VALUE 100
#endif

#if defined(WINDOWS)
    // Codice Windows
#elif defined(LINUX)
    // Codice Linux
#endif
```

## Include Guards
```c
// myheader.h
#ifndef MYHEADER_H
#define MYHEADER_H

// Contenuto header

#endif
```

Preprocessore eseguito prima della compilazione!', 
'2024-10-11 10:00:00', 0, 'in_revisione', 26, 6);

-- Fisica Generale I (corso 4) - 3 appunti
INSERT INTO appunti (titolo, contenuto, data_pubblicazione, numero_visualizzazioni, stato, idutente, idcorso) VALUES
('Cinematica', 
'# CINEMATICA

## Grandezze
- **Posizione**: r = (x, y, z)
- **Velocità**: v = dr/dt
- **Accelerazione**: a = dv/dt = d²r/dt²

## MRU (Moto Rettilineo Uniforme)
v = costante, a = 0
x(t) = x₀ + v·t

## MRUA (Uniformemente Accelerato)
a = costante
- v(t) = v₀ + a·t
- x(t) = x₀ + v₀·t + ½a·t²
- v² = v₀² + 2a·Δx

### Caduta Libera
a = g = 9.81 m/s²
- h(t) = h₀ + v₀·t - ½g·t²
- v(t) = v₀ - g·t

## Moto Parabolico
- x(t) = v₀·cos(θ)·t
- y(t) = v₀·sin(θ)·t - ½g·t²

Gittata: R = v₀²·sin(2θ)/g
Massima per θ = 45°

## Moto Circolare Uniforme
v = costante in modulo
- ω = 2π/T = 2πf
- v = ωr
- a_centripeta = v²/r = ω²r (verso centro)', 
'2024-09-21 14:00:00', 312, 'approvato', 11, 4),

('Dinamica - Leggi di Newton', 
'# DINAMICA

## Prima Legge (Inerzia)
Corpo mantiene stato di quiete o moto rettilineo uniforme se forza risultante nulla.

## Seconda Legge
**F = m·a**
Forza = massa × accelerazione

Unità: [F] = N = kg·m/s²

## Terza Legge (Azione-Reazione)
Se A esercita forza F su B, allora B esercita -F su A.

## Forze Fondamentali

### Peso
P = m·g (verso basso)
g ≈ 9.81 m/s²

### Normale
Forza perpendicolare a superficie.
Reazione vincolare.

### Attrito
- **Statico**: f_s ≤ μ_s·N (impedisce movimento)
- **Dinamico**: f_d = μ_d·N (oppone movimento)
μ_d < μ_s

### Tensione
Forza trasmessa da funi/cavi.

## Applicazioni
- Piano inclinato: mg·sin(θ) (componente parallela)
- Pendolo: T - mg·cos(θ) = m·v²/r
- Moto circolare: F_centripeta = m·v²/r', 
'2024-09-28 16:00:00', 298, 'approvato', 5, 4),

('Lavoro ed Energia', 
'# LAVORO ED ENERGIA

## Lavoro
W = F · Δr = F·Δr·cos(θ)

Unità: [W] = J = N·m

Lavoro positivo se F e Δr stesso verso.
Lavoro negativo se opposti (es. attrito).

## Energia Cinetica
K = ½mv²

### Teorema Lavoro-Energia
W_totale = ΔK = K_f - K_i

## Energia Potenziale

### Gravitazionale
U_g = mgh (vicino Terra)

### Elastica
U_el = ½kx² (molla)

## Conservazione Energia
Se solo forze conservative:
E = K + U = costante

K_i + U_i = K_f + U_f

## Potenza
P = W/t = F·v

Unità: [P] = W = J/s = Watt

Con forze non conservative (attrito):
E_mecc_f = E_mecc_i - W_attrito', 
'2024-10-05 13:15:00', 276, 'approvato', 27, 4);

-- Altri corsi con 2-3 appunti ciascuno per variabilità
-- Analisi Matematica II (corso 2) - 3 appunti
INSERT INTO appunti (titolo, contenuto, data_pubblicazione, numero_visualizzazioni, stato, idutente, idcorso) VALUES
('Funzioni a più variabili', 
'# FUNZIONI A PIÙ VARIABILI

z = f(x, y)

## Derivate Parziali
- ∂f/∂x: derivata rispetto a x (y costante)
- ∂f/∂y: derivata rispetto a y (x costante)

## Gradiente
∇f = (∂f/∂x, ∂f/∂y)
Vettore che punta nella direzione di massima crescita.

## Massimi e Minimi
Punti critici: ∇f = 0

Matrice Hessiana:
H = [∂²f/∂x²    ∂²f/∂x∂y]
    [∂²f/∂y∂x   ∂²f/∂y²]

- det(H) > 0, ∂²f/∂x² > 0 → minimo
- det(H) > 0, ∂²f/∂x² < 0 → massimo
- det(H) < 0 → punto di sella', 
'2024-09-30 11:00:00', 234, 'approvato', 14, 2),

('Integrali Doppi', 
'# INTEGRALI DOPPI

∫∫_D f(x,y) dxdy

Rappresenta volume sotto superficie z = f(x,y).

## Domini Regolari

### Tipo 1: y tra funzioni di x
D = {(x,y): a ≤ x ≤ b, g₁(x) ≤ y ≤ g₂(x)}
∫∫_D f dA = ∫_a^b [∫_{g₁(x)}^{g₂(x)} f(x,y) dy] dx

### Tipo 2: x tra funzioni di y
D = {(x,y): c ≤ y ≤ d, h₁(y) ≤ x ≤ h₂(y)}

## Cambio di Variabili

### Coordinate Polari
x = r cos(θ), y = r sin(θ)
dxdy = r drdθ (Jacobiano!)

Utile per domini circolari.', 
'2024-10-07 14:30:00', 189, 'approvato', 19, 2),

('Equazioni Differenziali', 
'# EQUAZIONI DIFFERENZIALI

## Primo Ordine

### Separabili
dy/dx = g(x)h(y)
Soluzione: ∫dy/h(y) = ∫g(x)dx

### Lineari
y\' + p(x)y = q(x)
Fattore integrante: μ(x) = e^(∫p(x)dx)

## Secondo Ordine Lineari
ay\'\' + by\' + cy = 0

Equazione caratteristica: ar² + br + c = 0

- Radici reali distinte r₁, r₂: y = c₁e^(r₁x) + c₂e^(r₂x)
- Radici reali coincidenti r: y = (c₁ + c₂x)e^(rx)
- Radici complesse α ± iβ: y = e^(αx)(c₁cos(βx) + c₂sin(βx))', 
'2024-10-14 09:45:00', 0, 'in_revisione', 7, 2);

-- Altri appunti sparsi per gli altri corsi
INSERT INTO appunti (titolo, contenuto, data_pubblicazione, numero_visualizzazioni, stato, idutente, idcorso) VALUES
('Algebra Lineare - Matrici e Determinanti', 'Matrici: tabelle di numeri. Operazioni: somma, prodotto (righe per colonne). Determinante: numero associato a matrice quadrata. det(A) = 0 → matrice singolare. Proprietà: det(AB) = det(A)·det(B), det(A^T) = det(A).', '2024-09-17 10:00:00', 267, 'approvato', 16, 3),
('Spazi Vettoriali', 'Spazio vettoriale: insieme con operazioni + e ·. Sottospazio: sottoinsieme chiuso per operazioni. Base: insieme vettori linearmente indipendenti che generano lo spazio. Dimensione = numero elementi base.', '2024-09-25 15:20:00', 234, 'approvato', 21, 3),
('Autovalori e Autovettori', 'Av = λv. λ = autovalore, v = autovettore. Equazione caratteristica: det(A - λI) = 0. Diagonalizzazione: A = PDP^(-1) se A ha n autovettori l.i.', '2024-10-08 11:30:00', 198, 'approvato', 11, 3),
('Fisica II - Elettrostatica', 'Legge di Coulomb: F = k·q₁q₂/r². Campo elettrico: E = F/q. Potenziale: V = U/q. Condensatore: Q = C·V. Energia: U = ½CV².', '2024-10-01 14:00:00', 289, 'approvato', 13, 5),
('Circuiti in Corrente Continua', 'Legge di Ohm: V = R·I. Resistenze in serie: R_tot = R₁ + R₂. Parallelo: 1/R_tot = 1/R₁ + 1/R₂. Leggi di Kirchhoff: nodi (correnti), maglie (tensioni).', '2024-10-09 10:15:00', 312, 'approvato', 27, 5),
('Architettura - CPU e Pipeline', 'CPU: Control Unit + ALU + Registri. Ciclo fetch-decode-execute. Pipeline: sovrapposizione fasi. Hazard: data, control, structural. Soluzioni: forwarding, stall, branch prediction.', '2024-09-23 13:00:00', 378, 'approvato', 9, 11),
('Cache Memory', 'Cache: memoria veloce tra CPU e RAM. Località: temporale e spaziale. Mapping: diretto, associativo, set-associativo. Politiche: write-through, write-back. LRU replacement.', '2024-10-02 16:45:00', 345, 'approvato', 18, 11),
('Controlli Automatici - Trasformata di Laplace', 'Trasformata di Laplace: F(s) = ∫₀^∞ f(t)e^(-st)dt. Proprietà: linearità, derivazione, integrazione. Tabella trasformate. Antitrasformata per frazioni parziali.', '2024-09-29 11:20:00', 223, 'approvato', 15, 12),
('Funzione di Trasferimento', 'G(s) = Y(s)/U(s) (rapporto output/input). Poli e zeri. Stabilità: tutti poli con Re < 0. Diagramma di Bode: guadagno e fase vs frequenza.', '2024-10-10 14:00:00', 201, 'approvato', 24, 12),
('Segnali - Trasformata di Fourier', 'F(ω) = ∫_{-∞}^{∞} f(t)e^(-jωt)dt. Rappresenta segnale in frequenza. Proprietà: linearità, dualità, convoluzione. Serie di Fourier per segnali periodici.', '2024-09-26 15:30:00', 267, 'approvato', 20, 13),
('Campionamento e Aliasing', 'Teorema del campionamento (Nyquist): f_s ≥ 2f_max. Aliasing se f_s < 2f_max. Filtro anti-aliasing prima del campionamento. Ricostruzione con interpolazione.', '2024-10-11 10:45:00', 234, 'approvato', 8, 13),
('Ricerca Operativa - Programmazione Lineare', 'Ottimizzare z = c^T x soggetto a Ax ≤ b, x ≥ 0. Metodo del simplesso: vertici del poliedro. Dualità: min-max. Applicazioni: produzione, trasporto.', '2024-09-27 13:15:00', 189, 'approvato', 12, 14),
('Teoria dei Grafi', 'Grafo G = (V, E). Cammino minimo: Dijkstra (pesi non negativi), Bellman-Ford (pesi qualsiasi). Albero ricoprente minimo: Kruskal, Prim. Flusso massimo: Ford-Fulkerson.', '2024-10-12 11:00:00', 198, 'approvato', 17, 14),
('Ingegneria del Software - UML', 'Unified Modeling Language. Diagrammi: classi, sequenza, use case, attività, stati. Class diagram: classi, attributi, metodi, relazioni (associazione, aggregazione, composizione, ereditarietà).', '2024-09-28 14:30:00', 312, 'approvato', 22, 15),
('Design Patterns', 'Soluzioni riutilizzabili a problemi comuni. Creazionali: Singleton, Factory, Builder. Strutturali: Adapter, Decorator, Facade. Comportamentali: Observer, Strategy, Command.', '2024-10-13 09:00:00', 289, 'approvato', 10, 15),
('Testing Software', 'Unit test: singole unità. Integration test: interazione componenti. System test: sistema completo. TDD: test-driven development. Coverage: percentuale codice testato. Framework: JUnit, pytest.', '2024-10-18 15:00:00', 256, 'approvato', 25, 15);

-- ============================================
-- INSERIMENTO ISCRIZIONI (molti studenti iscritti a vari corsi)
-- ============================================

INSERT INTO iscrizioni (idutente, idcorso) VALUES
-- ale_rossi (4) iscritto a 8 corsi
(4, 1), (4, 3), (4, 6), (4, 7), (4, 8), (4, 9), (4, 10), (4, 15),

-- chiara_bianchi (5) iscritta a 6 corsi
(5, 1), (5, 2), (5, 3), (5, 4), (5, 6), (5, 7),

-- luca_verdi (6) iscritto a 7 corsi
(6, 1), (6, 4), (6, 5), (6, 6), (6, 7), (6, 8), (6, 9),

-- sara_neri (7) iscritta a 5 corsi
(7, 1), (7, 2), (7, 3), (7, 7), (7, 15),

-- marco_ferrari (8) iscritto a 6 corsi
(8, 6), (8, 7), (8, 8), (8, 9), (8, 10), (8, 15),

-- giulia_romano (9) iscritta a 7 corsi
(9, 1), (9, 3), (9, 4), (9, 6), (9, 9), (9, 10), (9, 11),

-- andrea_colombo (10) iscritto a 8 corsi
(10, 1), (10, 2), (10, 6), (10, 7), (10, 8), (10, 9), (10, 11), (10, 15),

-- francesca_ricci (11) iscritta a 5 corsi
(11, 1), (11, 3), (11, 4), (11, 6), (11, 7),

-- matteo_marino (12) iscritto a 7 corsi
(12, 1), (12, 6), (12, 7), (12, 8), (12, 9), (12, 10), (12, 15),

-- elena_greco (13) iscritta a 6 corsi
(13, 1), (13, 2), (13, 3), (13, 4), (13, 5), (13, 6),

-- davide_bruno (14) iscritto a 5 corsi
(14, 6), (14, 7), (14, 8), (14, 9), (14, 10),

-- valentina_gallo (15) iscritta a 6 corsi
(15, 1), (15, 2), (15, 3), (15, 4), (15, 6), (15, 7),

-- simone_conti (16) iscritto a 7 corsi
(16, 1), (16, 3), (16, 6), (16, 7), (16, 8), (16, 9), (16, 11),

-- martina_de_luca (17) iscritta a 5 corsi
(17, 1), (17, 4), (17, 6), (17, 7), (17, 15),

-- federico_costa (18) iscritto a 8 corsi
(18, 1), (18, 2), (18, 6), (18, 7), (18, 8), (18, 9), (18, 10), (18, 15),

-- alice_fontana (19) iscritta a 6 corsi
(19, 1), (19, 2), (19, 3), (19, 6), (19, 8), (19, 15),

-- lorenzo_caruso (20) iscritto a 7 corsi
(20, 1), (20, 4), (20, 6), (20, 7), (20, 8), (20, 13), (20, 15),

-- sofia_vitale (21) iscritta a 5 corsi
(21, 1), (21, 3), (21, 6), (21, 7), (21, 9),

-- gabriele_moretti (22) iscritto a 6 corsi
(22, 6), (22, 7), (22, 8), (22, 9), (22, 10), (22, 15),

-- anna_lombardi (23) iscritta a 7 corsi
(23, 1), (23, 2), (23, 4), (23, 6), (23, 7), (23, 10), (23, 15),

-- riccardo_barbieri (24) iscritto a 5 corsi
(24, 6), (24, 7), (24, 8), (24, 9), (24, 12),

-- beatrice_ferrara (25) iscritta a 6 corsi
(25, 1), (25, 3), (25, 6), (25, 10), (25, 11), (25, 15),

-- filippo_santoro (26) iscritto a 4 corsi
(26, 6), (26, 7), (26, 8), (26, 15),

-- camilla_marini (27) iscritta a 7 corsi
(27, 1), (27, 4), (27, 5), (27, 6), (27, 7), (27, 8), (27, 9),

-- michele_russo (28) iscritto a 5 corsi
(28, 6), (28, 7), (28, 9), (28, 10), (28, 15),

-- giorgia_villa (29) iscritta a 6 corsi
(29, 1), (29, 2), (29, 3), (29, 6), (29, 7), (29, 8),

-- pietro_serra (30) iscritto a 7 corsi
(30, 1), (30, 6), (30, 7), (30, 8), (30, 9), (30, 10), (30, 11);

-- ============================================
-- INSERIMENTO RECENSIONI (molte recensioni agli appunti)
-- ============================================

-- Recensioni per Analisi I (appunti 1-5)
INSERT INTO recensioni (valutazione, idappunto, idutente) VALUES
-- Appunto 1 (Limiti e Continuità) - 15 recensioni
(5, 1, 4), (5, 1, 5), (4, 1, 6), (5, 1, 7), (5, 1, 9), (4, 1, 11), (5, 1, 13), (5, 1, 15), 
(4, 1, 16), (5, 1, 19), (5, 1, 21), (4, 1, 23), (5, 1, 29), (5, 1, 30), (4, 1, 17),

-- Appunto 2 (Derivate) - 12 recensioni
(5, 2, 4), (4, 2, 5), (5, 2, 6), (5, 2, 9), (4, 2, 11), (5, 2, 13), (5, 2, 15), 
(4, 2, 17), (5, 2, 21), (5, 2, 23), (4, 2, 29), (5, 2, 30),

-- Appunto 3 (Integrali) - 10 recensioni
(5, 3, 4), (5, 3, 6), (4, 3, 9), (5, 3, 11), (5, 3, 13), (4, 3, 15), 
(5, 3, 19), (5, 3, 21), (4, 3, 23), (5, 3, 30),

-- Appunto 4 (Serie) - 8 recensioni
(4, 4, 5), (5, 4, 6), (4, 4, 9), (5, 4, 13), (5, 4, 15), (4, 4, 19), (5, 4, 23), (5, 4, 29),

-- Appunto 5 (Esercizi) - 18 recensioni
(5, 5, 4), (5, 5, 5), (5, 5, 6), (5, 5, 7), (5, 5, 9), (4, 5, 11), (5, 5, 13), (5, 5, 15), 
(5, 5, 16), (4, 5, 17), (5, 5, 19), (5, 5, 21), (5, 5, 23), (4, 5, 25), (5, 5, 29), (5, 5, 30), (4, 5, 27), (5, 5, 10),

-- Recensioni per Algoritmi (appunti 6-11)
-- Appunto 6 (Complessità) - 20 recensioni
(5, 6, 4), (5, 6, 6), (5, 6, 8), (5, 6, 10), (5, 6, 12), (4, 6, 14), (5, 6, 16), (5, 6, 18), 
(5, 6, 20), (5, 6, 22), (4, 6, 24), (5, 6, 26), (5, 6, 28), (5, 6, 30), (4, 6, 5), (5, 6, 7), 
(5, 6, 9), (4, 6, 11), (5, 6, 17), (5, 6, 23),

-- Appunto 7 (BST) - 16 recensioni
(5, 7, 4), (5, 7, 6), (4, 7, 8), (5, 7, 10), (5, 7, 12), (5, 7, 16), (4, 7, 18), (5, 7, 20), 
(5, 7, 22), (5, 7, 26), (4, 7, 28), (5, 7, 30), (5, 7, 5), (4, 7, 11), (5, 7, 17), (5, 7, 23),

-- Appunto 8 (Ordinamento) - 22 recensioni
(5, 8, 4), (5, 8, 6), (5, 8, 8), (5, 8, 10), (5, 8, 12), (5, 8, 14), (5, 8, 16), (5, 8, 18), 
(5, 8, 20), (4, 8, 22), (5, 8, 26), (5, 8, 28), (5, 8, 30), (4, 8, 5), (5, 8, 7), (5, 8, 9), 
(5, 8, 11), (4, 8, 17), (5, 8, 23), (5, 8, 25), (4, 8, 27), (5, 8, 29),

-- Appunto 9 (Liste) - 10 recensioni
(4, 9, 4), (5, 9, 6), (4, 9, 10), (5, 9, 12), (5, 9, 17), (4, 9, 20), (5, 9, 26), (5, 9, 28), (4, 9, 5), (5, 9, 11),

-- Appunto 10 (Grafi) - 14 recensioni
(5, 10, 4), (5, 10, 6), (5, 10, 10), (5, 10, 12), (4, 10, 16), (5, 10, 20), (5, 10, 22), (5, 10, 26), 
(4, 10, 28), (5, 10, 30), (5, 10, 7), (4, 10, 17), (5, 10, 23), (5, 10, 29),

-- Appunto 11 (Stack e Code) - 18 recensioni
(5, 11, 4), (5, 11, 6), (5, 11, 8), (5, 11, 10), (5, 11, 12), (4, 11, 16), (5, 11, 18), (5, 11, 20), 
(5, 11, 22), (4, 11, 26), (5, 11, 28), (5, 11, 30), (5, 11, 5), (4, 11, 7), (5, 11, 11), (5, 11, 17), 
(4, 11, 23), (5, 11, 29),

-- Recensioni per Basi di Dati (appunti 12-16)
-- Appunto 12 (Modello ER) - 14 recensioni
(5, 12, 4), (5, 12, 8), (4, 12, 10), (5, 12, 12), (5, 12, 14), (5, 12, 16), (5, 12, 18), (4, 12, 22), 
(5, 12, 26), (5, 12, 28), (5, 12, 29), (4, 12, 6), (5, 12, 20), (5, 12, 30),

-- Appunto 13 (SQL) - 21 recensioni
(5, 13, 4), (5, 13, 6), (5, 13, 8), (5, 13, 10), (5, 13, 12), (5, 13, 14), (5, 13, 16), (5, 13, 18), 
(5, 13, 20), (5, 13, 22), (5, 13, 26), (5, 13, 28), (5, 13, 30), (4, 13, 5), (5, 13, 7), (4, 13, 9), 
(5, 13, 11), (5, 13, 19), (4, 13, 23), (5, 13, 25), (5, 13, 29),

-- Appunto 14 (Normalizzazione) - 12 recensioni
(5, 14, 4), (4, 14, 8), (5, 14, 10), (5, 14, 12), (5, 14, 14), (4, 14, 16), (5, 14, 18), (5, 14, 22), 
(5, 14, 26), (5, 14, 28), (4, 14, 6), (5, 14, 20),

-- Appunto 15 (Transazioni) - 11 recensioni
(5, 15, 8), (5, 15, 10), (4, 15, 12), (5, 15, 14), (5, 15, 18), (5, 15, 22), (4, 15, 26), 
(5, 15, 28), (5, 15, 30), (5, 15, 6), (4, 15, 20),

-- Appunto 16 (Ottimizzazione) - 10 recensioni
(5, 16, 4), (5, 16, 8), (5, 16, 10), (4, 16, 12), (5, 16, 18), (5, 16, 22), (5, 16, 26), (4, 16, 28), (5, 16, 6), (5, 16, 20),

-- Recensioni per Sistemi Operativi (appunti 17-20)
-- Appunto 17 (Processi) - 17 recensioni
(5, 17, 4), (5, 17, 6), (5, 17, 8), (5, 17, 10), (5, 17, 12), (5, 17, 14), (4, 17, 16), (5, 17, 18), 
(5, 17, 22), (5, 17, 27), (5, 17, 28), (5, 17, 30), (4, 17, 9), (5, 17, 23), (5, 17, 25), (4, 17, 26), (5, 17, 29),

-- Appunto 18 (Scheduling) - 14 recensioni
(5, 18, 4), (5, 18, 8), (5, 18, 10), (4, 18, 12), (5, 18, 14), (5, 18, 16), (5, 18, 18), (5, 18, 22), 
(4, 18, 27), (5, 18, 28), (5, 18, 30), (5, 18, 6), (4, 18, 9), (5, 18, 23),

-- Appunto 19 (Memoria) - 16 recensioni
(5, 19, 4), (5, 19, 6), (5, 19, 8), (5, 19, 10), (5, 19, 12), (4, 19, 16), (5, 19, 18), (5, 19, 22), 
(5, 19, 27), (5, 19, 28), (5, 19, 30), (5, 19, 9), (4, 19, 14), (5, 19, 23), (5, 19, 25), (4, 19, 29),

-- Appunto 20 (Sincronizzazione) - 12 recensioni
(5, 20, 4), (5, 20, 8), (5, 20, 10), (4, 20, 12), (5, 20, 16), (5, 20, 18), (5, 20, 27), (5, 20, 28), 
(4, 20, 30), (5, 20, 6), (5, 20, 9), (5, 20, 22),

-- Recensioni per Reti (appunti 21-25)
-- Appunto 21 (Modelli) - 15 recensioni
(5, 21, 4), (5, 21, 8), (5, 21, 9), (5, 21, 10), (5, 21, 12), (5, 21, 14), (4, 21, 18), (5, 21, 22), 
(5, 21, 23), (5, 21, 25), (5, 21, 28), (5, 21, 30), (4, 21, 6), (5, 21, 27), (5, 21, 29),

-- Appunto 22 (IP) - 19 recensioni
(5, 22, 4), (5, 22, 8), (5, 22, 9), (5, 22, 10), (5, 22, 12), (5, 22, 14), (5, 22, 18), (5, 22, 22), 
(5, 22, 23), (5, 22, 25), (5, 22, 28), (5, 22, 30), (4, 22, 6), (5, 22, 16), (4, 22, 27), (5, 22, 29), 
(5, 22, 11), (4, 22, 20), (5, 22, 26),

-- Appunto 23 (TCP/UDP) - 17 recensioni
(5, 23, 4), (5, 23, 8), (5, 23, 9), (5, 23, 10), (5, 23, 12), (5, 23, 14), (5, 23, 18), (5, 23, 22), 
(5, 23, 23), (4, 23, 25), (5, 23, 28), (5, 23, 30), (5, 23, 6), (4, 23, 16), (5, 23, 27), (5, 23, 29), (4, 23, 20),

-- Appunto 24 (HTTP/DNS) - 20 recensioni
(5, 24, 4), (5, 24, 8), (5, 24, 9), (5, 24, 10), (5, 24, 12), (5, 24, 14), (5, 24, 18), (5, 24, 22), 
(5, 24, 23), (5, 24, 25), (5, 24, 28), (5, 24, 30), (5, 24, 6), (5, 24, 16), (4, 24, 27), (5, 24, 29), 
(5, 24, 11), (4, 24, 20), (5, 24, 26), (5, 24, 5),

-- Appunto 25 (Sicurezza) - 13 recensioni
(5, 25, 4), (5, 25, 8), (5, 25, 9), (5, 25, 10), (4, 25, 12), (5, 25, 18), (5, 25, 22), (5, 25, 23), 
(5, 25, 28), (5, 25, 30), (4, 25, 6), (5, 25, 27), (5, 25, 29),

-- Recensioni per Programmazione I (appunti 26-29)
-- Appunto 26 (Puntatori) - 13 recensioni
(5, 26, 4), (5, 26, 5), (5, 26, 6), (5, 26, 8), (4, 26, 10), (5, 26, 11), (5, 26, 13), (5, 26, 17), 
(5, 26, 20), (4, 26, 23), (5, 26, 27), (5, 26, 29), (5, 26, 30),

-- Appunto 27 (Struct) - 10 recensioni
(4, 27, 4), (5, 27, 6), (5, 27, 8), (5, 27, 11), (4, 27, 13), (5, 27, 17), (5, 27, 20), (5, 27, 27), (4, 27, 29), (5, 27, 30),

-- Appunto 28 (File I/O) - 11 recensioni
(5, 28, 4), (5, 28, 5), (4, 28, 6), (5, 28, 8), (5, 28, 11), (5, 28, 13), (5, 28, 17), (4, 28, 20), (5, 28, 27), (5, 28, 29), (5, 28, 30),

-- Recensioni per Fisica I (appunti 30-32)
-- Appunto 30 (Cinematica) - 12 recensioni
(5, 30, 5), (5, 30, 6), (5, 30, 9), (5, 30, 11), (4, 30, 13), (5, 30, 15), (5, 30, 21), (5, 30, 23), 
(4, 30, 27), (5, 30, 29), (5, 30, 7), (5, 30, 19),

-- Appunto 31 (Dinamica) - 11 recensioni
(5, 31, 5), (5, 31, 6), (5, 31, 9), (4, 31, 11), (5, 31, 13), (5, 31, 15), (5, 31, 21), (5, 31, 23), 
(5, 31, 27), (4, 31, 7), (5, 31, 19),

-- Appunto 32 (Energia) - 10 recensioni
(5, 32, 5), (5, 32, 6), (5, 32, 9), (5, 32, 11), (4, 32, 13), (5, 32, 15), (5, 32, 21), (4, 32, 27), (5, 32, 7), (5, 32, 19),

-- Recensioni per altri appunti (33-50)
-- Distribuisco recensioni in modo realistico
(5, 33, 5), (4, 33, 7), (5, 33, 13), (5, 33, 15), (5, 33, 19), (4, 33, 21), (5, 33, 23), (5, 33, 29),
(5, 34, 7), (5, 34, 13), (4, 34, 15), (5, 34, 19), (5, 34, 21), (5, 34, 29), (4, 34, 5),
(5, 36, 5), (5, 36, 13), (5, 36, 15), (4, 36, 27), (5, 36, 29), (5, 36, 7),
(5, 37, 5), (5, 37, 13), (5, 37, 15), (5, 37, 27), (4, 37, 29), (5, 37, 6), (5, 37, 9),
(5, 38, 4), (5, 38, 9), (5, 38, 16), (4, 38, 18), (5, 38, 25), (5, 38, 30),
(5, 39, 4), (5, 39, 9), (4, 39, 16), (5, 39, 18), (5, 39, 25), (5, 39, 30), (5, 39, 10),
(5, 40, 4), (5, 40, 15), (5, 40, 24), (4, 40, 12), (5, 40, 16),
(4, 41, 4), (5, 41, 15), (5, 41, 24), (5, 41, 12), (5, 41, 16), (5, 41, 10),
(5, 42, 4), (5, 42, 8), (5, 42, 20), (4, 42, 13), (5, 42, 17),
(5, 43, 4), (5, 43, 8), (4, 43, 20), (5, 43, 13), (5, 43, 17), (5, 43, 12),
(5, 44, 4), (5, 44, 12), (5, 44, 17), (4, 44, 14), (5, 44, 24),
(5, 45, 4), (5, 45, 22), (4, 45, 10), (5, 45, 15), (5, 45, 17), (5, 45, 25),
(5, 46, 4), (5, 46, 22), (5, 46, 10), (4, 46, 15), (5, 46, 25),
(5, 47, 4), (5, 47, 22), (5, 47, 10), (5, 47, 15), (4, 47, 25), (5, 47, 26),
(5, 48, 4), (5, 48, 22), (4, 48, 10), (5, 48, 15), (5, 48, 25), (5, 48, 26),
(5, 49, 4), (5, 49, 10), (5, 49, 22), (5, 49, 25), (4, 49, 26), (5, 49, 28);

-- ============================================
-- FINE POPOLAMENTO
-- ============================================

-- Statistiche finali
SELECT 'Database popolato con successo!' AS messaggio;
SELECT CONCAT('Utenti: ', COUNT(*)) AS stat FROM utenti;
SELECT CONCAT('Corsi: ', COUNT(*)) AS stat FROM corsi;
SELECT CONCAT('Appunti: ', COUNT(*)) AS stat FROM appunti;
SELECT CONCAT('Iscrizioni: ', COUNT(*)) AS stat FROM iscrizioni;
SELECT CONCAT('Recensioni: ', COUNT(*)) AS stat FROM recensioni;
SELECT CONCAT('SSD: ', COUNT(*)) AS stat FROM ssd;
