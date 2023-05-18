CREATE DATABASE tech_laughs CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE tech_laughs;

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL,
    duration INT NOT NULL,
    level VARCHAR(50) NOT NULL
);

INSERT INTO courses (title, description, duration, level) VALUES
('A arte de criar bugs', 'Descubra como deixar seu código repleto de surpresas inesperadas', 120, 'Avançado'),
('Programação para gatos', 'Ensine seu felino a escrever código e se tornar o próximo gênio da computação', 90, 'Iniciante'),
('Risos e código: Uma combinação perfeita', 'Aprenda a programar enquanto ri das piadas mais nerds do universo da tecnologia', 60, 'Intermediário'),
('Como irritar um programador', 'Domine as técnicas mais eficazes para deixar um programador à beira de um ataque de nervos', 180, 'Avançado'),
('Programação para palhaços', 'Descubra como criar software divertido e engraçado, mesmo se você tem nariz de palhaço', 120, 'Intermediário'),
('Hackeando a risada: Programação de comédia', 'Aprenda a criar algoritmos hilários que farão seus programas rirem junto com você', 90, 'Iniciante'),
('O guia definitivo do código esquisito', 'Explore as maravilhas do código bizarro e como sobreviver a ele', 240, 'Avançado'),
('Programação para preguiçosos', 'Automatize todas as tarefas chatas e aproveite para tirar uma soneca enquanto o código trabalha por você', 60, 'Iniciante');

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
);

INSERT INTO students (name, email) VALUES
('Elon Musk', 'elon.musk@spacex.com'),
('Bill Gates', 'bill.gates@microsoft.com'),
('Nikola Tesla', 'nikola.tesla@alternating.com'),
('Steve Jobs', 'steve.jobs@macintosh.com'),
('Alan Turing', 'alan.turing@numbers.com');

CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    enrollment_date DATE NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

INSERT INTO enrollments (student_id, course_id, enrollment_date) VALUES
(1, 1, '2023-05-01'),
(2, 3, '2023-04-15'),
(3, 5, '2023-05-10'),
(3, 1, '2023-04-30'),
(1, 6, '2023-05-02'),
(5, 1, '2023-05-08'),
(4, 2, '2023-04-28'),
(2, 4, '2023-05-07'),
(5, 4, '2023-05-05'),
(3, 3, '2023-05-11'),
(2, 6, '2023-05-03'),
(4, 3, '2023-05-12'),
(5, 7, '2023-05-09'),
(1, 2, '2023-05-14'),
(4, 5, '2023-05-06');
