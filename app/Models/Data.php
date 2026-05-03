<?php
namespace App\Models;

class Data {
    private string $host;
    private string $name;
    private string $user;
    private string $pass;

    public function __construct()
    {
        $this->host = 'db';
        $this->name = 'freelance_marketplace';
        $this->user = 'appuser';
        $this->pass = 'apppass';
    }

    public function getDb(): \mysqli
    {
        $conn = new \mysqli($this->host, $this->user, $this->pass, $this->name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }

    public function AddUser(string $email, string $pass, string $name, string $role): int
    {
        $conn     = $this->getDb();
        $password = password_hash($pass, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO userData (user_email, user_password, user_name, user_role) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssss", $email, $password, $name, $role);
        $stmt->execute();

        $new_id = $stmt->affected_rows > 0 ? $conn->insert_id : 0;

        $stmt->close();
        $conn->close();

        return $new_id;
    }

    public function checkEmail(string $email): bool
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare("SELECT id FROM userData WHERE user_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        $exists = $stmt->num_rows > 0;

        $stmt->close();
        $conn->close();

        return $exists;
    }

    public function checkPass(string $pass, string $email): ?array
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            "SELECT id, user_name, user_email, user_password, user_role FROM userData WHERE user_email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($pass, $user['user_password'])) {
                $stmt->close();
                $conn->close();
                return $user;
            }
        }

        $stmt->close();
        $conn->close();

        return null;
    }

    public function getUsers(): array
    {
        $conn   = $this->getDb();
        $result = $conn->query("SELECT id, user_name, user_email, user_role FROM userData");

        $users = $result->fetch_all(MYSQLI_ASSOC);

        $conn->close();
    
        return $users;
    }

    public function getRole(string $email): ?string
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare("SELECT user_role FROM userData WHERE user_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $role = null;
        if ($result->num_rows > 0) {
            $row  = $result->fetch_assoc();
            $role = $row['user_role'];
        }
        
        $stmt->close();
        $conn->close();

        return $role;
    }
    public function is_verified(string $email): bool
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare('SELECT is_verified FROM userData WHERE user_email = ?');
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $verified = false;

        if ($result->num_rows > 0) {
            $row      = $result->fetch_assoc();
            $verified = (bool) $row['is_verified'];
        }

        $stmt->close();
        $conn->close();

        return $verified;
    }
    public function getUserByEmail(string $email): ?array
{
    $conn = $this->getDb();
    $stmt = $conn->prepare(
        "SELECT id, user_name, user_email, user_role, is_verified, user_SSN
         FROM userData WHERE user_email = ?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $user = $result->num_rows > 0 ? $result->fetch_assoc() : null;

    $stmt->close();
    $conn->close();

    return $user;
}

public function getActiveProjectsCount(int $user_id, string $role): int
{
    $conn = $this->getDb();

    if ($role === 'Freelancer') {
        $stmt = $conn->prepare(
            "SELECT COUNT(*) as count
             FROM projects p
             JOIN specialistProfiles sp ON sp.id = p.specialist_id
             WHERE sp.user_id = ? AND p.is_done = 0"
        );
    } else {
        $stmt = $conn->prepare(
            "SELECT COUNT(*) as count
             FROM projects p
             JOIN clientProfile cp ON cp.id = p.client_id
             WHERE cp.user_id = ? AND p.is_done = 0"
        );
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $row   = $result->fetch_assoc();
    $count = (int) ($row['count'] ?? 0);

    $stmt->close();
    $conn->close();

    return $count;
}
}
