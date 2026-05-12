CREATE DATABASE IF NOT EXISTS freelance_marketplace
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE freelance_marketplace;

SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS userData (
    id            INT UNSIGNED    PRIMARY KEY AUTO_INCREMENT,
    user_email    VARCHAR(255)    UNIQUE NOT NULL,
    user_password VARCHAR(255)    NOT NULL,
    user_name     VARCHAR(150)    NOT NULL,
    user_role     ENUM('Freelancer','Client','Admin','Arbitrator') NOT NULL DEFAULT 'Client',
    is_verified   TINYINT(1)      NOT NULL DEFAULT 0,
    is_active     TINYINT(1)      NOT NULL DEFAULT 1,
    created_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_SSN      VARCHAR(20)     DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS specialistProfiles (
    id              INT UNSIGNED  PRIMARY KEY AUTO_INCREMENT,
    user_id         INT UNSIGNED  NOT NULL UNIQUE,
    full_legal_name VARCHAR(255)  NOT NULL,
    date_of_birth   DATE          NOT NULL,
    phone_number    VARCHAR(25)   NOT NULL,
    primary_niche   VARCHAR(100)  NOT NULL,
    education_level ENUM('high-school','bachelor','master','phd') NOT NULL,
    summary         TEXT          DEFAULT NULL,
    avatar_path     VARCHAR(500)  DEFAULT NULL,
    country         VARCHAR(100)  DEFAULT NULL,
    timezone        VARCHAR(80)   DEFAULT NULL,
    profile_status  ENUM('pending','under_review','approved','rejected') NOT NULL DEFAULT 'pending',
    project_number  INT UNSIGNED  DEFAULT 0,
    hourly_rate     DECIMAL(10,2) DEFAULT NULL,
    rating_avg      DECIMAL(3,2)  NOT NULL DEFAULT 0.00,
    rating_count    INT UNSIGNED  NOT NULL DEFAULT 0,
    created_at      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES userData(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS specialistSkills (
    id         INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id    INT UNSIGNED NOT NULL,
    skill_name VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES userData(id) ON DELETE CASCADE,
    UNIQUE KEY uq_user_skill (user_id, skill_name)
);

CREATE TABLE IF NOT EXISTS verificationDocuments (
    id            INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id       INT UNSIGNED NOT NULL,
    doc_type      ENUM('identity','education','cv','certificate') NOT NULL,
    doc_title     VARCHAR(255) DEFAULT NULL,
    file_path     VARCHAR(500) NOT NULL,
    file_name     VARCHAR(255) NOT NULL,
    review_status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    uploaded_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES userData(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS clientProfile (
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
    kyc_status          ENUM('incomplete','pending','verified','rejected') NOT NULL DEFAULT 'incomplete',
    kyc_verified_at     TIMESTAMP       DEFAULT NULL,
    tax_jurisdiction    VARCHAR(100)    DEFAULT NULL,
    vat_number          VARCHAR(100)    DEFAULT NULL,
    tax_id              VARCHAR(100)    DEFAULT NULL,
    billing_address     TEXT            DEFAULT NULL,
    currency            VARCHAR(10)     DEFAULT 'USD',
    profile_active      TINYINT(1)      NOT NULL DEFAULT 1,
    show_project_count  TINYINT(1)      NOT NULL DEFAULT 1,
    show_spend_band     TINYINT(1)      NOT NULL DEFAULT 0,
    allow_messages      TINYINT(1)      NOT NULL DEFAULT 0,
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

CREATE TABLE IF NOT EXISTS clientKycDocuments (
    id              INT UNSIGNED    PRIMARY KEY AUTO_INCREMENT,
    client_id       INT UNSIGNED    NOT NULL,
    doc_type        VARCHAR(100)    NOT NULL,
    doc_title       VARCHAR(255)    DEFAULT NULL,
    file_path       VARCHAR(500)    NOT NULL,
    file_name       VARCHAR(255)    NOT NULL,
    review_status   ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    reviewed_at     TIMESTAMP       DEFAULT NULL,
    uploaded_at     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clientProfile(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS clientNichePrefs (
    id          INT UNSIGNED    PRIMARY KEY AUTO_INCREMENT,
    client_id   INT UNSIGNED    NOT NULL,
    niche_name  VARCHAR(100)    NOT NULL,
    FOREIGN KEY (client_id) REFERENCES clientProfile(id) ON DELETE CASCADE,
    UNIQUE KEY uq_client_niche (client_id, niche_name)
);

CREATE TABLE IF NOT EXISTS clientKeywords (
    id          INT UNSIGNED    PRIMARY KEY AUTO_INCREMENT,
    client_id   INT UNSIGNED    NOT NULL,
    keyword     VARCHAR(150)    NOT NULL,
    FOREIGN KEY (client_id) REFERENCES clientProfile(id) ON DELETE CASCADE,
    UNIQUE KEY uq_client_keyword (client_id, keyword)
);

CREATE TABLE IF NOT EXISTS categories (
    id          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name        VARCHAR(100) NOT NULL,
    slug        VARCHAR(120) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

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
    visibility                 ENUM('public','invite_only') NOT NULL DEFAULT 'public',
    status                     ENUM('draft','posted','closed','cancelled') NOT NULL DEFAULT 'posted',
    created_at                 TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at                 TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clientProfile(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)   REFERENCES userData(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS jobs (
    id                   INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    project_posting_id   INT UNSIGNED DEFAULT NULL,
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
    FOREIGN KEY (client_id)          REFERENCES clientProfile(id)     ON DELETE CASCADE,
    FOREIGN KEY (category_id)        REFERENCES categories(id)        ON DELETE SET NULL,
    FOREIGN KEY (project_posting_id) REFERENCES project_postings(id)  ON DELETE SET NULL,
    UNIQUE KEY uq_jobs_slug (slug)
);

CREATE TABLE IF NOT EXISTS job_milestones (
    id             INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    job_id         INT UNSIGNED NOT NULL,
    milestone_name VARCHAR(255) NOT NULL,
    deliverables   TEXT DEFAULT NULL,
    amount         DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    duration_days  INT UNSIGNED NOT NULL DEFAULT 0,
    sort_order     INT UNSIGNED NOT NULL DEFAULT 0,
    created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS bids (
    id                   INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    -- NOTE: job_id references project_postings, not the jobs table.
    -- Consider renaming to posting_id for clarity.
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
    FOREIGN KEY (user_id) REFERENCES userData(id)          ON DELETE CASCADE,
    FOREIGN KEY (job_id)  REFERENCES project_postings(id)  ON DELETE CASCADE,
    UNIQUE KEY uq_bid_job_user (job_id, user_id)
);

CREATE TABLE IF NOT EXISTS bid_milestones (
    id             INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    bid_id         INT UNSIGNED NOT NULL,
    milestone_name VARCHAR(255) NOT NULL,
    deliverables   TEXT DEFAULT NULL,
    amount         DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    duration_days  INT UNSIGNED NOT NULL DEFAULT 0,
    sort_order     INT UNSIGNED NOT NULL DEFAULT 0,
    created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bid_id) REFERENCES bids(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS bid_attachments (
    id          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    bid_id      INT UNSIGNED NOT NULL,
    file_path   VARCHAR(500) NOT NULL,
    file_name   VARCHAR(255) NOT NULL,
    mime_type   VARCHAR(150) DEFAULT NULL,
    file_size   INT UNSIGNED DEFAULT NULL,
    uploaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bid_id) REFERENCES bids(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS projects (
    project_id          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    bid_id              INT UNSIGNED DEFAULT NULL,
    posting_id          INT UNSIGNED DEFAULT NULL,
    specialist_id       INT UNSIGNED NOT NULL,
    client_id           INT UNSIGNED NOT NULL,
    title               VARCHAR(255) DEFAULT NULL,
    niche               VARCHAR(100) DEFAULT NULL,
    total_amount        DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    platform_fee        DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    status              ENUM('active','disputed','completed','cancelled') NOT NULL DEFAULT 'active',
    is_done             TINYINT(1)    NOT NULL DEFAULT 0,
    started_at          TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ended_at            TIMESTAMP     DEFAULT NULL,
    created_at          TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (specialist_id) REFERENCES specialistProfiles(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id)     REFERENCES clientProfile(id)      ON DELETE CASCADE,
    FOREIGN KEY (bid_id)        REFERENCES bids(id)               ON DELETE SET NULL,
    FOREIGN KEY (posting_id)    REFERENCES project_postings(id)   ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS project_milestones (
    id               INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    project_id       INT UNSIGNED NOT NULL,
    bid_milestone_id INT UNSIGNED DEFAULT NULL,
    milestone_name   VARCHAR(255) NOT NULL,
    deliverables     TEXT DEFAULT NULL,
    amount           DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    duration_days    INT UNSIGNED NOT NULL DEFAULT 0,
    sort_order       INT UNSIGNED NOT NULL DEFAULT 0,
    status           ENUM('pending','in_progress','submitted','approved','revision_requested','paid')
                     NOT NULL DEFAULT 'pending',
    submitted_at     TIMESTAMP DEFAULT NULL,
    approved_at      TIMESTAMP DEFAULT NULL,
    due_date         DATE DEFAULT NULL,
    created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id)       REFERENCES projects(project_id) ON DELETE CASCADE,
    FOREIGN KEY (bid_milestone_id) REFERENCES bid_milestones(id)   ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS escrow (
    id              INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    project_id      INT UNSIGNED NOT NULL,
    milestone_id    INT UNSIGNED DEFAULT NULL,
    client_id       INT UNSIGNED NOT NULL,
    specialist_id   INT UNSIGNED NOT NULL,
    amount          DECIMAL(12,2) NOT NULL,
    platform_fee    DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    status          ENUM('held','released','refunded','disputed') NOT NULL DEFAULT 'held',
    released_at     TIMESTAMP DEFAULT NULL,
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id)    REFERENCES projects(project_id)    ON DELETE CASCADE,
    FOREIGN KEY (milestone_id)  REFERENCES project_milestones(id)  ON DELETE SET NULL,
    FOREIGN KEY (client_id)     REFERENCES clientProfile(id)       ON DELETE CASCADE,
    FOREIGN KEY (specialist_id) REFERENCES specialistProfiles(id)  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS wallets (
    id           INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id      INT UNSIGNED NOT NULL UNIQUE,
    balance      DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    pending      DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    currency     VARCHAR(10)   NOT NULL DEFAULT 'USD',
    created_at   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES userData(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS wallet_transactions (
    id              INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id         INT UNSIGNED NOT NULL,
    project_id      INT UNSIGNED DEFAULT NULL,
    escrow_id       INT UNSIGNED DEFAULT NULL,
    type            ENUM('deposit','withdrawal','escrow_hold','escrow_release','platform_fee','refund') NOT NULL,
    amount          DECIMAL(12,2) NOT NULL,
    balance_after   DECIMAL(12,2) NOT NULL,
    description     VARCHAR(255)  DEFAULT NULL,
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)    REFERENCES userData(id)         ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(project_id) ON DELETE SET NULL,
    FOREIGN KEY (escrow_id)  REFERENCES escrow(id)           ON DELETE SET NULL
);

-- FIX: added attachment_path and attachment_name columns
CREATE TABLE IF NOT EXISTS messages (
    id               INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    sender_id        INT UNSIGNED NOT NULL,
    receiver_id      INT UNSIGNED NOT NULL,
    project_id       INT UNSIGNED DEFAULT NULL,
    bid_id           INT UNSIGNED DEFAULT NULL,
    subject          VARCHAR(255) DEFAULT NULL,
    body             TEXT NOT NULL,
    attachment_path  VARCHAR(500) DEFAULT NULL,
    attachment_name  VARCHAR(255) DEFAULT NULL,
    is_read          TINYINT(1) NOT NULL DEFAULT 0,
    read_at          TIMESTAMP DEFAULT NULL,
    created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id)   REFERENCES userData(id)         ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES userData(id)         ON DELETE CASCADE,
    FOREIGN KEY (project_id)  REFERENCES projects(project_id) ON DELETE SET NULL,
    FOREIGN KEY (bid_id)      REFERENCES bids(id)             ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS reviews (
    id            INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    project_id    INT UNSIGNED NOT NULL,
    reviewer_id   INT UNSIGNED NOT NULL,
    reviewee_id   INT UNSIGNED NOT NULL,
    rating        TINYINT UNSIGNED NOT NULL,
    title         VARCHAR(255) DEFAULT NULL,
    comment       TEXT DEFAULT NULL,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id)  REFERENCES projects(project_id) ON DELETE CASCADE,
    FOREIGN KEY (reviewer_id) REFERENCES userData(id)         ON DELETE CASCADE,
    FOREIGN KEY (reviewee_id) REFERENCES userData(id)         ON DELETE CASCADE,
    UNIQUE KEY uq_review_project_reviewer (project_id, reviewer_id),
    CONSTRAINT chk_reviews_rating CHECK (rating BETWEEN 1 AND 5)
);

CREATE TABLE IF NOT EXISTS disputes (
    id              INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    project_id      INT UNSIGNED NOT NULL,
    raised_by       INT UNSIGNED NOT NULL,
    against         INT UNSIGNED NOT NULL,
    milestone_id    INT UNSIGNED DEFAULT NULL,
    reason          TEXT NOT NULL,
    status          ENUM('open','under_review','resolved','closed') NOT NULL DEFAULT 'open',
    resolution      TEXT DEFAULT NULL,
    arbitrator_id   INT UNSIGNED DEFAULT NULL,
    resolved_at     TIMESTAMP DEFAULT NULL,
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id)    REFERENCES projects(project_id)   ON DELETE CASCADE,
    FOREIGN KEY (raised_by)     REFERENCES userData(id)           ON DELETE CASCADE,
    FOREIGN KEY (against)       REFERENCES userData(id)           ON DELETE CASCADE,
    FOREIGN KEY (milestone_id)  REFERENCES project_milestones(id) ON DELETE SET NULL,
    FOREIGN KEY (arbitrator_id) REFERENCES userData(id)           ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS dispute_messages (
    id          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    dispute_id  INT UNSIGNED NOT NULL,
    user_id     INT UNSIGNED NOT NULL,
    body        TEXT NOT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dispute_id) REFERENCES disputes(id)  ON DELETE CASCADE,
    FOREIGN KEY (user_id)    REFERENCES userData(id)  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS notifications (
    id          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id     INT UNSIGNED NOT NULL,
    type        VARCHAR(80)  NOT NULL,
    title       VARCHAR(255) NOT NULL,
    body        TEXT         DEFAULT NULL,
    link        VARCHAR(500) DEFAULT NULL,
    is_read     TINYINT(1)   NOT NULL DEFAULT 0,
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES userData(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS kyc_reviews (
    id              INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id         INT UNSIGNED NOT NULL,
    user_role       ENUM('Freelancer','Client') NOT NULL,
    doc_table       ENUM('verificationDocuments','clientKycDocuments') NOT NULL,
    doc_id          INT UNSIGNED NOT NULL,
    status          ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    reviewer_id     INT UNSIGNED DEFAULT NULL,
    reviewer_notes  TEXT DEFAULT NULL,
    reviewed_at     TIMESTAMP DEFAULT NULL,
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)     REFERENCES userData(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewer_id) REFERENCES userData(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS sanctions (
    id          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id     INT UNSIGNED NOT NULL,
    admin_id    INT UNSIGNED NOT NULL,
    type        ENUM('warning','suspension','ban') NOT NULL,
    reason      TEXT NOT NULL,
    expires_at  TIMESTAMP DEFAULT NULL,
    lifted_at   TIMESTAMP DEFAULT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)  REFERENCES userData(id) ON DELETE CASCADE,
    FOREIGN KEY (admin_id) REFERENCES userData(id) ON DELETE CASCADE
);

-- FIX: added missing semicolons on support_tickets and support_ticket_messages
CREATE TABLE IF NOT EXISTS support_tickets (
    id          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id     INT UNSIGNED NOT NULL,
    subject     VARCHAR(255) NOT NULL,
    body        TEXT NOT NULL,
    status      ENUM('open','in_progress','resolved','closed') NOT NULL DEFAULT 'open',
    priority    ENUM('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
    assigned_to INT UNSIGNED DEFAULT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)     REFERENCES userData(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES userData(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS support_ticket_messages (
    id          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    ticket_id   INT UNSIGNED NOT NULL,
    user_id     INT UNSIGNED NOT NULL,
    body        TEXT NOT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES support_tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)   REFERENCES userData(id)        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS sessions (
    id            VARCHAR(128) PRIMARY KEY,
    user_id       INT UNSIGNED DEFAULT NULL,
    ip_address    VARCHAR(45)  DEFAULT NULL,
    user_agent    VARCHAR(500) DEFAULT NULL,
    payload       TEXT         DEFAULT NULL,
    last_activity TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES userData(id) ON DELETE CASCADE
);

INSERT IGNORE INTO categories (name, slug, description) VALUES
('Software Development',  'software-development',  'Custom software, web apps, mobile apps'),
('Design & Creative',     'design-creative',        'UI/UX, graphic design, branding'),
('Data Science & AI',     'data-science-ai',        'Machine learning, analytics, data engineering'),
('Writing & Translation', 'writing-translation',    'Copywriting, technical writing, translation'),
('Marketing & SEO',       'marketing-seo',          'Digital marketing, SEO, social media'),
('Finance & Accounting',  'finance-accounting',     'Bookkeeping, tax, financial modelling'),
('Legal',                 'legal',                  'Contract review, compliance, IP'),
('Engineering',           'engineering',            'Mechanical, civil, electrical engineering');

SET FOREIGN_KEY_CHECKS = 1;