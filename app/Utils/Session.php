<?php

namespace App\Utils;

use PDO;
use SessionHandlerInterface;

class Session implements SessionHandlerInterface
{
    private $db;
    private $session;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    function open($save_path, $name)
    {
        $this->gc();
        return true;
    }

    public function close()
    {
        $this->gc();

        return true;
    }

    public function read($session_id)
    {
        // Read session data and return it
        $stmt = $this->db->prepare("SELECT * FROM sessions WHERE sid = :sid AND ip_address = :ip_address");
        $stmt->execute([
            'sid' => $session_id,
            'ip_address' => $_SERVER['REMOTE_ADDR']
        ]);

        $session = $stmt->fetch(PDO::FETCH_ASSOC);

        if (isset($session['session_data'])) {
            $this->session = $session;
            return $session['session_data'];
        } else {
            return '';
        }
    }

    public function write($session_id, $session_data)
    {
        // Write the session in the database
        $user_id = empty($_SESSION['user_id']) ? 0 : $_SESSION['user_id'];
        $ip_address = $_SERVER['REMOTE_ADDR'];

        if (!is_array($this->session)) {
            // If there is no session created

            if ($user_id != 0) {
                // If there is already a session
                $stmt = $this->db->prepare("SELECT sid FROM sessions WHERE user_id = :user_id");
                $stmt->execute(['user_id' => $user_id]);
                $num_rows = $stmt->rowCount();

                if ($num_rows != 0) {

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($row['session_id'] != $session_id) {

                        $stmt = $this->db->prepare("DELETE FROM sessions WHERE user_id = :user_id");
                        $stmt->execute(['user_id' => $user_id]);
                    }
                }
            }

            $stmt = $this->db->prepare("INSERT INTO sessions (sid, user_id, session_data, ip_address, created_at, updated_at)
                                        VALUES (:sid, :user_id, :session_data, :ip_address, CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)");
            $stmt->execute([
                'sid' => $session_id,
                'user_id' => $user_id,
                'session_data' => $session_data,
                'ip_address' => $ip_address
            ]);

        } else {
            // If the session exists in the database update session data and last_updated
            $stmt = $this->db->prepare("UPDATE sessions SET session_data = :session_data, updated_at = CURRENT_TIMESTAMP WHERE sid = :sid AND ip_address = :ip_address AND user_id = :user_id");
            $stmt->execute([
                'session_data' => $session_data,
                'sid' => $session_id,
                'ip_address' => $ip_address,
                'user_id' => $user_id
            ]);
        }

        return true;

    }

    public function destroy($session_id)
    {
        // Destroy the session
        $stmt = $this->db->prepare("SELECT user_id FROM sessions WHERE sid = :sid");
        $stmt->execute(['sid' => $session_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (is_array($row)) {
            $stmt = $this->db->prepare("DELETE FROM sessions WHERE sid = :sid");
            $stmt->execute(['sid' => $session_id]);
        }

        return true;
    }

    public function gc($maxlifetime = 86400) // One day
    {
        $stmt = $this->db->prepare("DELETE FROM sessions WHERE updated_at < CURRENT_TIMESTAMP - INTERVAL '$maxlifetime' SECOND");

        $stmt->execute();

        return true;
    }
}