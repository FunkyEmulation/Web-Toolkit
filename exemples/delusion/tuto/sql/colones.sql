ALTER TABLE `accounts` ADD COLUMN(
 `points` int(11) NOT NULL DEFAULT '0',
 `vote` text(100) NULL,
 `heurevote` bigint(100) NOT NULL,
 `logged` int(1) NOT NULL
);