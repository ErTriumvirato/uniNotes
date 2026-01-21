<?php
class DatabaseHelper
{
    private $db;
    public $errorConnection;

    // Connessione al database
    public function __construct($servername, $username, $password, $dbname, $port)
    {
        try {
            $this->db = new mysqli($servername, $username, $password, $dbname, $port);
            if ($this->db->connect_error) {
                $this->errorConnection = "Non è stato possibile raggiungere il database";
            }
        } catch (Exception $e) {
            $this->errorConnection = "Impossibile connettersi al database";
        }
    }

    // Funzioni per la gestione degli utenti ----------------------------------------------------------------------

    // Crea un nuovo utente
    public function createUser($username, $email, $password, $ruolo)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO utenti (username, email, password, isAdmin) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $email, $hash, $ruolo);
        return $stmt->execute();
    }

    // Ottieni un utente per ID
    public function getUserById($idutente)
    {
        $stmt = $this->db->prepare("SELECT idutente, username, email, isAdmin FROM utenti WHERE idutente = ?");
        $stmt->bind_param("i", $idutente);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Ottieni la valutazione media di un autore
    public function getAuthorAverageRating($idutente)
    {
        $query = "SELECT ROUND(AVG(r.valutazione), 1) as avg_rating 
                  FROM recensioni r 
                  JOIN appunti a ON r.idappunto = a.idappunto 
                  WHERE a.idutente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idutente);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['avg_rating'] !== null ? $row['avg_rating'] : 0;
    }

    // Ottieni il conteggio degli amministratori
    public function getAdminCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM utenti WHERE isAdmin = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    // Ottieni il conteggio dei corsi seguiti da un utente
    public function getFollowedCoursesCount($idutente)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM iscrizioni WHERE idutente = ?");
        $stmt->bind_param("i", $idutente);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }

    // Aggiorna un utente esistente
    public function updateUser($idutente, $username, $email, $ruolo, $password = null)
    {
        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE utenti SET username = ?, email = ?, isAdmin = ?, password = ? WHERE idutente = ?");
            $stmt->bind_param("ssisi", $username, $email, $ruolo, $hash, $idutente);
        } else {
            $stmt = $this->db->prepare("UPDATE utenti SET username = ?, email = ?, isAdmin = ? WHERE idutente = ?");
            $stmt->bind_param("ssii", $username, $email, $ruolo, $idutente);
        }
        return $stmt->execute();
    }

    // Elimina un utente
    public function deleteUser($idutente)
    {
        $stmt = $this->db->prepare("DELETE FROM utenti WHERE idutente = ?");
        $stmt->bind_param("i", $idutente);
        return $stmt->execute();
    }

    // Cerca utenti con filtri
    public function getUsersWithFilters($email = null, $username = null, $role = 'all', $search = null)
    {
        $query = "SELECT * FROM utenti";
        $conditions = [];
        $params = [];
        $types = "";

        // Ricerca esatta per email o username
        if (!empty($email) || !empty($username)) {
            $field = !empty($email) ? 'email' : 'username';
            $conditions[] = "$field = ?";
            $params[] = !empty($email) ? $email : $username;
            $types .= "s";
        }

        // Ricerca per username
        if (!empty($search)) {
            $conditions[] = "username LIKE ?";
            $params[] = "%" . $search . "%";
            $types .= "s";
        }

        if ($role === 'admin') {
            $conditions[] = "isAdmin = 1";
        } elseif ($role === 'user') {
            $conditions[] = "isAdmin = 0";
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " ORDER BY isAdmin DESC, username ASC";

        $stmt = $this->db->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        // Se ricerca esatta, ritorna singolo utente
        if (!empty($email) || !empty($username)) {
            return $result->fetch_assoc();
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Funzioni per la gestione dei corsi ----------------------------------------------------------------------

    // Crea un nuovo corso
    public function createCourse($nome, $descrizione, $idssd)
    {
        $stmt = $this->db->prepare("INSERT INTO corsi (nome, descrizione, idssd) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nome, $descrizione, $idssd);
        return $stmt->execute();
    }

    // Ottieni un corso per ID
    public function getCourseById($idcorso)
    {
        $query = "SELECT corsi.*, ssd.nome AS nomeSSD 
              FROM corsi 
              JOIN ssd ON corsi.idssd = ssd.idssd 
              WHERE corsi.idcorso = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idcorso);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Aggiorna un corso esistente
    public function updateCourse($idcorso, $nome, $descrizione, $idssd)
    {
        $stmt = $this->db->prepare("UPDATE corsi SET nome = ?, descrizione = ?, idssd = ? WHERE idcorso = ?");
        $stmt->bind_param("ssii", $nome, $descrizione, $idssd, $idcorso);
        return $stmt->execute();
    }

    // Elimina un corso
    public function deleteCourse($idcorso)
    {
        $stmt = $this->db->prepare("DELETE FROM corsi WHERE idcorso = ?");
        $stmt->bind_param("i", $idcorso);
        return $stmt->execute();
    }

    // Ottieni corsi con filtri opzionali
    public function getCoursesWithFilters($idutente = null, $ssd = null, $filterType = 'all', $search = null)
    {
        $query = "SELECT corsi.idcorso, corsi.nome AS nomeCorso, ssd.nome AS nomeSSD, corsi.descrizione AS descrizioneCorso 
                FROM corsi 
                JOIN ssd ON corsi.idssd = ssd.idssd
                WHERE 1=1";

        $params = [];
        $types = "";

        if (!empty($search)) {
            $query .= " AND corsi.nome LIKE ?";
            $params[] = "%$search%";
            $types .= "s";
        }

        if (!empty($ssd)) {
            $query .= " AND ssd.nome = ?";
            $params[] = $ssd;
            $types .= "s";
        }

        if ($idutente && $filterType !== 'all') {
            $not = ($filterType === 'not_followed') ? "NOT" : "";
            $query .= " AND corsi.idcorso $not IN (SELECT idcorso FROM iscrizioni WHERE idutente = ?)";
            $params[] = $idutente;
            $types .= "i";
        }

        $stmt = $this->db->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Iscrive un utente a un corso
    public function followCourse($idutente, $idcorso)
    {
        $stmt = $this->db->prepare("INSERT INTO iscrizioni (idutente, idcorso) VALUES (?, ?)");
        $stmt->bind_param("ii", $idutente, $idcorso);
        $stmt->execute();
    }

    // Rimuove l'iscrizione di un utente da un corso
    public function unfollowCourse($idutente, $idcorso)
    {
        $stmt = $this->db->prepare("DELETE FROM iscrizioni WHERE idutente = ? AND idcorso = ?");
        $stmt->bind_param("ii", $idutente, $idcorso);
        $stmt->execute();
    }

    // Verifica se un utente è iscritto a un corso
    public function isFollowingCourse($idutente, $idcorso)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM iscrizioni WHERE idutente = ? AND idcorso = ?");
        $stmt->bind_param("ii", $idutente, $idcorso);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    // Funzioni per la gestione degli SSD ----------------------------------------------------------------------

    // Crea un nuovo SSD
    public function createSSD($nome, $descrizione)
    {
        $stmt = $this->db->prepare("INSERT INTO ssd (nome, descrizione) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $descrizione);
        return $stmt->execute();
    }

    // Ottiene un SSD per ID
    public function getSSDById($idssd)
    {
        $stmt = $this->db->prepare("SELECT * FROM ssd WHERE idssd = ?");
        $stmt->bind_param("i", $idssd);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Aggiorna un SSD esistente
    public function updateSSD($idssd, $nome, $descrizione)
    {
        $stmt = $this->db->prepare("UPDATE ssd SET nome = ?, descrizione = ? WHERE idssd = ?");
        $stmt->bind_param("ssi", $nome, $descrizione, $idssd);
        return $stmt->execute();
    }

    // Elimina un SSD
    public function deleteSSD($idssd)
    {
        $stmt = $this->db->prepare("DELETE FROM ssd WHERE idssd = ?");
        $stmt->bind_param("i", $idssd);
        return $stmt->execute();
    }

    // Ottiene tutti gli SSD con ricerca
    public function getAllSSD($search = null)
    {
        $query = "SELECT * FROM ssd";
        $params = [];
        $types = "";

        if (!empty($search)) {
            $query .= " WHERE nome LIKE ? OR descrizione LIKE ?";
            $searchTerm = "%" . $search . "%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= "ss";
        }

        $stmt = $this->db->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Funzioni per la gestione degli appunti ----------------------------------------------------------------------

    // Crea un nuovo appunto
    public function createNote($idcorso, $titolo, $contenuto, $idutente)
    {
        $stmt = $this->db->prepare("INSERT INTO appunti (idcorso, titolo, contenuto, idutente, data_pubblicazione, numero_visualizzazioni, stato) VALUES (?, ?, ?, ?, NOW(), 0, 'in_revisione')");
        $stmt->bind_param("issi", $idcorso, $titolo, $contenuto, $idutente);
        return $stmt->execute();
    }

    // Ottiene un appunto per ID
    public function getNoteById($idNote)
    {
        $query = "SELECT appunti.*, utenti.username AS autore, corsi.nome AS nome_corso,
            ROUND(AVG(recensioni.valutazione), 1) AS media_recensioni,
            COUNT(recensioni.idrecensione) AS numero_recensioni
            FROM appunti
            JOIN utenti ON appunti.idutente = utenti.idutente
            JOIN corsi ON appunti.idcorso = corsi.idcorso
            LEFT JOIN recensioni ON appunti.idappunto = recensioni.idappunto
            WHERE appunti.idappunto = ?
            GROUP BY appunti.idappunto
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idNote);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Aggiorna un appunto esistente
    public function updateNote($idNote, $idcorso, $titolo, $contenuto)
    {
        $stmt = $this->db->prepare("UPDATE appunti SET idcorso = ?, titolo = ?, contenuto = ?, stato = 'in_revisione', data_pubblicazione = NOW() WHERE idappunto = ?");
        $stmt->bind_param("issi", $idcorso, $titolo, $contenuto, $idNote);
        return $stmt->execute();
    }

    // Approva un appunto
    public function approveNote($idNote)
    {
        $stmt = $this->db->prepare("UPDATE appunti SET stato = 'approvato' WHERE idappunto = ?");
        $stmt->bind_param("i", $idNote);
        return $stmt->execute();
    }

    // Rifiuta un appunto
    public function rejectNote($idNote)
    {
        $stmt = $this->db->prepare("UPDATE appunti SET stato = 'rifiutato' WHERE idappunto = ?");
        $stmt->bind_param("i", $idNote);
        return $stmt->execute();
    }

    // Elimina un appunto
    public function deleteNote($idNote)
    {
        $stmt = $this->db->prepare("DELETE FROM appunti WHERE idappunto = ?");
        $stmt->bind_param("i", $idNote);
        return $stmt->execute();
    }

    // Ottiene il conteggio degli appunti di un autore
    public function getNotesCountByAuthor($idutente, $onlyApproved = false)
    {
        $query = "SELECT COUNT(*) as count FROM appunti WHERE idutente = ?";
        if ($onlyApproved) {
            $query .= " AND stato = 'approvato'";
        }
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idutente);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }

    // Ottieni appunti per la home page con filtri opzionali
    public function getHomeNotes($idutente = null, $orderBy = 'data_pubblicazione', $limit = 6)
    {
        $joinUtente = $idutente ? "JOIN iscrizioni i ON a.idcorso = i.idcorso AND i.idutente = ?" : "";
        $orderField = ($orderBy === 'numero_visualizzazioni') ? "a.numero_visualizzazioni DESC, " : "";

        $sql = "SELECT a.*, u.username AS autore, c.nome AS nome_corso, ROUND(AVG(r.valutazione), 1) AS media_recensioni
                FROM appunti a
                JOIN utenti u ON a.idutente = u.idutente
                JOIN corsi c ON a.idcorso = c.idcorso
                LEFT JOIN recensioni r ON a.idappunto = r.idappunto
                $joinUtente
                WHERE a.stato = 'approvato'
                GROUP BY a.idappunto
                ORDER BY $orderField a.data_pubblicazione DESC
                LIMIT ?";

        $stmt = $this->db->prepare($sql);
        $idutente ? $stmt->bind_param("ii", $idutente, $limit) : $stmt->bind_param("i", $limit);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Ottiene appunti con filtri
    public function getNotesWithFilters($idutente = null, $idcorso = null, $sort = 'data_pubblicazione', $order = 'DESC', $search = '', $approvalFilter = 'approved')
    {
        $allowedSort = ['data_pubblicazione', 'media_recensioni', 'numero_visualizzazioni'];
        $allowedOrder = ['ASC', 'DESC'];
        $allowedApprovalFilters = ['approved', 'pending', 'refused', 'all'];

        $sort = in_array($sort, $allowedSort) ? $sort : 'data_pubblicazione';
        $order = in_array($order, $allowedOrder) ? $order : 'DESC';
        $approvalFilter = in_array($approvalFilter, $allowedApprovalFilters) ? $approvalFilter : 'approved';
        $search = $search ?? '';

        $query = "SELECT appunti.*, utenti.username AS autore, corsi.nome AS nome_corso,
              ROUND(AVG(recensioni.valutazione), 1) AS media_recensioni,
              COUNT(recensioni.idrecensione) AS numero_recensioni
              FROM appunti
              JOIN utenti ON appunti.idutente = utenti.idutente
              JOIN corsi ON appunti.idcorso = corsi.idcorso
              LEFT JOIN recensioni ON appunti.idappunto = recensioni.idappunto
              WHERE 1=1";

        $params = [];
        $types = "";

        // Filtro per stato approvazione
        if ($approvalFilter === 'approved') {
            $query .= " AND appunti.stato = 'approvato'";
        } elseif ($approvalFilter === 'pending') {
            $query .= " AND appunti.stato = 'in_revisione'";
        } elseif ($approvalFilter === 'refused') {
            $query .= " AND appunti.stato = 'rifiutato'";
        }
        // 'all' non aggiunge filtri

        if (!empty($idutente)) {
            $query .= " AND utenti.idutente = ?";
            $params[] = $idutente;
            $types .= "i";
        }

        if (!empty($idcorso)) {
            $query .= " AND corsi.idcorso = ?";
            $params[] = $idcorso;
            $types .= "i";
        }

        if (!empty($search)) {
            $query .= " AND (appunti.titolo LIKE ? OR utenti.username LIKE ? OR corsi.nome LIKE ?)";
            $searchTerm = "%" . $search . "%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= "sss";
        }

        $query .= " GROUP BY appunti.idappunto ORDER BY $sort $order";

        $stmt = $this->db->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Funzioni per la gestione delle recensioni ----------------------------------------------------------------------

    // Aggiunge una recensione
    public function addReview($idappunto, $idutente, $valutazione)
    {
        $stmt = $this->db->prepare("INSERT INTO recensioni (idappunto, idutente, valutazione) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $idappunto, $idutente, $valutazione);
        return $stmt->execute();
    }

    // Incrementa il contatore delle visualizzazioni di un appunto
    public function incrementNoteViews($idappunto)
    {
        $stmt = $this->db->prepare("UPDATE appunti SET numero_visualizzazioni = numero_visualizzazioni + 1 WHERE idappunto = ?");
        $stmt->bind_param("i", $idappunto);
        $stmt->execute();
    }

    // Elimina una recensione
    public function deleteReview($idrecensione, $idutente)
    {
        $stmt = $this->db->prepare("DELETE FROM recensioni WHERE idrecensione = ? AND idutente = ?");
        $stmt->bind_param("ii", $idrecensione, $idutente);
        return $stmt->execute();
    }

    // Ottiene le recensioni per un appunto (tutte o filtrate per utente)
    public function getReviewsByNote($idappunto, $idutente = null)
    {
        $query = "SELECT recensioni.*, utenti.username
                  FROM recensioni
                  JOIN utenti ON recensioni.idutente = utenti.idutente
                  WHERE idappunto = ?";
        $types = "i";
        $params = [$idappunto];

        if (!empty($idutente)) {
            $query .= " AND recensioni.idutente = ?";
            $types .= "i";
            $params[] = $idutente;
        }

        $query .= " ORDER BY idrecensione DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!empty($idutente)) {
            return $result->fetch_assoc();
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Verifica se un utente ha già recensito un appunto
    public function hasUserReviewed($idappunto, $idutente)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM recensioni WHERE idappunto = ? AND idutente = ?");
        $stmt->bind_param("ii", $idappunto, $idutente);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }
}
