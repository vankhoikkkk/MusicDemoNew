<?php

use Dba\Connection;
class ArtistDAO {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;

    }
    public function getArtistById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM casi WHERE id_casi = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getAlbumsByArtistId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM albumcasi WHERE id_casi = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getSongsByArtistId($id) {
    $stmt = $this->conn->prepare("SELECT baihat.*, album 
                                FROM baihat 
                                LEFT JOIN albumcasi ON albumcasi.id_casi = baihat.id_casi
                                WHERE baihat.id_casi = ?
                                GROUP BY baihat.id_baihat");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

    public function getAllArtists() {
        $query = "SELECT * FROM casi";
        $result = $this->conn->query($query);
        return $result;
    }

    public function getArtistsWithPagination($offset, $limit) {
        $query = "
            SELECT albumcasi.id_casi, tenCaSi, MIN(linkAnh) as linkAnh 
            FROM albumcasi 
            JOIN casi ON casi.id_casi = albumcasi.id_casi 
            GROUP BY albumcasi.id_casi, tenCaSi 
            LIMIT ?, ?
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function getTotalArtists() {
        $query = "SELECT COUNT(DISTINCT id_casi) AS total FROM albumcasi";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
?>
