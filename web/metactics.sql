drop table if exists account CASCADE;
drop table if exists administration CASCADE;
drop table if exists supporttickets CASCADE;
drop table if exists matches CASCADE;
DROP TABLE IF EXISTS tournamentregistrations CASCADE;
DROP TABLE IF EXISTS tournaments CASCADE;

CREATE TABLE IF NOT EXISTS account (
  id serial NOT NULL constraint account_id PRIMARY KEY,
  pass text NOT NULL,
  btag1 text,
  btag2 text,
  btag3 text,
  btag4 text,
  btag5 text,
  btag6 text,
  btag7 text,
  email text NOT NULL unique,
  active varchar(20) NOT NULL,
  teamname text NOT NULL unique,
  "isAdmin" boolean not null default false,
  "registrationDate" TIMESTAMP with TIME ZONE not null DEFAULT now(),
  "avatarPic" text
);

INSERT INTO account (id, pass, btag1, btag2, btag3, btag4, btag5, btag6, btag7, email, active, teamname, "avatarPic") VALUES
  (1, 'pw1', 'btag11', 'btag21', 'btag31', 'btag41', 'btag51', '', '', 'email1', '0', 'asd', ''),
  (7, 'b8332426db619724f720c69d73741896', '', '', '', '', '', '', '', 'test@test.at', '1', 'Testteam', ''),
  (8, 'b8332426db619724f720c69d73741896', '', '', '', '', '', '', '', 'test2@test.at', 'ZbMWcVNDWvzI3TH', 'Testteam2', ''),
  (9, '2a49f4219490063fc79217dabede6168', '', '', '', '', '', '', '', 'ererger@rfrf.at', 'T5uL8HlLO43E6mo', 'aaaaaa', ''),
  (10, 'b8332426db619724f720c69d73741896', '', '', '', '', '', '', '', 'test3@test.at', 'R5UT67FEqxlFnua', 'Teamname', ''),
  (11, 'b8332426db619724f720c69d73741896', '', '', '', '', '', '', '', 'test5@test.at', 'dOxbo0hdAPh9EO8', 'sddedede', ''),
  (12, 'b8332426db619724f720c69d73741896', 'ergerg', 'ergerg', 'ergerg', 'ergerg', 'ergergeg', 'ergergeg', 'egrggre', 'test6@test.at', '1', 'AAbb', '150px-Flag_of_the_United_States.svg.jpg'),
  (13, 'b8332426db619724f720c69d73741896', 'wefwef', 'klu', 'kiiukiu', 'kiukiuk', 'iukiukiu', 'kiukiuk', 'iukiuk', 'test7@test.at', '1', 'asdasdaasd', 'textRotate.jpg'),
  (14, 'b8332426db619724f720c69d73741896', 'rzthrt', 'rthrth', 'rthrth', 'hrttrh', 'rthrthrht', 'rthrth', 'rthrthrth', 'test8@test.at', '1', 'asdasdaasdzj', 'xampp-logo.jpg'),
  (15, 'b8332426db619724f720c69d73741896', 'tztz', 'zztzzt', 'etztzrf', 'freferfsdfsf', 'rthrth', 'erf', 'erf', 'test9@test.at', '1', 'dwefcwefwe', 'Jellyfish.jpg'),
  (16, 'b8332426db619724f720c69d73741896', '', '', '', '', '', '', '', 'wf@wf.tz', 'kneJMsFuXdOee8M', 'rthrthtrh', ''),
  (17, 'b8332426db619724f720c69d73741896', '', '', '', '', '', '', '', 'test10@test.at', 'KrbmrKlqsBLxvmT', 'wfqqwd ffrref', ''),
  (18, 'b8332426db619724f720c69d73741896', '', '', '', '', '', '', '', 'test11@test.at', 'fvmUn96dr3o4C4g', 'Team Ultra', ''),
  (19, 'b8332426db619724f720c69d73741896', '', '', '', '', '', '', '', 'test12@test.at', 'k4beLHchSdn126O', 'Team Powerrrrrr', ''),
  (20, 'b8332426db619724f720c69d73741896', '', '', '', '', '', '', '', 'test13@test.at', 'Zs82vdVoIzygdck', 'Team MEgA', ''),
  (21, 'b8332426db619724f720c69d73741896', '', '', '', '', '', '', '', 'test14@test.at', 'EVYPpcoJLyp16q8', 'Team Super', ''),
  (22, 'b8332426db619724f720c69d73741896', '', '', '', '', '', '', '', 'wef@fwf.wfwf', 'pdinXcHZAt7o1oY', 'dqwqdw', ''),
  (23, 'b8332426db619724f720c69d73741896', '', '', '', '', '', '', '', 'weqf@fwf.wfwf', 'K8jQAMLjpma1zlF', 'dqwqdwq', ''),
  (24, 'b8332426db619724f720c69d73741896', '', '', '', '', '', '', '', 'waeqf@fwf.wfwf', 'BVCKp58N6FE3Qzb', 'refefr', ''),
  (25, 'NDW7SWxABRcFGKn', '', '', '', '', '', '', '', '', '', '', '');

