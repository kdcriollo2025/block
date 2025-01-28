-- Tabla 'users'
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla 'password_reset_tokens'
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);

-- Tabla 'sessions'
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE SET NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NOT NULL,
    last_activity INT
);

-- Tabla 'cache'
CREATE TABLE cache (
    key VARCHAR(255) PRIMARY KEY,
    value TEXT NOT NULL,
    expiration INT NOT NULL
);

-- Tabla 'cache_locks'
CREATE TABLE cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INT NOT NULL
);

-- Tabla 'jobs'
CREATE TABLE jobs (
    id SERIAL PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload TEXT NOT NULL,
    attempts SMALLINT NOT NULL,
    reserved_at INT NULL,
    available_at INT NOT NULL,
    created_at INT NOT NULL
);

-- Tabla 'job_batches'
CREATE TABLE job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INT NOT NULL,
    pending_jobs INT NOT NULL,
    failed_jobs INT NOT NULL,
    failed_job_ids TEXT NOT NULL,
    options TEXT NULL,
    cancelled_at INT NULL,
    created_at INT NOT NULL,
    finished_at INT NULL
);

-- Tabla 'failed_jobs'
CREATE TABLE failed_jobs (
    id SERIAL PRIMARY KEY,
    uuid UUID UNIQUE NOT NULL,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla 'permissions'
CREATE TABLE permissions (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    guard_name VARCHAR(25) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (name, guard_name)
);

-- Tabla 'roles'
CREATE TABLE roles (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    guard_name VARCHAR(25) NOT NULL,
    team_foreign_key BIGINT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (name, guard_name)
);

-- Tabla 'model_has_permissions'
CREATE TABLE model_has_permissions (
    permission_id BIGINT,
    model_type VARCHAR(255) NOT NULL,
    model_id BIGINT NOT NULL,
    team_foreign_key BIGINT NULL,
    PRIMARY KEY (permission_id, model_type, model_id),
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);

-- Tabla 'model_has_roles'
CREATE TABLE model_has_roles (
    role_id BIGINT,
    model_type VARCHAR(255) NOT NULL,
    model_id BIGINT NOT NULL,
    team_foreign_key BIGINT NULL,
    PRIMARY KEY (role_id, model_type, model_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- Tabla 'role_has_permissions'
CREATE TABLE role_has_permissions (
    permission_id BIGINT,
    role_id BIGINT,
    PRIMARY KEY (permission_id, role_id),
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- Tabla 'medical_histories'
CREATE TABLE medical_histories (
    id SERIAL PRIMARY KEY,
    patient_id BIGINT REFERENCES patients(id),
    hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla 'patients'
CREATE TABLE patients (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    birth_date DATE NOT NULL,
    gender CHAR(36) NOT NULL,
    address TEXT NOT NULL,
    doctor_id BIGINT REFERENCES medicos(id), -- Relación con médicos
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla 'allergy_records'
CREATE TABLE allergy_records (
    id SERIAL PRIMARY KEY,
    medical_history_id BIGINT REFERENCES medical_histories(id),
    allergy_type VARCHAR(100) NOT NULL,
    reaction VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla 'surgery_records'
CREATE TABLE surgery_records (
    id SERIAL PRIMARY KEY,
    medical_history_id BIGINT REFERENCES medical_histories(id),
    type VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    details VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla 'medical_consultation_records'
CREATE TABLE medical_consultation_records (
    id SERIAL PRIMARY KEY,
    medical_history_id BIGINT REFERENCES medical_histories(id),
    doctor_id BIGINT REFERENCES medicos(id), -- Relación con médicos
    consultation_date DATE NOT NULL,
    reported_symptoms VARCHAR(255) NOT NULL,
    diagnosis VARCHAR(255) NOT NULL,
    treatment VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla 'therapy_records'
CREATE TABLE therapy_records (
    id SERIAL PRIMARY KEY,
    medical_history_id BIGINT REFERENCES medical_histories(id),
    type VARCHAR(50) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    detail VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla 'vaccination_records'
CREATE TABLE vaccination_records (
    id SERIAL PRIMARY KEY,
    medical_history_id BIGINT REFERENCES medical_histories(id),
    name VARCHAR(50) NOT NULL,
    date DATE NOT NULL,
    dose INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla 'medicos'
CREATE TABLE medicos (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    specialty VARCHAR(100) NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
