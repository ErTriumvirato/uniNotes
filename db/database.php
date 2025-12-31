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

    public function getCoursesWithSSD()
    {
        $query = "SELECT corsi.idcorso, corsi.nome AS nomeCorso, ssd.nome AS nomeSSD, corsi.descrizione AS descrizioneCorso
        FROM corsi JOIN ssd ON corsi.idssd = ssd.idssd";
        $stmt = $this->db->prepare($query);
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


    public function getUnapprovedArticles()
    {
        $stmt = $this->db->prepare("SELECT * FROM articoli WHERE approvato = false");
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

    public function getUsersNumber()
    {
        $query = "SELECT COUNT(*) AS total FROM utenti";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'];
    }

    public function getCoursesNumber()
    {
        $query = "SELECT COUNT(*) AS total FROM corsi";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'];
    }

    public function getArticlesNumber()
    {
        $query = "SELECT COUNT(*) AS total FROM articoli";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'];
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
        $query = "SELECT articoli.*, utenti.username AS autore
            FROM articoli
            JOIN utenti ON articoli.idutente = utenti.idutente
            WHERE articoli.approvato = FALSE
            ORDER BY data_pubblicazione DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