select setval('account_id_seq', 25);

CREATE TABLE IF NOT EXISTS supporttickets (
  id serial NOT NULL constraint supporttickets_id PRIMARY KEY,
  "teamId" int NOT NULL references account(id),
  date timestamp with time zone NOT NULL DEFAULT now(),
  subject text NOT NULL,
  message text NOT NULL,
  response text NOT NULL,
  resolved boolean NOT NULL,
  "fileName" text NOT NULL
);

INSERT INTO supporttickets (id, "teamId", date, subject, message, response, resolved, "fileName") VALUES
  (37, 15, '2015-03-31 22:17:36', 'wfwefw', 'wfwfw\r\nfw\r\nw\r\nfw\r\nfw\r\nwf\r\nw\r\n', 'rtwhrthtrh', false, 'pico1.jpg'),
  (38, 12, '2015-04-01 22:43:31', 'tgeg', 'ergergegr', '', false, 'xampp.gif'),
  (39, 15, '2015-04-02 20:48:44', '', 'regerg', '', false, ''),
  (40, 15, '2015-04-03 22:20:55', '---Ã¶----Ã¤---', '---Ã¶----Ã¤---', '', true, ''),
  (41, 15, '2015-05-03 18:45:25', 'zjtzjtz', 'tzjtzjtzjtjtzj\r\ntzj\r\ntzj\r\ntzj\r\ntz\r\njtz\r\njtzjtzjtzjtz', '', false, 'Requiem.for.a.Dream.(2000).ab2103fe.jpg'),
  (42, 15, '2015-05-03 18:51:56', 'kj,jkzwer', 'werwer', '', false, 'Gotan_Project-Lunatico-Trasera.jpg'),
  (43, 15, '2015-05-03 18:56:35', 'wfwwfwf', 'wfefwf\r\nwfewef\r\nwf\r\nw\r\nwffw\r\n \r\n\r\nfwwwef,fw\r\nwfewefwf', '', false, ''),
  (44, 15, '2015-05-03 19:04:19', 'rtgfwefw', 'fwwefwefwfw', '', true, ''),
  (45, 15, '2015-05-03 19:09:27', 'gertgreg', 'fwf\r\nwf\r\nw\r\nfw\r\nwf\r\nwf\r\nwf\r\n', '', false, 'back.jpg'),
  (46, 15, '2015-05-04 21:10:23', 'wfwfw', 'wfwfwfw', '', true, ''),
  (47, 15, '2015-05-04 21:10:32', 'test1', '11111', '', false, ''),
  (48, 15, '2015-05-04 21:10:54', 'fwefwefwefwfwfw', 'wrfwr\r\nerg\r\nregergregger\r\n\r\neg.rg.erg.e,\r\neg.g\r\n', '', false, ''),
  (49, 15, '2015-05-05 18:14:58', 'grgreg', 'ggggrgregegegregre', '', false, ''),
  (50, 15, '2015-05-05 18:16:20', 'wefwf', 'wfwefwfwfw', '', false, ''),
  (51, 15, '2015-05-05 18:16:27', 'qwd', 'qwd', '', false, ''),
  (52, 15, '2015-05-05 18:17:31', 'trhtrh', 'rthrthrthrth', '', false, '');

select setval('supporttickets_id_seq', 53);

CREATE TABLE IF NOT EXISTS tournaments (
  id serial NOT NULL constraint tournaments_id PRIMARY KEY,
  name text NOT NULL UNIQUE,
  date timestamp with time zone NOT NULL,
  region text NOT NULL
);

