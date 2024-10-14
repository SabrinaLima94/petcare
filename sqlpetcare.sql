-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 14-Out-2024 às 23:50
-- Versão do servidor: 5.6.34
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `petcare`
--
CREATE DATABASE IF NOT EXISTS `petcare` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `petcare`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `alimentacao`
--

CREATE TABLE `alimentacao` (
  `idAlimentacao` int(11) NOT NULL,
  `marcaRacao` varchar(255) DEFAULT NULL,
  `qtdDiaria` varchar(255) DEFAULT NULL,
  `horariosRefeicoes` varchar(255) DEFAULT NULL,
  `anotacoes` varchar(255) DEFAULT NULL,
  `idTutor` int(11) DEFAULT NULL,
  `idPet` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `animal`
--

CREATE TABLE `animal` (
  `idPet` int(11) NOT NULL,
  `nomePet` varchar(255) NOT NULL,
  `dataNascimentoPet` date DEFAULT NULL,
  `especie` varchar(100) DEFAULT NULL,
  `raca` varchar(100) DEFAULT NULL,
  `sexo` tinyint(1) DEFAULT NULL,
  `microchip` tinyint(1) DEFAULT NULL,
  `castracao` tinyint(1) DEFAULT NULL,
  `idTutor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `controleparasitario`
--

CREATE TABLE `controleparasitario` (
  `idControle` int(11) NOT NULL,
  `idPet` int(11) DEFAULT NULL,
  `idTutor` int(11) DEFAULT NULL,
  `nomeMedicacao` varchar(255) DEFAULT NULL,
  `dataAdministrada` date DEFAULT NULL,
  `categoria` varchar(255) DEFAULT NULL,
  `frequenciaUso` varchar(255) DEFAULT NULL,
  `anotacoes` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `exercicios`
--

CREATE TABLE `exercicios` (
  `idExercicios` int(11) NOT NULL,
  `idTutor` int(11) DEFAULT NULL,
  `idPet` int(11) DEFAULT NULL,
  `tipoExercicio` varchar(255) DEFAULT NULL,
  `dataExercicio` date DEFAULT NULL,
  `qtdVezesDia` int(11) DEFAULT NULL,
  `tempoMedio` int(11) DEFAULT NULL,
  `observacoes` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `higiene`
--

CREATE TABLE `higiene` (
  `igHigiene` int(11) NOT NULL,
  `idPet` int(11) DEFAULT NULL,
  `idTutor` int(11) DEFAULT NULL,
  `categoria` varchar(255) DEFAULT NULL,
  `dataHigiene` date DEFAULT NULL,
  `anotacoes` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tutor`
--

CREATE TABLE `tutor` (
  `idTutor` int(11) NOT NULL,
  `nomeTutor` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `vacinas`
--

CREATE TABLE `vacinas` (
  `idVacinas` int(11) NOT NULL,
  `idPet` int(11) DEFAULT NULL,
  `idTutor` int(11) DEFAULT NULL,
  `nomeVacina` varchar(255) DEFAULT NULL,
  `dataAdministrada` date DEFAULT NULL,
  `laboratorio` varchar(255) DEFAULT NULL,
  `lote` varchar(255) DEFAULT NULL,
  `anotacoes` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alimentacao`
--
ALTER TABLE `alimentacao`
  ADD PRIMARY KEY (`idAlimentacao`),
  ADD KEY `idTutor` (`idTutor`),
  ADD KEY `idPet` (`idPet`);

--
-- Indexes for table `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`idPet`),
  ADD KEY `idTutor` (`idTutor`);

--
-- Indexes for table `controleparasitario`
--
ALTER TABLE `controleparasitario`
  ADD PRIMARY KEY (`idControle`),
  ADD KEY `idPet` (`idPet`),
  ADD KEY `idTutor` (`idTutor`);

--
-- Indexes for table `exercicios`
--
ALTER TABLE `exercicios`
  ADD PRIMARY KEY (`idExercicios`),
  ADD KEY `idPet` (`idPet`),
  ADD KEY `idTutor` (`idTutor`);

--
-- Indexes for table `higiene`
--
ALTER TABLE `higiene`
  ADD PRIMARY KEY (`igHigiene`),
  ADD KEY `idTutor` (`idTutor`),
  ADD KEY `idPet` (`idPet`);

--
-- Indexes for table `tutor`
--
ALTER TABLE `tutor`
  ADD PRIMARY KEY (`idTutor`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vacinas`
--
ALTER TABLE `vacinas`
  ADD PRIMARY KEY (`idVacinas`),
  ADD KEY `idPet` (`idPet`),
  ADD KEY `idTutor` (`idTutor`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alimentacao`
--
ALTER TABLE `alimentacao`
  MODIFY `idAlimentacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `animal`
--
ALTER TABLE `animal`
  MODIFY `idPet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `controleparasitario`
--
ALTER TABLE `controleparasitario`
  MODIFY `idControle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `exercicios`
--
ALTER TABLE `exercicios`
  MODIFY `idExercicios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `higiene`
--
ALTER TABLE `higiene`
  MODIFY `igHigiene` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tutor`
--
ALTER TABLE `tutor`
  MODIFY `idTutor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `vacinas`
--
ALTER TABLE `vacinas`
  MODIFY `idVacinas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `alimentacao`
--
ALTER TABLE `alimentacao`
  ADD CONSTRAINT `alimentacao_ibfk_1` FOREIGN KEY (`idTutor`) REFERENCES `tutor` (`idTutor`),
  ADD CONSTRAINT `alimentacao_ibfk_2` FOREIGN KEY (`idPet`) REFERENCES `animal` (`idPet`);

--
-- Limitadores para a tabela `animal`
--
ALTER TABLE `animal`
  ADD CONSTRAINT `animal_ibfk_1` FOREIGN KEY (`idTutor`) REFERENCES `tutor` (`idTutor`);

--
-- Limitadores para a tabela `controleparasitario`
--
ALTER TABLE `controleparasitario`
  ADD CONSTRAINT `controleparasitario_ibfk_1` FOREIGN KEY (`idPet`) REFERENCES `animal` (`idPet`),
  ADD CONSTRAINT `controleparasitario_ibfk_2` FOREIGN KEY (`idTutor`) REFERENCES `tutor` (`idTutor`);

--
-- Limitadores para a tabela `exercicios`
--
ALTER TABLE `exercicios`
  ADD CONSTRAINT `exercicios_ibfk_1` FOREIGN KEY (`idPet`) REFERENCES `animal` (`idPet`),
  ADD CONSTRAINT `exercicios_ibfk_2` FOREIGN KEY (`idTutor`) REFERENCES `tutor` (`idTutor`);

--
-- Limitadores para a tabela `higiene`
--
ALTER TABLE `higiene`
  ADD CONSTRAINT `higiene_ibfk_1` FOREIGN KEY (`idTutor`) REFERENCES `tutor` (`idTutor`),
  ADD CONSTRAINT `higiene_ibfk_2` FOREIGN KEY (`idPet`) REFERENCES `animal` (`idPet`);

--
-- Limitadores para a tabela `vacinas`
--
ALTER TABLE `vacinas`
  ADD CONSTRAINT `vacinas_ibfk_1` FOREIGN KEY (`idPet`) REFERENCES `animal` (`idPet`),
  ADD CONSTRAINT `vacinas_ibfk_2` FOREIGN KEY (`idTutor`) REFERENCES `tutor` (`idTutor`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
