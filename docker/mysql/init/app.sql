USE freelance_marketplace;


CREATE TABLE userData (
    id            INT UNSIGNED    PRIMARY KEY AUTO_INCREMENT,
    user_email    VARCHAR(255)    UNIQUE NOT NULL,
    user_password VARCHAR(255)    NOT NULL,
    user_name     VARCHAR(150)    NOT NULL,          
    user_role     ENUM('Freelancer','Client','Admin','Arbitrator') NOT NULL DEFAULT 'Client',
    is_verified   TINYINT(1)      NOT NULL DEFAULT 0,
    is_active     TINYINT(1)      NOT NULL DEFAULT 1,
    created_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_SSN      VARCHAR(20)     DEFAULT NULL,
    INDEX idx_email (user_email),
    INDEX idx_role  (user_role)
) ENGINE=InnoDB;


CREATE TABLE specialistProfiles (
    id              INT UNSIGNED  PRIMARY KEY AUTO_INCREMENT,
    user_id         INT UNSIGNED  NOT NULL UNIQUE,
    full_legal_name VARCHAR(255)  NOT NULL,
    date_of_birth   DATE          NOT NULL,
    phone_number    VARCHAR(25)   NOT NULL,
    primary_niche   VARCHAR(100)  NOT NULL,
    education_level ENUM('high-school','bachelor','master','phd') NOT NULL,
    summary         TEXT          DEFAULT NULL,
    profile_status  ENUM('pending','under_review','approved','rejected') NOT NULL DEFAULT 'pending',
    created_at      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    project_number  INT UNSIGNED DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES userData(id) ON DELETE CASCADE,
    INDEX idx_status (profile_status),
    INDEX idx_niche  (primary_niche)
) ENGINE=InnoDB;


CREATE TABLE specialistSkills (
    id         INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id    INT UNSIGNED NOT NULL,
    skill_name VARCHAR(100) NOT NULL,

    FOREIGN KEY (user_id) REFERENCES userData(id) ON DELETE CASCADE,
    UNIQUE KEY uq_user_skill (user_id, skill_name),
    INDEX idx_skill (skill_name)
) ENGINE=InnoDB;

CREATE TABLE verificationDocuments (
    id            INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id       INT UNSIGNED NOT NULL,
    doc_type      ENUM('identity','education','cv','certificate') NOT NULL,
    doc_title     VARCHAR(255) DEFAULT NULL,   
    file_path     VARCHAR(500) NOT NULL,
    file_name     VARCHAR(255) NOT NULL,
    review_status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    uploaded_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES userData(id) ON DELETE CASCADE,
    INDEX idx_user   (user_id),
    INDEX idx_status (review_status)
) ENGINE=InnoDB;

CREATE TABLE clientProfile (
    id                  INT UNSIGNED    PRIMARY KEY AUTO_INCREMENT,
    user_id             INT UNSIGNED    NOT NULL UNIQUE,

    
    job_title           VARCHAR(150)    DEFAULT NULL,
    country             VARCHAR(100)    DEFAULT NULL,
    timezone            VARCHAR(80)     DEFAULT NULL,
    phone_number        VARCHAR(25)     DEFAULT NULL,
    avatar_path         VARCHAR(500)    DEFAULT NULL,

   
    org_name            VARCHAR(255)    DEFAULT NULL,
    org_type            VARCHAR(100)    DEFAULT NULL,
    org_industry        VARCHAR(100)    DEFAULT NULL,
    org_industry_other  VARCHAR(150)    DEFAULT NULL,  
    org_website         VARCHAR(255)    DEFAULT NULL,
    org_reg_country     VARCHAR(100)    DEFAULT NULL,
    org_reg_number      VARCHAR(100)    DEFAULT NULL,
    org_bio             TEXT            DEFAULT NULL,
    org_address         VARCHAR(500)    DEFAULT NULL,
    logo_path           VARCHAR(500)    DEFAULT NULL,

    
    hiring_description  TEXT            DEFAULT NULL,  

    -- Section 4: KYC
    kyc_status          ENUM('incomplete','pending','verified','rejected')
                                        NOT NULL DEFAULT 'incomplete',
    kyc_verified_at     TIMESTAMP       DEFAULT NULL,

    -- Section 5: Billing & Tax
    tax_jurisdiction    VARCHAR(100)    DEFAULT NULL,
    vat_number          VARCHAR(100)    DEFAULT NULL,
    tax_id              VARCHAR(100)    DEFAULT NULL,
    billing_address     TEXT            DEFAULT NULL,
    currency            VARCHAR(10)     DEFAULT 'USD',

    -- Section 6: Privacy & Visibility
    profile_active      TINYINT(1)      NOT NULL DEFAULT 1,
    show_project_count  TINYINT(1)      NOT NULL DEFAULT 1,
    show_spend_band     TINYINT(1)      NOT NULL DEFAULT 0,
    allow_messages      TINYINT(1)      NOT NULL DEFAULT 0,

    -- Stats (denormalized for public profile display)
    projects_posted     INT UNSIGNED    NOT NULL DEFAULT 0,
    projects_completed  INT UNSIGNED    NOT NULL DEFAULT 0,
    total_spent         DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    repeat_hire_rate    DECIMAL(5,2)    NOT NULL DEFAULT 0.00,
    dispute_rate        DECIMAL(5,2)    NOT NULL DEFAULT 0.00,
    reputation_score    DECIMAL(4,2)    NOT NULL DEFAULT 0.00,

    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES userData(id) ON DELETE CASCADE

) ENGINE=InnoDB;

