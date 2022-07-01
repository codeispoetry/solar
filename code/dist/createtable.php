<?php

$db = new SQLite3('data/solar.db');


$db->exec(
    'CREATE TABLE IF NOT EXISTS solar(
		timestamp DATETIME PRIMARY KEY DEFAULT CURRENT_TIMESTAMP,
		P_Grid	INTEGER,
		P_PV 	INTEGER,
		P_Akku	INTEGER,
		P_Load	INTEGER,
		rel_Autonomy INTEGER,
		rel_SelfConsumption INTEGER,
		SOC		INTEGER,
		P		INTEGER,
		E_Day	INTEGER,
		E_Total INTEGER
	)'
);
