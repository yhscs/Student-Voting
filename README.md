# Student-Voting
A web app for students to vote for homecoming/prom court.

MySQL database tables:

Table to store general info about voting
```SQL
CREATE TABLE `election` (
  `Active` tinyint(1) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `9thGirls` int(11) NOT NULL,
  `9thBoys` int(11) NOT NULL,
  `10thGirls` int(11) NOT NULL,
  `10thBoys` int(11) NOT NULL,
  `11thGirls` int(11) NOT NULL,
  `11thBoys` int(11) NOT NULL,
  `12thGirls` int(11) NOT NULL,
  `12thBoys` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

Table to store voters and results
```SQL
CREATE TABLE `electionVoters` (
  `ID` varchar(7) NOT NULL,
  `Last Name` varchar(255) NOT NULL,
  `First Name` varchar(255) NOT NULL,
  `Grade` int(11) NOT NULL,
  `Gender` varchar(1) NOT NULL,
  `Voted` tinyint(1) NOT NULL,
  `Candidate` tinyint(1) NOT NULL DEFAULT '1',
  `Votes` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```SQL
ALTER TABLE `election`
  ADD PRIMARY KEY (`Title`),
  ADD KEY `Title` (`Title`);

ALTER TABLE `electionVoters`
  ADD PRIMARY KEY (`ID`);
  ```