CREATE TABLE clientKycDocuments (
    id              INT UNSIGNED    PRIMARY KEY AUTO_INCREMENT,
    client_id       INT UNSIGNED    NOT NULL,
    doc_type        VARCHAR(100)    NOT NULL,   
    doc_title       VARCHAR(255)    DEFAULT NULL,
    file_path       VARCHAR(500)    NOT NULL,
    file_name       VARCHAR(255)    NOT NULL,
    review_status   ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    reviewed_at     TIMESTAMP       DEFAULT NULL,
    uploaded_at     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (client_id) REFERENCES clientProfile(id) ON DELETE CASCADE,
    INDEX idx_client (client_id),
    INDEX idx_status (review_status)
) ENGINE=InnoDB;


-- Niche preferences (the toggle cards in Section 3)
CREATE TABLE clientNichePrefs (
    id          INT UNSIGNED    PRIMARY KEY AUTO_INCREMENT,
    client_id   INT UNSIGNED    NOT NULL,
    niche_name  VARCHAR(100)    NOT NULL,

    FOREIGN KEY (client_id) REFERENCES clientProfile(id) ON DELETE CASCADE,
    UNIQUE KEY uq_client_niche (client_id, niche_name)
) ENGINE=InnoDB;

CREATE TABLE clientKeywords (
    id          INT UNSIGNED    PRIMARY KEY AUTO_INCREMENT,
    client_id   INT UNSIGNED    NOT NULL,
    keyword     VARCHAR(150)    NOT NULL,

    FOREIGN KEY (client_id) REFERENCES clientProfile(id) ON DELETE CASCADE,
    UNIQUE KEY uq_client_keyword (client_id, keyword),
    INDEX idx_keyword (keyword)
) ENGINE=InnoDB;

