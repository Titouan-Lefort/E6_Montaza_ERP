<?php

if (!function_exists('formatNumber')) {
    /**
     * Formats a given number to a string with appropriate formatting.
     *
     * This function takes a number as input and returns it as a formatted string.
     * The formatting includes adding commas as thousand separators and ensuring
     * a fixed number of decimal places.
     *
     * @param mixed $number The number to be formatted. It can be an integer, float, or a string representing a number.
     * @param bool $without_space Optional. If true, the formatted number will not include spaces as thousand separators.
     * @return string The formatted number as a string.
     */
     function formatNumber($number,$without_space = false): string {
        // Vérifier si le nombre est numérique
        if (!is_numeric($number)) {
            return (string)$number;
        }

        // Convertir en chaîne pour manipuler les décimales
        $nombre = (string)$number;

        // Séparer la partie entière et la partie décimale
        $parties = explode('.', $nombre);
        $partie_entiere = $parties[0];
        $partie_decimale = $parties[1] ?? ''; // Utiliser une chaîne vide si aucune décimale

        // Supprimer les zéros inutiles après la virgule
        $partie_decimale = rtrim($partie_decimale, '0');

        // Formater la partie entière avec des espaces entre les milliers
        if ($without_space) {
            $partie_entiere_formattee = number_format((float)$partie_entiere, 0, '.', '');
        } else {
            $partie_entiere_formattee = number_format((float)$partie_entiere, 0, '.', ' ');
        }

        // Si la partie décimale n'est pas vide, on l'ajoute au résultat final
        if ($partie_decimale !== '') {
            return $partie_entiere_formattee . '.' . $partie_decimale;
        }

        return $partie_entiere_formattee;
    }
    /**
     * Formats a number as a currency string in euros.
     *
     * This function formats a given number to a string representing an amount in euros,
     * with two decimal places and appropriate spacing.
     *
     * @param mixed $number The number to be formatted. It can be an integer, float, or a string representing a number.
     * @param bool $without_dollar Optional. If true, the euro symbol will not be appended.
     * @param bool $without_space Optional. If true, the formatted number will not include spaces as thousand separators.
     * @return string The formatted currency string.
     */
    function formatNumberArgent($number,$without_dollar = false,$without_space = false): string {
        // Vérifier si le nombre est numérique
        if (!is_numeric($number)) {
            return (string)$number;
        }
        // Convertir en chaîne pour manipuler les décimales
        $number = (float)$number;
        // Formater le nombre avec deux décimales
        if ($without_space) {
            $number = number_format($number, 2, '.', '');
        } else {
            $number = number_format($number, 2, '.', ' ');
        }
        // Ajouter le symbole de l'euro si nécessaire
        if (!$without_dollar) {
            $number .= ' €';
        }
        // Retourner le nombre formaté
        return $number;
    }

    /**
     * Formats a number of bytes into a human-readable string with appropriate units.
     * This function converts a number of bytes into a more readable format,
     * such as kilobytes (KB), megabytes (MB), gigabytes (GB), etc.
     *
     * @param int|float $bytes The number of bytes to format.
     * @param bool $without_space Optional. If true, the formatted number will not include spaces as thousand separators.
     * @param bool $without_unit Optional. If true, the unit (e.g., KB, MB) will not be appended to the formatted number.
     * @return string The formatted number with appropriate units.
     */
    function formatNumberBytes($bytes, $without_space = false, $without_unit = false): string {
        // Vérifier si le nombre est numérique
        if (!is_numeric($bytes)) {
            return (string)$bytes;
        }

        // Définir les unités de mesure
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $unitIndex = 0;

        // Convertir les octets en unités appropriées
        while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
            $bytes /= 1024;
            $unitIndex++;
        }

        // Déterminer le nombre de décimales à afficher (2 pour les unités supérieures à B)
        $decimals = ($unitIndex === 0) ? 0 : 2;

        // Formater le nombre avec ou sans espace
        if ($without_space) {
            $formattedNumber = number_format($bytes, $decimals, '.', '');
        } else {
            $formattedNumber = number_format($bytes, $decimals, '.', ' ');
        }

        // Ajouter l'unité de mesure si nécessaire
        if (!$without_unit) {
            $formattedNumber .= ' ' . $units[$unitIndex];
        }

        return $formattedNumber;
    }
}
