<?php

if (!function_exists('formatDate')) {
    /**
     * Formats a given date string to a specific format.
     *
     * This function takes a date string as input and returns it in the format 'd/m/Y H:i' or 'd/m/Y'.
     * The format used depends on the second parameter, which indicates whether to include the time.
     *
     * @param string $date_string The date string to be formatted. It can be a valid date string or a timestamp.
     * @param bool $avec_heure Optional. If true, the time will be included in the format. Default is true.
     * @return string The formatted date string.
     */
    function formatDate($date_string, $avec_heure = false): string
    {
        // Vérifier si le nombre est numérique
        if (!$date_string) {
            return '';
        }

        // Convertir en chaîne pour manipuler les décimales
        $date = \Carbon\Carbon::parse($date_string);

        if ($avec_heure) {
            return $date->format('d/m/Y H:i');
        } else {
            return $date->format('d/m/Y');
        }
    }
}