CREATE TABLE projects (
    project_id      INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    specialist_id   INT UNSIGNED  NOT NULL ,
    client_id       INT UNSIGNED  NOT NULL ,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ended_at        TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_done         TINYINT(1)      NOT NULL DEFAULT 0,

    FOREIGN KEY (specialist_id) REFERENCES specialistProfiles(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES clientProfile(id) ON DELETE CASCADE
);

CREATE TABLE categories (
    id          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name        VARCHAR(100) NOT NULL,
    slug        VARCHAR(120) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_name (name)
) ENGINE=InnoDB;

CREATE TABLE jobs (
    id                   INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    client_id            INT UNSIGNED NOT NULL,
    category_id          INT UNSIGNED DEFAULT NULL,
    title                VARCHAR(255) NOT NULL,
    slug                 VARCHAR(255) DEFAULT NULL,
    project_brief        TEXT NOT NULL,
    full_requirements    TEXT DEFAULT NULL,
    budget_min           DECIMAL(12,2) DEFAULT NULL,
    budget_max           DECIMAL(12,2) DEFAULT NULL,
    fixed_budget         DECIMAL(12,2) DEFAULT NULL,
    currency             VARCHAR(10) NOT NULL DEFAULT 'USD',
    visibility           ENUM('public','invite_only') NOT NULL DEFAULT 'public',
    experience_level     ENUM('entry','intermediate','expert') DEFAULT NULL,
    status               ENUM('draft','open','in_review','awarded','closed','cancelled') NOT NULL DEFAULT 'open',
    nda_required         TINYINT(1) NOT NULL DEFAULT 0,
    bid_deadline         DATETIME DEFAULT NULL,
    start_date           DATE DEFAULT NULL,
    created_at           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (client_id) REFERENCES clientProfile(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    UNIQUE KEY uq_jobs_slug (slug),
    INDEX idx_jobs_client (client_id),
    INDEX idx_jobs_status (status),
    INDEX idx_jobs_category (category_id)
) ENGINE=InnoDB;

CREATE TABLE project_postings (
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

CREATE TABLE job_milestones (
    id             INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    job_id         INT UNSIGNED NOT NULL,
    milestone_name VARCHAR(255) NOT NULL,
    deliverables   TEXT DEFAULT NULL,
    amount         DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    duration_days  INT UNSIGNED NOT NULL DEFAULT 0,
    sort_order     INT UNSIGNED NOT NULL DEFAULT 0,
    created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    INDEX idx_job_milestones_job (job_id)
) ENGINE=InnoDB;

CREATE TABLE bids (
    id                   INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    job_id               INT UNSIGNED NOT NULL,
    user_id              INT UNSIGNED NOT NULL,
    proposal_message     TEXT NOT NULL,
    key_differentiators  TEXT DEFAULT NULL,
    relevant_work        TEXT DEFAULT NULL,
    total_bid_amount     DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    bid_rationale        TEXT DEFAULT NULL,
    start_date           DATE DEFAULT NULL,
    availability_slots   JSON DEFAULT NULL,
    free_reviews         INT UNSIGNED NOT NULL DEFAULT 0,
    review_price         DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    status               ENUM('draft','submitted','shortlisted','accepted','rejected','withdrawn') NOT NULL DEFAULT 'submitted',
    client_feedback      TEXT DEFAULT NULL,
    submitted_at         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES userData(id) ON DELETE CASCADE,
    UNIQUE KEY uq_bid_job_user (job_id, user_id),
    INDEX idx_bids_job (job_id),
    INDEX idx_bids_user (user_id),
    INDEX idx_bids_status (status)
) ENGINE=InnoDB;

CREATE TABLE bid_milestones (
    id             INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    bid_id         INT UNSIGNED NOT NULL,
    milestone_name VARCHAR(255) NOT NULL,
    deliverables   TEXT DEFAULT NULL,
    amount         DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    duration_days  INT UNSIGNED NOT NULL DEFAULT 0,
    sort_order     INT UNSIGNED NOT NULL DEFAULT 0,
    created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (bid_id) REFERENCES bids(id) ON DELETE CASCADE,
    INDEX idx_bid_milestones_bid (bid_id)
) ENGINE=InnoDB;

CREATE TABLE bid_attachments (
    id          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    bid_id      INT UNSIGNED NOT NULL,
    file_path   VARCHAR(500) NOT NULL,
    file_name   VARCHAR(255) NOT NULL,
    mime_type   VARCHAR(150) DEFAULT NULL,
    file_size   INT UNSIGNED DEFAULT NULL,
    uploaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (bid_id) REFERENCES bids(id) ON DELETE CASCADE,
    INDEX idx_bid_attachments_bid (bid_id)
) ENGINE=InnoDB;

CREATE TABLE messages (
    id           INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    sender_id    INT UNSIGNED NOT NULL,
    receiver_id  INT UNSIGNED NOT NULL,
    project_id   INT UNSIGNED DEFAULT NULL,
    bid_id       INT UNSIGNED DEFAULT NULL,
    subject      VARCHAR(255) DEFAULT NULL,
    body         TEXT NOT NULL,
    is_read      TINYINT(1) NOT NULL DEFAULT 0,
    read_at      TIMESTAMP DEFAULT NULL,
    created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (sender_id) REFERENCES userData(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES userData(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(project_id) ON DELETE SET NULL,
    FOREIGN KEY (bid_id) REFERENCES bids(id) ON DELETE SET NULL,
    INDEX idx_messages_sender (sender_id),
    INDEX idx_messages_receiver (receiver_id),
    INDEX idx_messages_project (project_id),
    INDEX idx_messages_bid (bid_id)
) ENGINE=InnoDB;

CREATE TABLE reviews (
    id            INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    project_id    INT UNSIGNED NOT NULL,
    reviewer_id   INT UNSIGNED NOT NULL,
    reviewee_id   INT UNSIGNED NOT NULL,
    rating        TINYINT UNSIGNED NOT NULL,
    title         VARCHAR(255) DEFAULT NULL,
    comment       TEXT DEFAULT NULL,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (project_id) REFERENCES projects(project_id) ON DELETE CASCADE,
    FOREIGN KEY (reviewer_id) REFERENCES userData(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewee_id) REFERENCES userData(id) ON DELETE CASCADE,
    UNIQUE KEY uq_review_project_reviewer (project_id, reviewer_id),
    INDEX idx_reviews_reviewee (reviewee_id),
    CONSTRAINT chk_reviews_rating CHECK (rating BETWEEN 1 AND 5)
) ENGINE=InnoDB;

CREATE TABLE sessions (
    id            VARCHAR(128) PRIMARY KEY,
    user_id       INT UNSIGNED DEFAULT NULL,
    ip_address    VARCHAR(45) DEFAULT NULL,
    user_agent    VARCHAR(500) DEFAULT NULL,
    payload       TEXT DEFAULT NULL,
    last_activity TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES userData(id) ON DELETE CASCADE,
    INDEX idx_sessions_user (user_id),
    INDEX idx_sessions_activity (last_activity)
) ENGINE=InnoDB;
