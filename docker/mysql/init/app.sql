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