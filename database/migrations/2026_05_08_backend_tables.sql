CREATE TABLE IF NOT EXISTS project_postings (
    id                         INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    client_id                  INT UNSIGNED NOT NULL,
    user_id                    INT UNSIGNED NOT NULL,
    niche                      VARCHAR(100) NOT NULL,
    niche_answers_json         JSON DEFAULT NULL,
    project_title              VARCHAR(255) NOT NULL,
    project_brief              TEXT NOT NULL,
    project_full_requirements  TEXT DEFAULT NULL,
    ideal_candidate            TEXT DEFAULT NULL,
    milestones_json            JSON DEFAULT NULL,
    total_budget               DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    platform_fee               DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    specialist_receives        DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    first_escrow_required      DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    free_revisions             TINYINT(1) NOT NULL DEFAULT 0,
    nda_type                   ENUM('standard','custom') NOT NULL DEFAULT 'standard',
    nda_duration               VARCHAR(100) DEFAULT NULL,
    nda_damages                VARCHAR(100) DEFAULT NULL,
    nda_custom_amount          INT UNSIGNED NOT NULL DEFAULT 0,
    nda_file_path              VARCHAR(500) DEFAULT NULL,
    profile_masking            TINYINT(1) NOT NULL DEFAULT 0,
    visibility                 ENUM('public','invite_only','invitation-only') NOT NULL DEFAULT 'public',
    status                     ENUM('draft','posted','closed','cancelled') NOT NULL DEFAULT 'posted',
    created_at                 TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at                 TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (client_id) REFERENCES clientProfile(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES userData(id) ON DELETE CASCADE,
    INDEX idx_project_postings_client (client_id),
    INDEX idx_project_postings_status (status),
    INDEX idx_project_postings_niche (niche)
) ENGINE=InnoDB;

ALTER TABLE bids
    ADD COLUMN IF NOT EXISTS free_reviews INT UNSIGNED NOT NULL DEFAULT 0 AFTER availability_slots,
    ADD COLUMN IF NOT EXISTS review_price DECIMAL(12,2) NOT NULL DEFAULT 0.00 AFTER free_reviews;
