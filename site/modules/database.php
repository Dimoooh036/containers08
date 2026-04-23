<?php

class Database {
    private $pdo;

    public function __construct($path) {
        $this->pdo = new PDO("sqlite:" . $path);
    }

    public function Execute($sql) {
        return $this->pdo->exec($sql);
    }

    public function Fetch($sql) {
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function Create($table, $data) {
        $keys = implode(',', array_keys($data));
        $values = implode(',', array_map(fn($v) => "'$v'", array_values($data)));

        $this->Execute("INSERT INTO $table ($keys) VALUES ($values)");
        return $this->pdo->lastInsertId();
    }

    public function Read($table, $id) {
        $result = $this->Fetch("SELECT * FROM $table WHERE id = $id");
        return $result[0] ?? null;
    }

    public function Update($table, $id, $data) {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key='$value'";
        }
        $set = implode(',', $set);

        return $this->Execute("UPDATE $table SET $set WHERE id = $id");
    }

    public function Delete($table, $id) {
        return $this->Execute("DELETE FROM $table WHERE id = $id");
    }

    public function Count($table) {
        $result = $this->Fetch("SELECT COUNT(*) as count FROM $table");
        return $result[0]['count'];
    }
}
