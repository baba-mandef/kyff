<?php

/**
 * Classe Model optimisée pour apprendre la gestion CRUD
 * Cette version gère automatiquement les placeholders pour simplifier les requêtes.
 */
class Model
{
    /**
     * Établit une connexion à la base de données.
     * @return PDO Un objet de connexion à la base de données.
     */
    private function conn()
    {
        require 'app/config.php';
        try {
            return new PDO('mysql:host=' . $DB_HOST . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    /**
     * Insère un enregistrement dans une table.
     * @param string $table Nom de la table.
     * @param array $data Tableau associatif (champ => valeur).
     * @return bool Succès ou échec de l'opération.
     */
    public function add($table, $data)
    {
        $db = $this->conn();
        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $query = $db->prepare("INSERT INTO $table ($fields) VALUES ($placeholders)");
        return $query->execute($data);
    }

    /**
     * Récupère des enregistrements dans une table.
     * @param string $table Nom de la table.
     * @param array $conditions (optionnel) Tableau associatif des conditions (champ => valeur).
     * @return array Liste des résultats.
     */
    public function read($table, $conditions = [])
    {
        $db = $this->conn();
        $whereClause = $this->buildWhereClause($conditions);
        $query = $db->prepare("SELECT * FROM $table $whereClause");
        $query->execute($conditions);
        return $query->fetchAll();
    }

    /**
     * Met à jour des enregistrements dans une table.
     * @param string $table Nom de la table.
     * @param array $data Données à mettre à jour (champ => valeur).
     * @param array $conditions Conditions pour identifier les enregistrements (champ => valeur).
     * @return bool Succès ou échec de l'opération.
     */
    public function update($table, $data, $conditions)
    {
        $db = $this->conn();
        $setClause = implode(', ', array_map(fn($key) => "$key = :set_$key", array_keys($data)));
        $whereClause = $this->buildWhereClause($conditions);

        $query = $db->prepare("UPDATE $table SET $setClause $whereClause");
        foreach ($data as $key => $value) {
            $data["set_$key"] = $value; // Préfixe pour différencier les données des conditions
            unset($data[$key]);
        }
        return $query->execute(array_merge($data, $conditions));
    }

    /**
     * Supprime des enregistrements dans une table.
     * @param string $table Nom de la table.
     * @param array $conditions Conditions pour identifier les enregistrements (champ => valeur).
     * @return bool Succès ou échec de l'opération.
     */
    public function delete($table, $conditions)
    {
        $db = $this->conn();
        $whereClause = $this->buildWhereClause($conditions);

        $query = $db->prepare("DELETE FROM $table $whereClause");
        return $query->execute($conditions);
    }

    /**
     * Construit une clause WHERE pour les requêtes SQL.
     * @param array $conditions Tableau associatif des conditions (champ => valeur).
     * @return string Clause WHERE (vide si pas de conditions).
     */
    private function buildWhereClause($conditions)
    {
        if (empty($conditions)) {
            return '';
        }
        $clauses = array_map(fn($key) => "$key = :$key", array_keys($conditions));
        return 'WHERE ' . implode(' AND ', $clauses);
    }
}
