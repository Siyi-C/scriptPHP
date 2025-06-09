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
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(100) NOT NULL,
                `surname` VARCHAR(100) NOT NULL,
                `email` VARCHAR(100) UNIQUE
            )
        ";

        if ($this->dryRun) {
             echo "[DRY RUN] There is no actual execution. It would execute:\n$sql\n";
        } else {
              $this->db->exec($sql);
              echo "Users table created successfully.\n";
        }

    }

    //read csv
    public function readCsv(string $filename): array
    {
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

            // remove space
            $header = array_map('trim', $header);
            
            while(($row = fgetcsv($handle)) !== false) {
                $row = array_map('trim', $row);
                $data = array_combine($header, $row);

           $parsed = $this->parseCsv($data);
            if ($parsed === null) {
                $skippedRows++;
                continue;
            }

            $rows[] = $parsed;
        }

            fclose($handle);

        } else {
             throw new RuntimeException("File $filename could not be opened.\n");
        }

        return $rows;
    }

    //insert data to db
    public function insert(array $data): void
    {
       $prepareData = $this->db->prepare("
            INSERT INTO users (name, surname, email) 
            VALUES (:name, :surname, :email)
        ");

        if ($this->dryRun) {
            $messages = [];
            foreach ($data as $row) {
                $messages[] = json_encode($row);
        }
        echo "[DRY RUN] Would insert " . count($data) . " rows:\n" . implode("\n", $messages) . "\n";
        return;
    }

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
    }

    private function parseCsv(array $data): ?array
    {
        // Check for required fields
        if (!isset($data['name'], $data['surname'], $data['email'])) {
            echo "Missing name, surname, or email: " . implode(',', $data) . "\n";
            return null;
        }

        // Clean and format
        $name = ucfirst(strtolower($data['name']));
        $surname = ucfirst(strtolower($data['surname']));
        $email = strtolower($data['email']);

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email: " . $email . ". This user will not save to database.\n";
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