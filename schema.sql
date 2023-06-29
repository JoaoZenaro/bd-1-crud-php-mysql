CREATE TABLE aluno (
    id_aluno INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(60) NOT NULL,
    nascimento DATE,
    salario DECIMAL(10, 2)
) ENGINE=InnoDB;


INSERT INTO aluno (nome, nascimento, salario) VALUES ('Fulano', '1990-10-25', 1234.56);
INSERT INTO aluno (nome, nascimento, salario) VALUES ('Beltrano', '2005-09-30', 42.42);


ALTER TABLE aluno ADD COLUMN foto LONGBLOB NULL AFTER salario;

CREATE TABLE curso (
    id_curso INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(60) NOT NULL
) ENGINE=InnoDB;

INSERT INTO curso VALUES (null, 'Ciência da Computação'),
                        (null, 'Engenharia de Software'),
                        (null, 'Sistemas de Informação'),
                        (null, 'Design');


ALTER TABLE aluno ADD COLUMN id_curso INT AFTER salario;

UPDATE aluno SET id_curso = 1 WHERE id_aluno = 1;
UPDATE aluno SET id_curso = 2 WHERE id_aluno = 2;

ALTER TABLE aluno ADD FOREIGN KEY (id_curso) REFERENCES curso (id_curso);
ALTER TABLE aluno ADD COLUMN ativo BOOLEAN NOT NULL DEFAULT true AFTER salario;
ALTER TABLE aluno ADD COLUMN sexo ENUM('m', 'f', 'n') NOT NULL DEFAULT 'n' AFTER salario;

UPDATE aluno SET sexo = 'm' WHERE id_aluno = 1;
UPDATE aluno SET ativo = false WHERE id_aluno = 2;