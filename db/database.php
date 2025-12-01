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
        // Assuming $userId is available in the context where this method is called
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
}
?>