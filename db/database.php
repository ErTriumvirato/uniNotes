<?php
class DatabaseHelper
{
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port){
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }        
    }

    public function checkLogin($username, $password)
    {
        $query = "SELECT idautore, username, nome FROM autore WHERE attivo=1 AND username = ? AND password = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCoursesWithSSD(){       
        $query = "SELECT corsi.nome AS nomeCorso, ssd.nome AS nomeSSD, corsi.descrizione AS descrizioneCorso
        FROM corsi JOIN ssd ON corsi.idssd = ssd.idssd";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getFollowedCoursesWithSSD($userId){
        $query = "SELECT corsi.nome AS nomeCorso, ssd.nome AS nomeSSD, corsi.descrizione AS descrizioneCorso
        FROM corsi 
        JOIN ssd ON corsi.idssd = ssd.idssd
        JOIN iscrizioni ON corsi.idcorso = iscrizioni.idcorso
        WHERE iscrizioni.idutente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getArticlesByDate($idcorso){
        $query = "SELECT * FROM articoli WHERE idcorso = ? ORDER BY data_pubblicazione DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idcorso);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getArticlesByNumberOfViews(){
        $query = "SELECT * FROM articoli ORDER BY numero_visualizzazioni DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getArticlesByReviews(){
        $query = "SELECT articoli.*, AVG(recensioni.valutazione) AS media_valutazioni
        FROM articoli
        LEFT JOIN recensioni ON articoli.idarticolo = recensioni.idarticolo
        GROUP BY articoli.idarticolo
        ORDER BY media_valutazioni DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getUsersInfo(){
        $query = "SELECT * FROM utenti";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getReviewByArticle($idarticolo){
        $query = "SELECT * FROM recensioni WHERE idarticolo = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idarticolo);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUsersNumber(){
        $query = "SELECT COUNT(*) AS total FROM utenti";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'];
    }

    public function getCoursesNumber(){
        $query = "SELECT COUNT(*) AS total FROM corsi";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'];
    }

    public function getArticlesNumber(){
        $query = "SELECT COUNT(*) AS total FROM articoli";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'];
    }
}
?>