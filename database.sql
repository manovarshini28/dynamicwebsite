CREATE DATABASE IF NOT EXISTS portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio;

DROP TABLE IF EXISTS skills;
CREATE TABLE skills (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(40) NOT NULL,
  icon VARCHAR(10) NOT NULL,
  description VARCHAR(100) NOT NULL,
  level TINYINT UNSIGNED NOT NULL DEFAULT 50,
  message VARCHAR(160) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0
) CHARACTER SET utf8mb4;

DROP TABLE IF EXISTS projects;
CREATE TABLE projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category VARCHAR(30) NOT NULL,
  label VARCHAR(80) NOT NULL,
  title VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  tags VARCHAR(200) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0
) CHARACTER SET utf8mb4;

DROP TABLE IF EXISTS messages;
CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  email VARCHAR(120) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4;

INSERT INTO skills (name, icon, description, level, message, sort_order) VALUES
('C','Ｃ','Programming basics',70,'Learning programming basics',1),
('C++','＋','Logic & problem solving',60,'Practising logic & problem solving',2),
('Java','☕','Object-oriented basics',55,'Exploring object-oriented ideas',3),
('SQL','▤','Working with data',65,'Querying and working with data',4),
('HTML','<>','Structuring web pages',80,'Structuring web pages',5),
('CSS','✧','Responsive styling',75,'Making things responsive and pretty',6),
('PHP','⚙','Server-side basics',40,'Connecting pages to a MySQL database',7);

INSERT INTO projects (category, label, title, description, tags, sort_order) VALUES
('web','💻 WEB DESIGN · HTML + CSS','My Portfolio Website','A responsive personal website with colourful slides, playful decorations, and a little intro to my learning journey.','HTML,CSS,PHP,MySQL',1),
('social','🚁 HACKATHON · AI FOR SOCIAL GOOD','Disaster Relief Concept','A hackathon concept exploring resource allocation, pet-friendly shelters, and drone-assisted delivery during emergencies.','Social Good,Data,Teamwork',2);
