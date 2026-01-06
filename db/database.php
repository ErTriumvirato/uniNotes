<?php
class DatabaseHelper
{
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port)
    {
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    public function getUserByUsername($username)
    {
        $query = "SELECT * FROM utenti WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function getAllSSD()
    {
        $query = "SELECT * FROM ssd";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCoursesWithSSD($search = null, $ssd = null, $idutente = null, $filterType = 'all')
    {
        $query = "SELECT corsi.idcorso, corsi.nome AS nomeCorso, ssd.nome AS nomeSSD, corsi.descrizione AS descrizioneCorso 
                  FROM corsi 
                  JOIN ssd ON corsi.idssd = ssd.idssd";

        $params = [];
        $types = "";
        $hasWhere = false;

        if (!empty($search)) {
            if (!$hasWhere) {
                $query .= " WHERE ";
                $hasWhere = true;
            } else {
                $query .= " AND ";
            }
            $query .= "corsi.nome LIKE ?";
            $params[] = "%" . $search . "%";
            $types .= "s";
        }

        if (!empty($ssd)) {
            if (!$hasWhere) {
                $query .= " WHERE ";
                $hasWhere = true;
            } else {
                $query .= " AND ";
            }
            $query .= "ssd.nome = ?";
            $params[] = $ssd;
            $types .= "s";
        }

        if ($idutente && $filterType === 'followed') {
            if (!$hasWhere) {
                $query .= " WHERE ";
                $hasWhere = true;
            } else {
                $query .= " AND ";
            }
            $query .= "corsi.idcorso IN (SELECT idcorso FROM iscrizioni WHERE idutente = ?)";
            $params[] = $idutente;
            $types .= "i";
        }

        if ($idutente && $filterType === 'not_followed') {
            if (!$hasWhere) {
                $query .= " WHERE ";
                $hasWhere = true;
            } else {
                $query .= " AND ";
            }
            $query .= "corsi.idcorso NOT IN (SELECT idcorso FROM iscrizioni WHERE idutente = ?)";
            $params[] = $idutente;
            $types .= "i";
        }

        $stmt = $this->db->prepare($query);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getHomeArticles($idutente = null, $orderBy = 'data_pubblicazione', $limit = 6)
    {
        $query = "SELECT articoli.*, utenti.username AS autore, 
                ROUND(AVG(recensioni.valutazione), 1) AS media_recensioni
                FROM articoli
                JOIN utenti ON articoli.idutente = utenti.idutente
                LEFT JOIN recensioni ON articoli.idarticolo = recensioni.idarticolo";
        
        $params = [];
        $types = "";

        if ($idutente !== null) {
            $query .= " JOIN iscrizioni ON articoli.idcorso = iscrizioni.idcorso AND iscrizioni.idutente = ?";
            $params[] = $idutente;
            $types .= "i";
        }

        $query .= " WHERE articoli.approvato = TRUE";

        $query .= " GROUP BY articoli.idarticolo";

        if ($orderBy === 'numero_visualizzazioni') {
            $query .= " ORDER BY articoli.numero_visualizzazioni DESC, articoli.data_pubblicazione DESC";
        } else {
            $query .= " ORDER BY articoli.data_pubblicazione DESC";
        }

        $query .= " LIMIT ?";
        $params[] = $limit;
        $types .= "i";

        $stmt = $this->db->prepare($query);
        if (!empty($params)) {
             $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getApprovedArticlesByCourse($idcorso)
    {
        $query = "SELECT articoli.*, utenti.username AS autore, ROUND(AVG(recensioni.valutazione), 1) AS media_recensioni
            FROM articoli
            JOIN utenti ON articoli.idutente = utenti.idutente
            LEFT JOIN recensioni ON articoli.idarticolo = recensioni.idarticolo
            WHERE articoli.idcorso = ?
            AND articoli.approvato = true
            GROUP BY articoli.idarticolo
            ORDER BY data_pubblicazione DESC, media_recensioni DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idcorso);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getApprovedArticles()
    {
        $query = "SELECT articoli.*,  utenti.username AS autore,
            ROUND(AVG(recensioni.valutazione), 1) AS media_recensioni,
            COUNT(recensioni.idrecensione) AS numero_recensioni
        FROM articoli
        JOIN utenti ON articoli.idutente = utenti.idutente
        LEFT JOIN recensioni ON articoli.idarticolo = recensioni.idarticolo
        WHERE articoli.approvato = TRUE
        GROUP BY articoli.idarticolo
        ORDER BY articoli.data_pubblicazione DESC, media_recensioni DESC
    ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getArticles()
    {
        $query = "SELECT articoli.*, ROUND(AVG(recensioni.valutazione), 1) AS media_recensioni
            FROM articoli
            LEFT JOIN recensioni ON articoli.idarticolo = recensioni.idarticolo
            GROUP BY articoli.idarticolo
            ORDER BY data_pubblicazione DESC, media_recensioni DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getArticleById($idarticolo)
    {
        $query = "SELECT articoli.*, utenti.username AS autore, ROUND(AVG(recensioni.valutazione), 1) AS media_recensioni
            FROM articoli
            JOIN utenti ON articoli.idutente = utenti.idutente
            LEFT JOIN recensioni ON articoli.idarticolo = recensioni.idarticolo
            WHERE articoli.idarticolo = ?
            GROUP BY articoli.idarticolo
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idarticolo);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function incrementArticleViews($idarticolo)
    {
        $stmt = $this->db->prepare("UPDATE articoli SET numero_visualizzazioni = numero_visualizzazioni + 1 WHERE idarticolo = ?");
        $stmt->bind_param("i", $idarticolo);
        $stmt->execute();
    }

    public function followCourse($idutente, $idcorso)
    {
        $stmt = $this->db->prepare("INSERT INTO iscrizioni (idutente, idcorso) VALUES (?, ?)");
        $stmt->bind_param("ii", $idutente, $idcorso);
        $stmt->execute();
    }

    public function unfollowCourse($idutente, $idcorso)
    {
        $stmt = $this->db->prepare("DELETE FROM iscrizioni WHERE idutente = ? AND idcorso = ?");
        $stmt->bind_param("ii", $idutente, $idcorso);
        $stmt->execute();
    }

    public function isFollowingCourse($idutente, $idcorso)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM iscrizioni WHERE idutente = ? AND idcorso = ?");
        $stmt->bind_param("ii", $idutente, $idcorso);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    public function createArticle($idcorso, $titolo, $contenuto, $idutente)
    {
        $stmt = $this->db->prepare("INSERT INTO articoli (idcorso, titolo, contenuto, idutente, data_pubblicazione, approvato, numero_visualizzazioni) VALUES (?, ?, ?, ?, NOW(), false, 0)");
        $stmt->bind_param("issi", $idcorso, $titolo, $contenuto, $idutente);
        $stmt->execute();
    }

    public function getCourses()
    {
        $query = "SELECT * FROM corsi";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getArticlesToApprove()
    {
        $query = "SELECT articoli.*, utenti.username AS autore, corsi.nome AS nome_corso
            FROM articoli
            JOIN utenti ON articoli.idutente = utenti.idutente
            JOIN corsi ON articoli.idcorso = corsi.idcorso
            WHERE articoli.approvato = FALSE
            ORDER BY data_pubblicazione DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function approveArticle($idarticolo)
    {
        $stmt = $this->db->prepare("UPDATE articoli SET approvato = true WHERE idarticolo = ?");
        $stmt->bind_param("i", $idarticolo);
        return $stmt->execute();
    }

    public function deleteArticle($idarticolo)
    {
        $stmt = $this->db->prepare("DELETE FROM articoli WHERE idarticolo = ?");
        $stmt->bind_param("i", $idarticolo);
        return $stmt->execute();
    }

    public function addReview($idarticolo, $idutente, $valutazione)
    {
        $stmt = $this->db->prepare("INSERT INTO recensioni (idarticolo, idutente, valutazione) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $idarticolo, $idutente, $valutazione);
        return $stmt->execute();
    }

    public function getReviewsByArticle($idarticolo)
    {
        $query = "SELECT recensioni.*, utenti.username 
                  FROM recensioni 
                  JOIN utenti ON recensioni.idutente = utenti.idutente 
                  WHERE idarticolo = ? 
                  ORDER BY idrecensione DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idarticolo);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function hasUserReviewed($idarticolo, $idutente)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM recensioni WHERE idarticolo = ? AND idutente = ?");
        $stmt->bind_param("ii", $idarticolo, $idutente);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    public function getApprovedArticlesByCourseWithFilters($idcorso, $sort, $order)
    {
        $allowedSort = ['data_pubblicazione', 'media_recensioni', 'numero_visualizzazioni'];
        $allowedOrder = ['ASC', 'DESC'];

        $sort = in_array($sort, $allowedSort) ? $sort : 'data_pubblicazione';
        $order = in_array($order, $allowedOrder) ? $order : 'DESC';

        $query = "SELECT articoli.*, utenti.username AS autore, ROUND(AVG(recensioni.valutazione), 1) AS media_recensioni
              FROM articoli
              JOIN utenti ON articoli.idutente = utenti.idutente
              LEFT JOIN recensioni ON articoli.idarticolo = recensioni.idarticolo
              WHERE articoli.idcorso = ? AND articoli.approvato = true
              GROUP BY articoli.idarticolo
              ORDER BY $sort $order";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idcorso);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

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

    public function createCourse($nome, $descrizione, $idssd) {
        $stmt = $this->db->prepare("INSERT INTO corsi (nome, descrizione, idssd) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nome, $descrizione, $idssd);
        return $stmt->execute();
    }

    public function updateCourse($idcorso, $nome, $descrizione, $idssd) {
        $stmt = $this->db->prepare("UPDATE corsi SET nome = ?, descrizione = ?, idssd = ? WHERE idcorso = ?");
        $stmt->bind_param("ssii", $nome, $descrizione, $idssd, $idcorso);
        return $stmt->execute();
    }

    public function deleteCourse($idcorso) {
        $stmt = $this->db->prepare("DELETE FROM corsi WHERE idcorso = ?");
        $stmt->bind_param("i", $idcorso);
        return $stmt->execute();
    }

    public function createSSD($nome, $descrizione) {
        $stmt = $this->db->prepare("INSERT INTO ssd (nome, descrizione) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $descrizione);
        return $stmt->execute();
    }

    public function updateSSD($idssd, $nome, $descrizione) {
        $stmt = $this->db->prepare("UPDATE ssd SET nome = ?, descrizione = ? WHERE idssd = ?");
        $stmt->bind_param("ssi", $nome, $descrizione, $idssd);
        return $stmt->execute();
    }

    public function deleteSSD($idssd) {
        $stmt = $this->db->prepare("DELETE FROM ssd WHERE idssd = ?");
        $stmt->bind_param("i", $idssd);
        return $stmt->execute();
    }

    public function getSSDById($idssd) {
        $stmt = $this->db->prepare("SELECT * FROM ssd WHERE idssd = ?");
        $stmt->bind_param("i", $idssd);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAllUsers($search = null) {
        $query = "SELECT idutente, username, isAdmin FROM utenti";
        $params = [];
        $types = "";

        if (!empty($search)) {
            $query .= " WHERE username LIKE ?";
            $params[] = "%" . $search . "%";
            $types .= "s";
        }

        $stmt = $this->db->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserById($idutente) {
        $stmt = $this->db->prepare("SELECT idutente, username, isAdmin FROM utenti WHERE idutente = ?");
        $stmt->bind_param("i", $idutente);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function createUser($username, $password, $ruolo) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO utenti (username, password, isAdmin) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $username, $hash, $ruolo);
        return $stmt->execute();
    }

    public function updateUser($idutente, $username, $ruolo, $password = null) {
        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE utenti SET username = ?, isAdmin = ?, password = ? WHERE idutente = ?");
            $stmt->bind_param("sisi", $username, $ruolo, $hash, $idutente);
        } else {
            $stmt = $this->db->prepare("UPDATE utenti SET username = ?, isAdmin = ? WHERE idutente = ?");
            $stmt->bind_param("sii", $username, $ruolo, $idutente);
        }
        return $stmt->execute();
    }

    public function deleteUser($idutente) {
        $stmt = $this->db->prepare("DELETE FROM utenti WHERE idutente = ?");
        $stmt->bind_param("i", $idutente);
        return $stmt->execute();
    }
}
