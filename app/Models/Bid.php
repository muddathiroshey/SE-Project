<?php
namespace App\Models;

class Bid extends Data
{
    public int $job_id;
    public int $user_id;
    public string $proposal_message;
    public string $key_differentiators;
    public string $relevant_work;
    public float $total_bid_amount;
    public string $bid_rationale;
    public ?string $start_date;
    public int $free_reviews;
    public float $review_price;
    public string $availability_slots;

    public function save(Bid $bidObject): int
    {
        $conn = $this->getDb();

        $sql = "INSERT INTO bids (
                    job_id,
                    user_id,
                    proposal_message,
                    key_differentiators,
                    relevant_work,
                    total_bid_amount,
                    bid_rationale,
                    start_date,
                    free_reviews,
                    review_price,
                    availability_slots
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param(
            "iisssdssids",
            $bidObject->job_id,
            $bidObject->user_id,
            $bidObject->proposal_message,
            $bidObject->key_differentiators,
            $bidObject->relevant_work,
            $bidObject->total_bid_amount,
            $bidObject->bid_rationale,
            $bidObject->start_date,
            $bidObject->free_reviews,
            $bidObject->review_price,
            $bidObject->availability_slots
        );

        if (!$stmt->execute()) {
            die("SQL Error: " . $stmt->error . " | Error Number: " . $conn->errno);
        }

        $newId = $conn->insert_id;
        $stmt->close();
        $conn->close();

        return $newId;
    }

    public function addAttachment(int $bidId, array $file): bool
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare(
            "INSERT INTO bid_attachments (bid_id, file_path, file_name, mime_type, file_size)
             VALUES (?, ?, ?, ?, ?)"
        );
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param(
            "isssi",
            $bidId,
            $file['file_path'],
            $file['file_name'],
            $file['mime_type'],
            $file['file_size']
        );
        $ok = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $ok;
    }

    public function updateStatus(int $bidId, string $status): bool
    {
        $conn = $this->getDb();
        $stmt = $conn->prepare("UPDATE bids SET status = ? WHERE id = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("si", $status, $bidId);
        $ok = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $ok;
    }
}
