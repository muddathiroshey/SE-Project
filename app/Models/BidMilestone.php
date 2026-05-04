<?php
namespace App\Models;

class BidMilestone extends Data 
{
    public $bid_id;
    public $milestone_name;
    public $deliverables;
    public $amount;
    public $duration_days;

    
    public function save(BidMilestone $msObject) 
    {
        $conn = $this->getDb();

        $sql = "INSERT INTO bid_milestones (
                    bid_id, 
                    milestone_name, 
                    deliverables, 
                    amount, 
                    duration_days
                ) VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        // الربط: i = integer, s = string, d = double
        $stmt->bind_param("issdi", 
            $msObject->bid_id,
            $msObject->milestone_name,
            $msObject->deliverables,
            $msObject->amount,
            $msObject->duration_days
        );

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }
}

?>