CREATE DATABASE oil_rescue CHARACTER SET = 'utf8mb4' collate = 'utf8mb4_general_ci';

USE oil_rescue;

-- ========================= START SYSTEM TABLES ======================

CREATE TABLE tb_logins (
    -- login info
    cd_usuario INT NOT NULL,
    nm_login VARCHAR(80),
    ds_email VARCHAR(80),
    cd_senha CHAR(64),

    -- constraints
    CONSTRAINT pk_usuario
    PRIMARY KEY (cd_usuario)
);

CREATE TABLE tb_usuarios (
    cd_usuario INT NOT NULL,
    nm_usuario VARCHAR(80),
    cd_cpf_cnpj CHAR(14),
    ds_telefone VARCHAR(100),
    cd_tipo CHAR(1),
    cd_code CHAR(64),
    dt_criacao DATE,
    cd_qt_notify INT,
    qt_advertence INT,
    ic_desativado BOOLEAN,

    -- constraints
    CONSTRAINT pk_usuario
    PRIMARY KEY (cd_usuario)
);

CREATE TABLE tb_descartes (
    cd_descarte INT NOT NULL,
    cd_usuario INT NOT NULL,
    cd_agenda INT,
    ds_descarte VARCHAR(432),
    qt_descarte FLOAT(10),
    cd_status INT,
    dt_criacao DATE,

    -- constraints
    CONSTRAINT pk_descarte
    PRIMARY KEY (cd_descarte),

    CONSTRAINT fk_id_do_descartante
    FOREIGN KEY (cd_usuario)
    REFERENCES tb_usuarios (cd_usuario)
);

CREATE TABLE tb_configs (
    -- system
    cd_usuario INT NOT NULL,

    -- config
    cd_theme INT,
    ic_premium BOOLEAN,
    qt_material FLOAT(10),
    qt_nivel INT,
    ds_atuacao VARCHAR(79),
    ds_xp FLOAT(10),

    CONSTRAINT pk_config_do_usuario
    PRIMARY KEY (cd_usuario)
);

CREATE TABLE tb_agendas (
    -- system
    cd_agenda INT NOT NULL,
    cd_usuario INT NOT NULL,

    -- info
    cd_estado INT,
    cd_cidade INT,
    dt_coleta DATE,
    hr_inicial TIME,
    hr_final TIME,

    -- constraints
    CONSTRAINT pk_agenda
    PRIMARY KEY (cd_agenda)
);

CREATE TABLE tb_notify (
    -- system
    cd_notify INT NOT NULL,
    cd_agenda INT,      --NEW
    cd_destinatario INT,
    cd_remetente INT,
    cd_descarte INT,
    cd_notify_type INT,
    ic_new_notify BOOLEAN,
    dt_emissao DATETIME,

    -- constraints
    CONSTRAINT pk_notify
    PRIMARY KEY (cd_notify)
);

CREATE TABLE tb_enderecos (
    -- system
    cd_usuario INT NOT NULL,
    cd_estado INT,
    cd_cidade INT,
    -- ds
    ds_bairro VARCHAR(100),
    ds_rua VARCHAR(100),
    ds_numero VARCHAR(10),
    ds_complemento VARCHAR(100),
    ds_cep CHAR(8),

    -- constraints
    CONSTRAINT pk_endereco_do_usuario
    PRIMARY KEY (cd_usuario)
);

CREATE TABLE tb_reviews (
    cd_review INT NOT NULL,
    cd_to INT NOT NULL,
    cd_from INT NOT NULL,
    ds_review VARCHAR(400),
    dt_review TIMESTAMP,
    ds_review_stars FLOAT,
        
    -- constraints
    CONSTRAINT pk_review
    PRIMARY KEY (cd_review)
);

CREATE TABLE tb_estados (
    cd_estado INT NOT NULL,
    nm_estado VARCHAR(20),
    
    -- constraints
    CONSTRAINT pk_estado
    PRIMARY KEY (cd_estado)
);

CREATE TABLE tb_cidades (
    cd_cidade INT NOT NULL,
    nm_cidade VARCHAR(32),

    -- constraints
    CONSTRAINT pk_cidade
    PRIMARY KEY (cd_cidade)
);

-- ========================= END SYSTEM TABLES ======================

INSERT INTO tb_usuarios 
(cd_usuario, nm_usuario, cd_cpf_cnpj, ds_telefone, cd_tipo, cd_code, dt_criacao, cd_qt_notify, qt_advertence, ic_desativado) VALUES
(0, 'System', 0, 0, 4, null, NOW(), 0, 0, 1);

-- ======================== START EVENT CONFIGS =====================

DELIMITER $$
CREATE EVENT expireAgenda
    ON SCHEDULE
        EVERY 1 DAY
    DO
      BEGIN
        DECLARE done INT DEFAULT 0;
        DECLARE id INT;
        DECLARE cur1 CURSOR FOR SELECT cd_agenda FROM tb_agendas WHERE dt_coleta < NOW();
        DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
        SET id = 0;
	    OPEN cur1;

		REPEAT
	      FETCH cur1 INTO id;
	      IF NOT done THEN
	        DELETE FROM tb_notify WHERE cd_agenda = id;
	        DELETE FROM tb_agendas WHERE cd_agenda = id;
            UPDATE tb_descartes SET cd_status = 1, cd_agenda = 0 WHERE cd_agenda = id;
	      END IF;
	    UNTIL done END REPEAT;
    END$$
DELIMITER ;


-- CREATE EVENT expireCartao
--    ON SCHEDULE
--        EVERY 1 HOUR
--    DO
--      DELETE FROM tb_descartes WHERE dt_criacao <= NOW() - INTERVAL 30 DAY;



DELIMITER $$
CREATE EVENT expireRecoveryCode
    ON SCHEDULE
        EVERY 1 DAY
    DO
      BEGIN
        DECLARE done INT DEFAULT 0;
        DECLARE id INT;
        DECLARE cur1 CURSOR FOR SELECT cd_usuario FROM tb_usuarios WHERE cd_code IS NOT NULL;
        DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
        SET id = 0;
	    OPEN cur1;

		REPEAT
	      FETCH cur1 INTO id;
	      IF NOT done THEN
            UPDATE tb_usuarios SET cd_code = null WHERE cd_usuario = id;
	      END IF;
	    UNTIL done END REPEAT;
    END$$
DELIMITER ;