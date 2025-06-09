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

        if (($handle = fopen($filename, 'r')) !== false) {
            $header = fgetcsv($handle);
            if ($header === false) {
                 throw new RuntimeException("CSV header could not be read.\n");
            }
            
            while(($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row);
                $rows[] = $data;
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
    
    }

}