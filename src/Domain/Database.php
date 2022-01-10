
<?php
class Database
{

    private ?PDO $connection;
    private string $dbname;
    private string $username;
    private string $password;
    private string $host;
    private string $port;

    public function __construct(string $dbname = 'mzn-db', string $username = 'root', string $password = 'MSK2199-', string $host = 'localhost', string $port = '3306')
    {
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
        $this->host = $host;
        $this->port = $port;
    }

    public function getConnection(): PDO
    {
        if (!isset($this->connection)) {
            $dsn = "mysql:dbname=$this->dbname;host=$this->host;port=$this->port";
            $options = [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_FOUND_ROWS => true,
            ];

            try {
                $this->connection = new PDO($dsn, $this->username, $this->password, $options);
            } catch (PDOException $e) {
                throw $e;
            }
        }

        return $this->connection;
    }

    public function closeConnection(): void
    {
        $this->connection = null;
    }
}
?>