CREATE TABLE IF NOT EXISTS aluno (
    id_aluno INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(60) NOT NULL,
    nascimento DATE,
    salario DECIMAL(10, 2)
) ENGINE = InnoDB;

INSERT INTO aluno (nome, nascimento, salario)
VALUES ('Fulano', '1990-10-25', 1234.56);
INSERT INTO aluno (nome, nascimento, salario)
VALUES ('Beltrano', '2005-09-30', 42.42);
SELECT *
FROM aluno;