<?php

$db = new SQLite3('/var/www/html/solar/dist/data/solar.db');
$url = 'http://192.168.178.80/solar_api/v1/GetPowerFlowRealtimeData.fcgi';


$data = json_decode(file_get_contents($url));

$P                    = $data->Body->Data->Inverters->{'1'}->P;
$SOC                  = (int) $data->Body->Data->Inverters->{'1'}->SOC;
$P_Grid               = $data->Body->Data->Site->P_Grid;
$P_PV                 = $data->Body->Data->Site->P_PV;
$P_Akku               = $data->Body->Data->Site->P_Akku;
$P_Load               = $data->Body->Data->Site->P_Load;
$rel_Autonomy         = (int) $data->Body->Data->Site->rel_Autonomy;
$rel_SelfConsumption  = (int) $data->Body->Data->Site->rel_SelfConsumption;
$E_Day                = $data->Body->Data->Site->E_Day;
$E_Total              = $data->Body->Data->Site->E_Total;


$columns = ['P_Grid','P_PV','P_Akku','P_Load','rel_Autonomy','rel_SelfConsumption','SOC','P','E_Day','E_Total'];
$smt = $db->prepare(
    sprintf(
        'INSERT INTO solar (%s) values (:%s)',
        join(',', $columns),
        join(',:', $columns)
    )
);

foreach ($columns as $column) {
    $smt->bindValue(':'.$column, $$column, SQLITE3_TEXT);

    printf('<li>%s: %s</li>', $column, $$column);
}
print_r($smt);
$smt->execute();

print_r($smt);