<?php
namespace App\Models;

class Project extends Data 
{
    // Project identifiers & ownership
    public $client_id;
    public $user_id;
    
    // Step 1: Niche
    public $niche;
    public $niche_answers_json;
    
    // Step 2: Project Details
    public $project_title;
    public $project_brief;
    public $project_full_requirements;
    public $ideal_candidate;
    
    // Step 3: Milestones & Budget
    public $milestones_json;
    public $total_budget;
    public $platform_fee;
    public $specialist_receives;
    public $first_escrow_required;
    public $free_revisions;
    
    // Step 4: NDA & Privacy
    public $nda_type;
    public $nda_duration;
    public $nda_damages;
    public $nda_custom_amount;
    public $nda_file_path;
    public $profile_masking;
    public $visibility;
    
    // Step 5: Review & Post
    public $agree_terms;
    
    /**
     * Save project to database
     */
    public function save(Project $projectObject)
    {
        $conn = $this->getDb();

        $sql = "INSERT INTO project_postings (
                    client_id,
                    user_id,
                    niche,
                    niche_answers_json,
                    project_title,
                    project_brief,
                    project_full_requirements,
                    ideal_candidate,
                    milestones_json,
                    total_budget,
                    platform_fee,
                    specialist_receives,
                    first_escrow_required,
                    free_revisions,
                    nda_type,
                    nda_duration,
                    nda_damages,
                    nda_custom_amount,
                    nda_file_path,
                    profile_masking,
                    visibility,
                    status,
                    created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'posted', NOW())";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameters: i=int, s=string, d=double
        $stmt->bind_param(
            //"iisssssssjddddsssissis",
            "iisssssssddddisssisss",
            $projectObject->user_id,
            $projectObject->client_id,
            $projectObject->niche,
            $projectObject->niche_answers_json,
            $projectObject->project_title,
            $projectObject->project_brief,
            $projectObject->project_full_requirements,
            $projectObject->ideal_candidate,
            $projectObject->milestones_json,
            $projectObject->total_budget,
            $projectObject->platform_fee,
            $projectObject->specialist_receives,
            $projectObject->first_escrow_required,
            $projectObject->free_revisions,
            $projectObject->nda_type,
            $projectObject->nda_duration,
            $projectObject->nda_damages,
            $projectObject->nda_custom_amount,
            $projectObject->nda_file_path,
            $projectObject->profile_masking,
            $projectObject->visibility
        );

        if (!$stmt->execute()) {
            die("SQL Error: " . $stmt->error . " | Error Number: " . $conn->errno);
        }

        $newId = $conn->insert_id;
        $stmt->close();

        return $newId;
    }

    /**
     * Retrieve a project record by ID and populate model properties.
     */
    public function gitdata(int $projectId): ?array
    {
        $conn = $this->getDb();

        $sql = "SELECT * FROM project_postings WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param('i', $projectId);
        $stmt->execute();
        $result = $stmt->get_result();
        $project = $result->fetch_assoc();

        $stmt->close();
        $conn->close();

        if (!$project) {
            return null;
        }

        foreach ($project as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        return $project;
    }
}