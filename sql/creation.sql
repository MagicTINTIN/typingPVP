create table tpvpGames (gID Serial PRIMARY KEY, started DATETIME NOT NULL, host VARCHAR(30) NOT NULL, code VARCHAR(30) NOT NULL, CONSTRAINT UNIQUE(gID));

CREATE TABLE tpvpPlayers (pID Serial PRIMARY KEY,
                          game bigint(20) unsigned NOT null REFERENCES tpvpGames(gID) ON DELETE CASCADE,
                          name varchar(30) not null,
                          wordCount INT not null DEFAULT 0,
                          charCount int not null DEFAULT 0,
                          CONSTRAINT UNIQUE(pID,game));
                          
create table tpvpWords(wID serial,game bigint(20) unsigned references tpvpGames(gID) on DELETE CASCADE,
                       word varchar(50) not null,
                       flag varchar(1) default 0 not null,
                       CONSTRAINT PRIMARY KEY(wID,game));
                       
create table tpvpPlayerWords(player bigint(20) unsigned REFERENCES tpvpPlayers(pID) on DELETE CASCADE,
                             word bigint(20) unsigned references tpvpWords(wID) on delete cascade,
                             TTL datetime not null default DATE_ADD(NOW(), INTERVAL 1 DAY_MINUTE))