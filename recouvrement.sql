-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : Dim 18 avr. 2021 à 21:34
-- Version du serveur :  10.4.17-MariaDB
-- Version de PHP : 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `recouvrement`
--

-- --------------------------------------------------------

--
-- Structure de la table `caisse`
--

CREATE TABLE `caisse` (
  `id` int(11) NOT NULL,
  `solde` varchar(255) NOT NULL DEFAULT '0',
  `total_creances` varchar(255) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenoms` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `societe` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE `factures` (
  `id` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `prestations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`prestations`)),
  `grand_total` varchar(255) NOT NULL,
  `reste_a_payer` varchar(255) NOT NULL,
  `statut` enum('payer','attente') NOT NULL DEFAULT 'attente',
  `date_prestation` date NOT NULL,
  `date_limite` date NOT NULL,
  `date_derniere_relance` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `gestionnaires`
--

CREATE TABLE `gestionnaires` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `gestionnaires`
--

INSERT INTO `gestionnaires` (`id`, `nom`, `email`, `mot_de_passe`) VALUES
(1, 'Jeune codeuse', 'novasys@gmail.com', 'novasys');

-- --------------------------------------------------------

--
-- Structure de la table `mail_de_relance`
--

CREATE TABLE `mail_de_relance` (
  `id` int(11) NOT NULL,
  `sujet` varchar(255) NOT NULL DEFAULT 'Rappel',
  `texte` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `mail_de_relance`
--

INSERT INTO `mail_de_relance` (`id`, `sujet`, `texte`) VALUES
(4, 'Rappel paiement pour &lt;&lt;$info_facture[\'titre\']&gt;&gt;', 'Bonjour monsieur/madame $info_facture[\'nom\'] $info_facture[\'prenoms\'], apres la prestation de service intitule &lt;&lt;  $info_facture[\'titre\'] &gt;&gt; fournie le $info_facture[\'date_prestation\'] au cout de $info_facture[\'grand_total\'] vous nous avez versé un total de $info_facture[\'total_versement\'].\r\nVous nous devez un relicat de $info_facture[\'reste_a_payer\'] a payer avant la date du $info_facture[\'date_limite\'].\r\nFaites vous aller donner notre argent.\r\n\r\nCordialement.     ');

-- --------------------------------------------------------

--
-- Structure de la table `mode_de_paiements`
--

CREATE TABLE `mode_de_paiements` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `mode_de_paiements`
--

INSERT INTO `mode_de_paiements` (`id`, `nom`) VALUES
(1, 'chèque'),
(2, 'Espèce'),
(5, 'virement bancaire');

-- --------------------------------------------------------

--
-- Structure de la table `reglements`
--

CREATE TABLE `reglements` (
  `id` int(11) NOT NULL,
  `id_facture` int(11) NOT NULL,
  `versements` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`versements`)),
  `total_versements` varchar(255) NOT NULL DEFAULT '0',
  `date_dernier_versement` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `caisse`
--
ALTER TABLE `caisse`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `factures`
--
ALTER TABLE `factures`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `gestionnaires`
--
ALTER TABLE `gestionnaires`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `mail_de_relance`
--
ALTER TABLE `mail_de_relance`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `mode_de_paiements`
--
ALTER TABLE `mode_de_paiements`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reglements`
--
ALTER TABLE `reglements`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `caisse`
--
ALTER TABLE `caisse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `factures`
--
ALTER TABLE `factures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gestionnaires`
--
ALTER TABLE `gestionnaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `mail_de_relance`
--
ALTER TABLE `mail_de_relance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `mode_de_paiements`
--
ALTER TABLE `mode_de_paiements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `reglements`
--
ALTER TABLE `reglements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
