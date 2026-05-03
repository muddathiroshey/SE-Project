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
    specialist_id   INT UNSIGNED  NOT NULL UNIQUE,
    client_id       INT UNSIGNED  NOT NULL UNIQUE,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ended_at        TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_done         TINYINT(1)      NOT NULL DEFAULT 0,

    FOREIGN KEY (specialist_id) REFERENCES specialistProfiles(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES clientProfile(id) ON DELETE CASCADE
);
