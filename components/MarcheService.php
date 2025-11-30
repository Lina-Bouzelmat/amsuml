<?php

namespace app\components;

class MarcheService
{
    /**
     * Simule la recherche d'un itinéraire de marche.
     * Dans un cas réel, cela appellerait une API de cartographie (ex: Google Maps API).
     *
     * @param string $depart Point de départ (peut être une adresse ou une ville)
     * @param string $arrivee Point d'arrivée (peut être une adresse ou une ville)
     * @return array Itinéraire de marche simulé
     */
    public static function getWalkingRoute($depart, $arrivee)
    {
        // Logique de simulation
        // Pour simplifier, on retourne un itinéraire générique
        $distance = rand(1, 10); // Distance aléatoire en km
        $duree = round($distance * 15 / 60, 1); // 15 min par km en moyenne

        return [
            'depart' => $depart,
            'arrivee' => $arrivee,
            'distance' => $distance . ' km',
            'duree' => $duree . ' heures',
            'instructions' => [
                "Commencez à marcher depuis " . $depart . ".",
                "Suivez les indications générales vers " . $arrivee . ".",
                "Profitez de la balade !"
            ]
        ];
    }
}
