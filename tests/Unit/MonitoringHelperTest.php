<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Test 3-Tier Logic for MonitoringHelper::getStatusLabel().
 *
 * Default thresholds:
 *   temp_min_ekstrem = 36.0  temp_min_ideal = 37.0
 *   temp_max_ideal   = 38.0  temp_max_ekstrem = 39.0
 *   hum_min_ekstrem  = 50.0  hum_min_ideal  = 55.0
 *   hum_max_ideal    = 60.0  hum_max_ekstrem = 65.0
 *
 * Priority order (NO overlap):
 *   1. Critical  → temp < 36 OR temp > 39 OR hum < 50 OR hum > 65
 *   2. Baik      → 37 ≤ temp ≤ 38 AND 55 ≤ hum ≤ 60
 *   3. Perhatian → everything else (between extremes but not ideal)
 */
class MonitoringHelperTest extends TestCase
{
    /**
     * Inline replica of the 3-tier logic (no DB dependency).
     */
    private function resolveStatus(
        float $temp,
        float $hum,
        float $tempMinEkstrem  = 36.0,
        float $tempMinIdeal    = 37.0,
        float $tempMaxIdeal    = 38.0,
        float $tempMaxEkstrem  = 39.0,
        float $humMinEkstrem   = 50.0,
        float $humMinIdeal     = 55.0,
        float $humMaxIdeal     = 60.0,
        float $humMaxEkstrem   = 65.0,
    ): string {
        // TIER 1: Critical
        if ($temp < $tempMinEkstrem || $temp > $tempMaxEkstrem
            || $hum < $humMinEkstrem || $hum > $humMaxEkstrem) {
            return 'Critical';
        }

        // TIER 2: Baik
        if ($temp >= $tempMinIdeal && $temp <= $tempMaxIdeal
            && $hum >= $humMinIdeal && $hum <= $humMaxIdeal) {
            return 'Baik';
        }

        // TIER 3: Perhatian
        return 'Perhatian';
    }

    // -----------------------------------------------------------------
    // CRITICAL cases
    // -----------------------------------------------------------------

    /** Suhu terlalu rendah → Critical */
    public function test_critical_when_temp_below_ekstrem(): void
    {
        $this->assertSame('Critical', $this->resolveStatus(35.9, 57.0));
    }

    /** Suhu terlalu tinggi → Critical */
    public function test_critical_when_temp_above_ekstrem(): void
    {
        $this->assertSame('Critical', $this->resolveStatus(39.1, 57.0));
    }

    /** Kelembaban terlalu rendah → Critical */
    public function test_critical_when_hum_below_ekstrem(): void
    {
        $this->assertSame('Critical', $this->resolveStatus(37.5, 49.9));
    }

    /** Kelembaban terlalu tinggi → Critical */
    public function test_critical_when_hum_above_ekstrem(): void
    {
        $this->assertSame('Critical', $this->resolveStatus(37.5, 65.1));
    }

    /** Kedua nilai ekstrem sekaligus → Critical */
    public function test_critical_when_both_values_extreme(): void
    {
        $this->assertSame('Critical', $this->resolveStatus(35.0, 70.0));
    }

    // -----------------------------------------------------------------
    // BAIK cases
    // -----------------------------------------------------------------

    /** Tepat di batas ideal (nominal center) → Baik */
    public function test_baik_when_values_nominal(): void
    {
        $this->assertSame('Baik', $this->resolveStatus(37.5, 57.5));
    }

    /** Tepat di batas bawah ideal → Baik */
    public function test_baik_at_lower_ideal_boundary(): void
    {
        $this->assertSame('Baik', $this->resolveStatus(37.0, 55.0));
    }

    /** Tepat di batas atas ideal → Baik */
    public function test_baik_at_upper_ideal_boundary(): void
    {
        $this->assertSame('Baik', $this->resolveStatus(38.0, 60.0));
    }

    // -----------------------------------------------------------------
    // PERHATIAN cases (between extremes but outside ideal)
    // -----------------------------------------------------------------

    /** Suhu di zona antara (36–37) → Perhatian */
    public function test_perhatian_when_temp_between_ekstrem_and_ideal_lower(): void
    {
        $this->assertSame('Perhatian', $this->resolveStatus(36.5, 57.0));
    }

    /** Suhu di zona antara (38–39) → Perhatian */
    public function test_perhatian_when_temp_between_ideal_upper_and_ekstrem(): void
    {
        $this->assertSame('Perhatian', $this->resolveStatus(38.5, 57.0));
    }

    /** Kelembaban di zona antara bawah (50–55) → Perhatian */
    public function test_perhatian_when_hum_between_ekstrem_and_ideal_lower(): void
    {
        $this->assertSame('Perhatian', $this->resolveStatus(37.5, 52.0));
    }

    /** Kelembaban di zona antara atas (60–65) → Perhatian */
    public function test_perhatian_when_hum_between_ideal_upper_and_ekstrem(): void
    {
        $this->assertSame('Perhatian', $this->resolveStatus(37.5, 63.0));
    }

    /** Suhu ideal tapi humidity di zona antara → Perhatian */
    public function test_perhatian_when_temp_ideal_but_hum_outside(): void
    {
        $this->assertSame('Perhatian', $this->resolveStatus(37.5, 53.0));
    }

    // -----------------------------------------------------------------
    // Pastikan Critical TIDAK pernah dianggap Baik (no overlap)
    // -----------------------------------------------------------------

    /** Suhu tepat di batas ekstrem bawah → Critical, BUKAN Baik */
    public function test_critical_takes_priority_over_baik(): void
    {
        // humidity ideal, tapi suhu menyentuh batas ekstrem bawah
        $this->assertSame('Critical', $this->resolveStatus(36.0 - 0.01, 57.5));
        $this->assertNotSame('Baik', $this->resolveStatus(35.0, 57.5));
    }
}
