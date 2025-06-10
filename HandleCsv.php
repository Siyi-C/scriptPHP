<?php

class HandleCsv {

    private PDO $db;
    private bool $dryRun;

    public function __construct(PDO $database, $dryRun = false)
    {
        $this->db = $database;
        $this->dryRun = $dryRun;
    }

    // create table
    public function createTable(): void
    {
        $dropTable = "DROP TABLE IF EXISTS users";
        $createTable = "
            CREATE TABLE IF NOT EXISTS users (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(100) NOT NULL,
                `surname` VARCHAR(100) NOT NULL,
                `email` VARCHAR(100) UNIQUE
            )
        ";

        if ($this->dryRun) {
             echo "[DRY RUN] There is no actual execution. Output:\n$createTable\n";
        } else {
              $this->db->exec($dropTable);
              $this->db->exec($createTable);
              echo "Users table created successfully.\n";
        }

    }

    // read csv
    public function importCsv(string $filename): void
    {
        // check user table exists
        $countTable = $this->db->query("SHOW TABLES LIKE 'users'")->fetchColumn();
        
        if ($countTable === false) {
            echo "The user table does not exist. Please create the table.\n";
            return;
        }

        if (!file_exists($filename)) {
            throw new InvalidArgumentException("File $filename does not exist.\n");
        }

        $rows = [];
        $skippedRows = 0;

        if (($handle = fopen($filename, 'r')) !== false) {
            $header = fgetcsv($handle);
            if ($header === false) {
                 throw new RuntimeException("CSV header could not be read.\n");
            }
            
            //remove space
            $header = array_map('trim', $header);

            while(($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row);
                $parsed = $this->parseCsv($data);

                if (!$parsed) {
                    $skippedRows++;
                    continue;
                }

                $rows[] = $parsed;
            }
            fclose($handle);

        } else {
             throw new RuntimeException("File $filename could not be opened.\n");
        }

        if ($this->dryRun) {
            echo "[DRY RUN] There is no actual execution. Output:\n" . json_encode($rows) ."\n";
        } else {
            $this->insert($rows);
        }

    }

    // insert data to db
    public function insert(array $data): void
    {

       $prepareData = $this->db->prepare("
            INSERT INTO users (name, surname, email) 
            VALUES (:name, :surname, :email)
        ");

        foreach($data as $row) { 
            // check email exits
            if ($this->emailExists($row['email'])) {
                echo "Found duplicate email:" . $row['email'] . "\n";
                continue; 
            }

            try {
                $prepareData->execute([
                ':name' => $row['name'],
                ':surname' => $row['surname'],
                ':email' => $row['email'],
            ]);
        
            } catch (PDOException $e) {
                echo "Error insert data: " . $e->getMessage() . "\n";
            }
        }

        echo "Added user(s) successfully.\n";
    }

    private function parseCsv(array $data): ?array
    {

        // check for required fields
        if (!isset($data['name'], $data['surname'], $data['email'])) {
            echo "Missing name, surname, or email: " . implode(',', $data) . "\n";
            return null;
        }

        // clean and format
        $name = ucfirst(strtolower(trim($data['name'])));
        $surname = ucfirst(strtolower(trim($data['surname'])));
        $email = strtolower(trim($data['email']));

        // validate name 
        if (!preg_match('/^[a-zA-Z\' -]+$/', $name) || !preg_match('/^[a-zA-Z\' -]+$/', $surname) ) {
            if (!$this->dryRun) {
                echo "Found Invalid name: " . $name . " " . $surname . ". This user will not save to database.\n";
            }
            return null;
        }

        // validate email
         if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (!$this->dryRun) {
                echo "Found Invalid email: " . $email . ". This user will not save to database.\n";
            }
            return null;
        }

        return [
            'name' => $name,
            'surname' => $surname,
            'email' => $email
        ];
    }   

    private function emailExists(string $email): bool
    {
        $check = $this->db->prepare("
            SELECT COUNT(*) FROM users where email = :email
        ");
        $check->execute([':email' => $email]);
        return $check->fetchColumn() > 0;
    }

}