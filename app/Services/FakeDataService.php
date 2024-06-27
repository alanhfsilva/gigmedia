<?php

namespace App\Services;

class FakeDataService
{
    /**
     * This method generate all possible non-empty combinations of an array returning them as an array of strings with lowercase.
     *
     * @param array $array
     * @return array
     */
    public function generateCombinations(array $array): array
    {
        $results = [];

        // Get the total number of possible combinations
        $maximumCombinations = pow(2, count($array)) - 1;

        for ($i = 1; $i <= $maximumCombinations; $i++) {
            $generatedCombination = [];

            for ($j = 0; $j < count($array); $j++) {
                if ($i & (1 << $j)) {
                    $generatedCombination[] = strtolower($array[$j]);
                }
            }

            $results[] = implode(' ', $generatedCombination);
        }

        return $results;
    }
}
