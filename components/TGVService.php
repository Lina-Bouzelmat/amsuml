<?php

namespace app\components;

class TGVService
{
    /**
     * Simule la recherche de trajets TGV.
     * Dans un cas réel, cela appellerait une API externe.
     *
     * @param string $depart Ville de départ
     * @param string $arrivee Ville d'arrivée
     * @return array Tableau de trajets TGV simulés
     */
    public static function getTGV($depart, $arrivee)
    {
        // Logique de simulation
        if (strtolower($depart) == 'paris' && strtolower($arrivee) == 'lyon') {
            return [
                ['compagnie' => 'SNCF', 'numero' => 'TGV 6601', 'depart' => 'Paris Gare de Lyon', 'arrivee' => 'Lyon Part-Dieu', 'heure' => '08:00', 'duree' => '2h00', 'prix' => 55.00],
                ['compagnie' => 'SNCF', 'numero' => 'TGV 6603', 'depart' => 'Paris Gare de Lyon', 'arrivee' => 'Lyon Part-Dieu', 'heure' => '09:30', 'duree' => '2h05', 'prix' => 62.50],
            ];
        } elseif (strtolower($depart) == 'lyon' && strtolower($arrivee) == 'marseille') {
             return [
                ['compagnie' => 'SNCF', 'numero' => 'TGV 9801', 'depart' => 'Lyon Part-Dieu', 'arrivee' => 'Marseille St-Charles', 'heure' => '10:00', 'duree' => '1h40', 'prix' => 45.00],
            ];
        }
        return [];
    }
}
