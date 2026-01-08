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
        $query = "SELECT appunti.*, utenti.username AS autore, corsi.nome AS nome_corso,
                ROUND(AVG(recensioni.valutazione), 1) AS media_recensioni
                FROM appunti
                JOIN utenti ON appunti.idutente = utenti.idutente
                JOIN corsi ON appunti.idcorso = corsi.idcorso
                LEFT JOIN recensioni ON appunti.idappunto = recensioni.idappunto";
        
        $params = [];
        $types = "";

        if ($idutente !== null) {
            $query .= " JOIN iscrizioni ON appunti.idcorso = iscrizioni.idcorso AND iscrizioni.idutente = ?";
            $params[] = $idutente;
            $types .= "i";
        }

        $query .= " WHERE appunti.approvato = TRUE";

        $query .= " GROUP BY appunti.idappunto";

        if ($orderBy === 'numero_visualizzazioni') {
            $query .= " ORDER BY appunti.numero_visualizzazioni DESC, appunti.data_pubblicazione DESC";
        } else {
            $query .= " ORDER BY appunti.data_pubblicazione DESC";
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
        $query = "SELECT appunti.*, utenti.username AS autore, ROUND(AVG(recensioni.valutazione), 1) AS media_recensioni
            FROM appunti
            JOIN utenti ON appunti.idutente = utenti.idutente
            LEFT JOIN recensioni ON appunti.idappunto = recensioni.idappunto
            WHERE appunti.idcorso = ?
            AND appunti.approvato = true
            GROUP BY appunti.idappunto
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
        $query = "SELECT appunti.*,  utenti.username AS autore,
            ROUND(AVG(recensioni.valutazione), 1) AS media_recensioni,
            COUNT(recensioni.idrecensione) AS numero_recensioni
        FROM appunti
        JOIN utenti ON appunti.idutente = utenti.idutente
        LEFT JOIN recensioni ON appunti.idappunto = recensioni.idappunto
        WHERE appunti.approvato = TRUE
        GROUP BY appunti.idappunto
        ORDER BY appunti.data_pubblicazione DESC, media_recensioni DESC
    ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAdminApprovedArticles($search = null, $orderBy = 'data_pubblicazione', $order = 'DESC')
    {
        $query = "SELECT appunti.*, utenti.username AS autore, corsi.nome AS nome_corso,
            ROUND(AVG(recensioni.valutazione), 1) AS media_recensioni,
            COUNT(recensioni.idrecensione) AS numero_recensioni
        FROM appunti
        JOIN utenti ON appunti.idutente = utenti.idutente
        JOIN corsi ON appunti.idcorso = corsi.idcorso
        LEFT JOIN recensioni ON appunti.idappunto = recensioni.idappunto
        WHERE appunti.approvato = TRUE";

        $params = [];
        $types = "";

        if (!empty($search)) {
            $query .= " AND (appunti.titolo LIKE ? OR utenti.username LIKE ? OR corsi.nome LIKE ?)";
            $searchTerm = "%" . $search . "%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= "sss";
        }

        $query .= " GROUP BY appunti.idappunto";

        $allowedColumns = ['data_pubblicazione', 'media_recensioni', 'numero_visualizzazioni'];
        if (!in_array($orderBy, $allowedColumns)) {
            $orderBy = 'data_pubblicazione';
        }

        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
        $query .= " ORDER BY $orderBy $order";
        
        $stmt = $this->db->prepare($query);
        if (!empty($params)) {
             $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getArticles()
    {
        $query = "SELECT appunti.*, ROUND(AVG(recensioni.valutazione), 1) AS media_recensioni
            FROM appunti
            LEFT JOIN recensioni ON appunti.idappunto = recensioni.idappunto
            GROUP BY appunti.idappunto
            ORDER BY data_pubblicazione DESC, media_recensioni DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getArticleById($idappunto)
    {
        $query = "SELECT appunti.*, utenti.username AS autore, corsi.nome AS nome_corso,
            ROUND(AVG(recensioni.valutazione), 1) AS media_recensioni
            FROM appunti
            JOIN utenti ON appunti.idutente = utenti.idutente
            JOIN corsi ON appunti.idcorso = corsi.idcorso
            LEFT JOIN recensioni ON appunti.idappunto = recensioni.idappunto
            WHERE appunti.idappunto = ?
            GROUP BY appunti.idappunto
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idappunto);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function incrementArticleViews($idappunto)
    {
        $stmt = $this->db->prepare("UPDATE appunti SET numero_visualizzazioni = numero_visualizzazioni + 1 WHERE idappunto = ?");
        $stmt->bind_param("i", $idappunto);
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

    public function hasFollowedCourses($idutente)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM iscrizioni WHERE idutente = ?");
        $stmt->bind_param("i", $idutente);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    public function createArticle($idcorso, $titolo, $contenuto, $idutente)
    {
        $stmt = $this->db->prepare("INSERT INTO appunti (idcorso, titolo, contenuto, idutente, data_pubblicazione, approvato, numero_visualizzazioni) VALUES (?, ?, ?, ?, NOW(), false, 0)");
        $stmt->bind_param("issi", $idcorso, $titolo, $contenuto, $idutente);
        $stmt->execute();
    }

    public function updateArticle($idappunto, $idcorso, $titolo, $contenuto)
    {
        $stmt = $this->db->prepare("UPDATE appunti SET idcorso = ?, titolo = ?, contenuto = ?, motivo_rifiuto = NULL, approvato = FALSE, data_pubblicazione = NOW() WHERE idappunto = ?");
        $stmt->bind_param("issi", $idcorso, $titolo, $contenuto, $idappunto);
        return $stmt->execute();
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
        $query = "SELECT appunti.*, utenti.username AS autore, corsi.nome AS nome_corso
            FROM appunti
            JOIN utenti ON appunti.idutente = utenti.idutente
            JOIN corsi ON appunti.idcorso = corsi.idcorso
            WHERE appunti.approvato = FALSE AND appunti.motivo_rifiuto IS NULL
            ORDER BY data_pubblicazione DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function approveArticle($idappunto)
    {
        $stmt = $this->db->prepare("UPDATE appunti SET approvato = true WHERE idappunto = ?");
        $stmt->bind_param("i", $idappunto);
        return $stmt->execute();
    }

    public function rejectArticle($idappunto, $motivo)
    {
        $stmt = $this->db->prepare("UPDATE appunti SET motivo_rifiuto = ? WHERE idappunto = ?");
        $stmt->bind_param("si", $motivo, $idappunto);
        return $stmt->execute();
    }

    public function deleteArticle($idappunto)
    {
        $stmt = $this->db->prepare("DELETE FROM appunti WHERE idappunto = ?");
        $stmt->bind_param("i", $idappunto);
        return $stmt->execute();
    }

    public function addReview($idappunto, $idutente, $valutazione)
    {
        $stmt = $this->db->prepare("INSERT INTO recensioni (idappunto, idutente, valutazione) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $idappunto, $idutente, $valutazione);
        return $stmt->execute();
    }

    public function deleteReview($idrecensione, $idutente)
    {
        $stmt = $this->db->prepare("DELETE FROM recensioni WHERE idrecensione = ? AND idutente = ?");
        $stmt->bind_param("ii", $idrecensione, $idutente);
        return $stmt->execute();
    }

    public function getReviewsByArticle($idappunto)
    {
        $query = "SELECT recensioni.*, utenti.username 
                  FROM recensioni 
                  JOIN utenti ON recensioni.idutente = utenti.idutente 
                  WHERE idappunto = ? 
                  ORDER BY idrecensione DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idappunto);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function hasUserReviewed($idappunto, $idutente)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM recensioni WHERE idappunto = ? AND idutente = ?");
        $stmt->bind_param("ii", $idappunto, $idutente);
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

        $query = "SELECT appunti.*, utenti.username AS autore, ROUND(AVG(recensioni.valutazione), 1) AS media_recensioni
              FROM appunti
              JOIN utenti ON appunti.idutente = utenti.idutente
              LEFT JOIN recensioni ON appunti.idappunto = recensioni.idappunto
              WHERE appunti.idcorso = ? AND appunti.approvato = true
              GROUP BY appunti.idappunto
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

    public function getAdminCount() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM utenti WHERE isAdmin = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    public function deleteUser($idutente) {
        $stmt = $this->db->prepare("DELETE FROM utenti WHERE idutente = ?");
        $stmt->bind_param("i", $idutente);
        return $stmt->execute();
    }

    public function getFollowedCoursesCount($idutente) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM iscrizioni WHERE idutente = ?");
        $stmt->bind_param("i", $idutente);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }

    public function getArticlesCountByAuthor($idutente, $onlyApproved = false) {
        $query = "SELECT COUNT(*) as count FROM appunti WHERE idutente = ?";
        if ($onlyApproved) {
            $query .= " AND approvato = TRUE";
        }
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idutente);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }

    public function getAuthorAverageRating($idutente) {
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

    public function getArticlesByAuthor($idutente, $onlyApproved = false) {
        $query = "SELECT appunti.*, corsi.nome AS nome_corso, 
                  ROUND(AVG(recensioni.valutazione), 1) AS media_recensioni
                  FROM appunti
                  JOIN corsi ON appunti.idcorso = corsi.idcorso
                  LEFT JOIN recensioni ON appunti.idappunto = recensioni.idappunto
                  WHERE appunti.idutente = ?";
        
        if ($onlyApproved) {
            $query .= " AND appunti.approvato = TRUE";
        }

        $query .= " GROUP BY appunti.idappunto
                  ORDER BY appunti.data_pubblicazione DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idutente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getUnapprovedArticlesByAuthor($idutente)
    {
        $query = "SELECT appunti.*, corsi.nome AS nome_corso
                  FROM appunti
                  JOIN corsi ON appunti.idcorso = corsi.idcorso
                  WHERE appunti.idutente = ? AND appunti.approvato = FALSE
                  ORDER BY appunti.data_pubblicazione DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idutente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getApprovedArticlesByUserIdWithFilters($idutente, $sort, $order)
    {
        $allowedSort = ['data_pubblicazione', 'media_recensioni', 'numero_visualizzazioni'];
        $allowedOrder = ['ASC', 'DESC'];

        $sort = in_array($sort, $allowedSort) ? $sort : 'data_pubblicazione';
        $order = in_array($order, $allowedOrder) ? $order : 'DESC';

        $query = "SELECT appunti.*, corsi.nome AS nome_corso,
              ROUND(AVG(recensioni.valutazione), 1) AS media_recensioni
              FROM appunti
              JOIN corsi ON appunti.idcorso = corsi.idcorso
              LEFT JOIN recensioni ON appunti.idappunto = recensioni.idappunto
              WHERE appunti.idutente = ? AND appunti.approvato = true
              GROUP BY appunti.idappunto
              ORDER BY $sort $order";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idutente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