INSERT INTO tournaments (id, name, date, region) VALUES
  (1, 'testT1', '2015-02-28 16:00:00', 'EU'),
  (2, 'testT2', '2015-03-07 16:00:00', 'EU'),
  (3, 'testT3', '2014-02-28 16:00:00', 'US'),
  (5, 'testT4', '2014-01-28 16:00:00', 'EU'),
  (6, 'testT6', '2015-05-28 16:00:00', 'EU'),
  (7, 'testT7', '2015-05-07 16:00:00', 'US'),
  (8, 'testT8', '2015-06-07 16:00:00', 'EU'),
  (9, 'testT62', '2015-05-28 16:00:00', 'EU'),
  (10, 'testT affe', '2015-05-04 21:55:10', 'EU');

select setval('tournaments_id_seq', 11);


CREATE TABLE IF NOT EXISTS tournamentregistrations (
  id serial NOT NULL constraint tournamentregistrations_id PRIMARY KEY,
  "tournamentId" int NOT NULL references tournaments(id),
  "teamId" int NOT NULL  references account(id),
  date timestamp with time zone NOT NULL DEFAULT now()
);


INSERT INTO tournamentregistrations (id, "tournamentId", "teamId", date) VALUES
  (20, 6, 12, '2015-04-01 22:44:12'),
  (22, 6, 13, '2015-04-01 22:45:35'),
  (46, 8, 14, '2015-04-28 21:39:29'),
  (65, 8, 13, '2015-04-29 18:37:59'),
  (66, 8, 14, '2015-04-29 18:38:12'),
  (68, 8, 13, '2015-04-29 18:38:31'),
  (69, 8, 12, '2015-04-29 18:38:35'),
  (70, 8, 11, '2015-04-29 18:38:39'),
  (71, 9, 12, '2015-04-29 18:41:57'),
  (78, 1, 15, '2015-05-01 04:26:25'),
  (79, 1, 14, '2015-05-01 04:26:33'),
  (80, 2, 12, '2015-05-05 18:28:40'),
  (81, 2, 13, '2015-05-05 18:28:48');

select setval('tournamentregistrations_id_seq', 82);


CREATE TABLE IF NOT EXISTS matches (
  id serial NOT NULL constraint matches_id PRIMARY KEY,
  "tournamentId" int NOT NULL references tournaments(id),
  "teamId1" int NOT NULL  references account(id),
  "teamId2" int NOT NULL  references account(id),
  score1 text NOT NULL,
  score2 text NOT NULL,
  "parentId" int references matches(id),
  replay text NOT NULL
);

INSERT INTO matches (id, "tournamentId", "teamId1", "teamId2", score1, score2, "parentId", replay) VALUES
  (774, 6, 12, 12, '0', '', 786, ''),
  (775, 6, 12, 12, '0', '', 786, ''),
  (776, 6, 12, 12, '0', '', 787, ''),
  (777, 6, 12, 12, '0', '', 787, ''),
  (778, 6, 12, 12, '0', '', 788, ''),
  (779, 6, 12, 12, '0', '', 788, ''),
  (780, 6, 12, 12, '0', '', 789, ''),
  (781, 6, 12, 12, '0', '', 789, ''),
  (786, 6, 12, 12, '0', '', 792, ''),
  (787, 6, 12, 12, '0', '', 792, ''),
  (788, 6, 12, 12, '0', '', 793, ''),
  (789, 6, 12, 12, '0', '', 793, ''),
  (792, 6, 12, 12, '0', '', 795, ''),
  (793, 6, 12, 12, '0', '', 795, ''),
  (795, 6, 12, 12, '0', '', 796, ''),
  (796, 6, 13, 12, '1', '0', null, '');

select setval('matches_id_seq', 797);


CREATE TABLE IF NOT EXISTS administration (
  id serial NOT NULL constraint administration_id PRIMARY KEY,
  "actualTId" int NOT NULL references tournaments(id),
  "tInProgress" boolean NOT NULL,
  "showResolvedSupportTickets" boolean NOT NULL,
  "activeTshout" text NOT NULL,
  "activeTshoutColor" text NOT NULL,
  "activeTstate" text NOT NULL,
  "activeTstateInfo" text NOT NULL,
  "activeTshowBracket" int NOT NULL,
  "activeTtimerDate" timestamp with time zone NOT NULL
);

INSERT INTO administration (id, "actualTId", "tInProgress", "showResolvedSupportTickets", "activeTshout", "activeTshoutColor", "activeTstate", "activeTstateInfo", "activeTshowBracket", "activeTtimerDate") VALUES
  (1, 2, true, true, 'delay', 'danger', 'Ready Check', 'Please log into channel metactics and tell us youre here', 1, '2015-06-07 16:45:00');

select setval('administration_id_seq', 2);
