SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `cad_pets` (
  `cod_pets_pet` int NOT NULL,
  `des_nome_pet` varchar(255) NOT NULL,
  `des_especie_pet` varchar(255) NOT NULL,
  `cod_usuario_pet` int DEFAULT NULL
) ENGINE=InnoDB;

--
-- Despejando dados para a tabela `cad_pets`
--

/*INSERT INTO `cad_pets` (`cod_pets_pet`, `des_nome_pet`, `des_especie_pet`, `cod_usuario_pet`) VALUES
(2, 'Louro', 'Cachorro', 1),
(3, 'Hashtag', 'Hamster', 21),
(4, 'Pato', 'Gato', 19);*/

-- --------------------------------------------------------

--
-- Estrutura para tabela `cad_usuario`
--

CREATE TABLE `cad_usuario` (
  `cod_usuario_usr` int NOT NULL,
  `des_usuario_usr` varchar(255) NOT NULL,
  `des_email_usr` varchar(255) NOT NULL,
  `des_senha_usr` varchar(255) NOT NULL,
  `des_cargo_usr` tinyint NOT NULL
) ENGINE=InnoDB ;

--
-- Despejando dados para a tabela `cad_usuario`
--

INSERT INTO `cad_usuario` (`cod_usuario_usr`, `des_usuario_usr`, `des_email_usr`, `des_senha_usr`, `des_cargo_usr`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$10$.kmoS2Zepx4xEK.DPPKfxeI//BPSP93em/DJUR6OMUEl8V8ngQD46', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tab_agendamentos`
--

CREATE TABLE `tab_agendamentos` (
  `cod_agendamento_agd` int NOT NULL,
  `dta_agendamento_agd` datetime NOT NULL,
  `cod_usuario_agd` int DEFAULT NULL,
  `cod_pets_agd` int DEFAULT NULL
) ENGINE=InnoDB;

-- Índices de tabelas apagadas
--

--
-- Índices de tabela `cad_pets`
--
ALTER TABLE `cad_pets`
  ADD PRIMARY KEY (`cod_pets_pet`),
  ADD KEY `idx_cod_usuario_pet` (`cod_usuario_pet`);

--
-- Índices de tabela `cad_usuario`
--
ALTER TABLE `cad_usuario`
  ADD PRIMARY KEY (`cod_usuario_usr`),
  ADD UNIQUE KEY `des_email_usr` (`des_email_usr`);

--
-- Índices de tabela `tab_agendamentos`
--
ALTER TABLE `tab_agendamentos`
  ADD PRIMARY KEY (`cod_agendamento_agd`),
  ADD KEY `idx_cod_usuario_agd` (`cod_usuario_agd`),
  ADD KEY `idx_cod_pets_agd` (`cod_pets_agd`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `cad_pets`
--
ALTER TABLE `cad_pets`
  MODIFY `cod_pets_pet` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `cad_usuario`
--
ALTER TABLE `cad_usuario`
  MODIFY `cod_usuario_usr` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `tab_agendamentos`
--
ALTER TABLE `tab_agendamentos`
  MODIFY `cod_agendamento_agd` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `cad_pets`
--
ALTER TABLE `cad_pets`
  ADD CONSTRAINT `cad_pets_ibfk_1` FOREIGN KEY (`cod_usuario_pet`) REFERENCES `cad_usuario` (`cod_usuario_usr`);

--
-- Restrições para tabelas `tab_agendamentos`
--
ALTER TABLE `tab_agendamentos`
  ADD CONSTRAINT `tab_agendamentos_ibfk_1` FOREIGN KEY (`cod_pets_agd`) REFERENCES `cad_pets` (`cod_pets_pet`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
