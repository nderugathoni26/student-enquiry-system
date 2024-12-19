CREATE DATABASE sip;
USE sip;

--table for students--
CREATE TABLE students (
    user_id INT AUTO_INCREMENT PRIMARY KEY,        
    username VARCHAR(50) NOT NULL UNIQUE,          
    email VARCHAR(100) NOT NULL UNIQUE,            
    password VARCHAR(255) NOT NULL,                
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

--table for enquiries--
CREATE TABLE enquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category VARCHAR(50) NOT NULL,
    professor_name VARCHAR(100) NOT NULL,
    enquiry_text TEXT NOT NULL,
    response TEXT,
    rejection_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reponded_at TIMESTAMP NULL,
    rejectected_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    status ENUM('Pending', 'Answered') DEFAULT 'Pending',
    FOREIGN KEY (user_id) REFERENCES students(user_id) ON DELETE CASCADE
);

CREATE TABLE resources (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    professor_name VARCHAR(255) NOT NULL,  
    category VARCHAR(100) NOT NULL,  
    title VARCHAR(255) NOT NULL,  
    description TEXT NOT NULL,  
    file_path VARCHAR(255) NOT NULL,  
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  
);


-- Table to store individual staff profiles
CREATE TABLE staff_profiles (
    profile_id INT AUTO_INCREMENT PRIMARY KEY,
    professor_name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- professors table with foreign key to 'staff_profiles'
CREATE TABLE professors (
    professor_id INT AUTO_INCREMENT PRIMARY KEY,
    profile_id INT NOT NULL,
    FOREIGN KEY (profile_id) REFERENCES staff_profiles(profile_id)
);

CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    feedback TEXT NOT NULL,
    user_role VARCHAR(50) NOT NULL,
    professor_id INT NOT NULL,
    response TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (professor_id) REFERENCES professors(professor_id) ON DELETE CASCADE ON UPDATE CASCADE
);


--table for messages--
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    recipient_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    replied_message TEXT,
    is_replied TINYINT DEFAULT 0,
    sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    replied_at DATETIME
);
