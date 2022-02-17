<?php

namespace PhpOffice\PhpSpreadsheet\Calculation;

use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Conditional;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Confidence;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Counts;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Maximum;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Minimum;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Permutations;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\StandardDeviations;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Trends;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Variances;

/**
 * @deprecated 1.18.0
 */
class Statistical
{
    const LOG_GAMMA_X_MAX_VALUE = 2.55e305;
    const EPS = 2.22e-16;
    const MAX_VALUE = 1.2e308;
    const SQRT2PI = 2.5066282746310005024157652848110452530069867406099;

    /**
     * AVEDEV.
     *
     * Returns the average of the absolute deviations of data points from their mean.
     * AVEDEV is a measure of the variability in a data set.
     *
     * Excel Function:
     *        AVEDEV(value1[,value2[, ...]])
     *
     * @Deprecated 1.17.0
     *
     * @see Statistical\Averages::averageDeviations()
     *      Use the averageDeviations() method in the Statistical\Averages class instead
     *
     * @param mixed ...$args Data values
     *
     * @return float|string
     */
    public static function AVEDEV(...$args)
    {
        return Averages::averageDeviations(...$args);
    }

    /**
     * AVERAGE.
     *
     * Returns the average (arithmetic mean) of the arguments
     *
     * Excel Function:
     *        AVERAGE(value1[,value2[, ...]])
     *
     * @Deprecated 1.17.0
     *
     * @see Statistical\Averages::average()
     *      Use the average() method in the Statistical\Averages class instead
     *
     * @param mixed ...$args Data values
     *
     * @return float|string
     */
    public static function AVERAGE(...$args)
    {
        return Averages::average(...$args);
    }

    /**
     * AVERAGEA.
     *
     * Returns the average of its arguments, including numbers, text, and logical values
     *
     * Excel Function:
     *        AVERAGEA(value1[,value2[, ...]])
     *
     * @Deprecated 1.17.0
     *
     * @see Statistical\Averages::averageA()
     *      Use the averageA() method in the Statistical\Averages class instead
     *
     * @param mixed ...$args Data values
     *
     * @return float|string
     */
    public static function AVERAGEA(...$args)
    {
        return Averages::averageA(...$args);
    }

    /**
     * AVERAGEIF.
     *
     * Returns the average value from a range of cells that contain numbers within the list of arguments
     *
     * Excel Function:
     *        AVERAGEIF(value1[,value2[, ...]],condition)
     *
     * @Deprecated 1.17.0
     *
     * @see Statistical\Conditional::AVERAGEIF()
     *      Use the AVERAGEIF() method in the Statistical\Conditional class instead
     *
     * @param mixed $range Data values
     * @param string $condition the criteria that defines which cells will be checked
     * @param mixed[] $averageRange Data values
     *
     * @return null|float|string
     */
    public static function AVERAGEIF($range, $condition, $averageRange = [])
    {
        return Conditional::AVERAGEIF($range, $condition, $averageRange);
    }

    /**
     * BETADIST.
     *
     * Returns the beta distribution.
     *
     * @Deprecated 1.18.0
     *
     * @see Statistical\Distributions\Beta::distribution()
     *      Use the distribution() method in the Statistical\Distributions\Beta class instead
     *
     * @param float $value Value at which you want to evaluate the distribution
     * @param float $alpha Parameter to the distribution
     * @param float $beta Parameter to the distribution
     * @param mixed $rMin
     * @param mixed $rMax
     *
     * @return float|string
     */
    public static function BETADIST($value, $alpha, $beta, $rMin = 0, $rMax = 1)
    {
        return Statistical\Distributions\Beta::distribution($value, $alpha, $beta, $rMin, $rMax);
    }

    /**
     * BETAINV.
     *
     * Returns the inverse of the Beta distribution.
     *
     * @Deprecated 1.18.0
     *
     * @see Statistical\Distributions\Beta::inverse()
     *      Use the inverse() method in the Statistical\Distributions\Beta class instead
     *
     * @param float $probability Probability at which you want to evaluate the distribution
     * @param float $alpha Parameter to the distribution
     * @param float $beta Parameter to the distribution
     * @param float $rMin Minimum value
     * @param float $rMax Maximum value
     *
     * @return float|string
     */
    public static function BETAINV($probability, $alpha, $beta, $rMin = 0, $rMax = 1)
    {
        return Statistical\Distributions\Beta::inverse($probability, $alpha, $beta, $rMin, $rMax);
    }

    /**
     * BINOMDIST.
     *
     * Returns the individual term binomial distribution probability. Use BINOMDIST in problems with
     *        a fixed number of tests or trials, when the outcomes of any trial are only success or failure,
     *        when trials are independent, and when the probability of success is constant throughout the
     *        experiment. For example, BINOMDIST can calculate the probability that two of the next three
     *        babies born are male.
     *
     * @Deprecated 1.18.0
     *
     * @see Statistical\Distributions\Binomial::distribution()
     *      Use the distribution() method in the Statistical\Distributions\Binomial class instead
     *
     * @param mixed $value Number of successes in trials
     * @param mixed $trials Number of trials
     * @param mixed $probability Probability of success on each trial
     * @param mixed $cumulative
     *
     * @return float|string
     */
    public static function BINOMDIST($value, $trials, $probability, $cumulative)
    {
        return Statistical\Distributions\Binomial::distribution($value, $trials, $probability, $cumulative);
    }

    /**
     * CHIDIST.
     *
     * Returns the one-tailed probability of the chi-squared distribution.
     *
     * @Deprecated 1.18.0
     *
     * @see Statistical\Distributions\ChiSquared::distributionRightTail()
     *      Use the distributionRightTail() method in the Statistical\Distributions\ChiSquared class instead
     *
     * @param float $value Value for the function
     * @param float $degrees degrees of freedom
     *
     * @return float|string
     */
    public static function CHIDIST($value, $degrees)
    {
        return Statistical\Distributions\ChiSquared::distributionRightTail($value, $degrees);
    }

    /**
     * CHIINV.
     *
     * Returns the one-tailed probability of the chi-squared distribution.
     *
     * @Deprecated 1.18.0
     *
     * @see Statistical\Distributions\ChiSquared::inverseRightTail()
     *      Use the inverseRightTail() method in the Statistical\Distributions\ChiSquared class instead
     *
     * @param float $probability Probability for the function
     * @param float $degrees degrees of freedom
     *
     * @return float|string
     */
    public static function CHIINV($probability, $degrees)
    {
        return Statistical\Distributions\ChiSquared::inverseRightTail($probability, $degrees);
    }

    /**
     * CONFIDENCE.
     *
     * Returns the confidence interval for a population mean
     *
     * @Deprecated 1.18.0
     *
     * @see Statistical\Confidence::CONFIDENCE()
     *      Use the CONFIDENCE() method in the Statistical\Confidence class instead
     *
     * @param float $alpha
     * @param float $stdDev Standard Deviation
     * @param float $size
     *
     * @return float|string
     */
    public static function CONFIDENCE($alpha, $stdDev, $size)
    {
        return Confidence::CONFIDENCE($alpha, $stdDev, $size);
    }

    /**
     * CORREL.
     *
     * Returns covariance, the average of the products of deviations for each data point pair.
     *
     * @Deprecated 1.18.0
     *
     * @see Statistical\Trends::CORREL()
     *      Use the CORREL() method in the Statistical\Trends class instead
     *
     * @param mixed $yValues array of mixed Data Series Y
     * @param null|mixed $xValues array of mixed Data Series X
     *
     * @return float|string
     */
    public static function CORREL($yValues, $xValues = null)
    {
        return Trends::CORREL($xValues, $yValues);
    }

    /**
     * COUNT.
     *
     * Counts the number of cells that contain numbers within the list of arguments
     *
     * Excel Function:
     *        COUNT(value1[,value2[, ...]])
     *
     * @Deprecated 1.17.0
     *
     * @see Statistical\Counts::COUNT()
     *      Use the COUNT() method in the Statistical\Counts class instead
     *
     * @param mixed ...$args Data values
     *
     * @return int
     */
    public static function COUNT(...$args)
    {
        return Counts::COUNT(...$args);
    }

    /**
     * COUNTA.
     *
     * Counts the number of cells that are not empty within the list of arguments
     *
     * Excel Function:
     *        COUNTA(value1[,value2[, ...]])
     *
     * @Deprecated 1.17.0
     *
     * @see Statistical\Counts::COUNTA()
     *      Use the COUNTA() method in the Statistical\Counts class instead
     *
     * @param mixed ...$args Data values
     *
     * @return int
     */
    public static function COUNTA(...$args)
    {
        return Counts::COUNTA(...$args);
    }

    /**
     * COUNTBLANK.
     *
     * Counts the number of empty cells within the list of arguments
     *
     * Excel Function:
     *        COUNTBLANK(value1[,value2[, ...]])
     *
     * @Deprecated 1.17.0
     *
     * @see Statistical\Counts::COUNTBLANK()
     *      Use the COUNTBLANK() method in the Statistical\Counts class instead
     *
     * @param mixed ...$args Data values
     *
     * @return int
     */
    public static function COUNTBLANK(...$args)
    {
        return Counts::COUNTBLANK(...$args);
    }

    /**
     * COUNTIF.
     *
     * Counts the number of cells that contain numbers within the list of arguments
     *
     * Excel Function:
     *        COUNTIF(range,condition)
     *
     * @Deprecated 1.17.0
     *
     * @see Statistical\Conditional::COUNTIF()
     *      Use the COUNTIF() method in the Statistical\Conditional class instead
     *
     * @param mixed $range Data values
     * @param string $condition the criteria that defines which cells will be counted
     *
     * @return int
     */
    public static function COUNTIF($range, $condition)
    {
        return Conditional::COUNTIF($range, $condition);
    }

    /**
     * COUNTIFS.
     *
     * Counts the number of cells that contain numbers within the list of arguments
     *
     * Excel Function:
     *        COUNTIFS(criteria_range1, criteria1, [criteria_range2, criteria2]…)
     *
     * @Deprecated 1.17.0
     *
     * @see Statistical\Conditional::COUNTIFS()
     *      Use the COUNTIFS() method in the Statistical\Conditional class instead
     *
     * @param mixed $args Pairs of Ranges and Criteria
     *
     * @return int
     */
    public static function COUNTIFS(...$args)
    {
        return Conditional::COUNTIFS(...$args);
    }

    /**
     * COVAR.
     *
     * Returns covariance, the average of the products of deviations for each data point pair.
     *
     * @Deprecated 1.18.0
     *
     * @see Statistical\Trends::COVAR()
     *      Use the COVAR() method in the Statistical\Trends class instead
     *
     * @param mixed $yValues array of mixed Data Series Y
     * @param mixed $xValues array of mixed Data Series X
     *
     * @return float|string
     */
    public static function COVAR($yValues, $xValues)
    {
        return Trends::COVAR($yValues, $xValues);
    }

    /**
     * CRITBINOM.
     *
     * Returns the smallest value for which the cumulative binomial distribution is greater
     *        than or equal to a criterion value
     *
     * See https://support.microsoft.com/en-us/help/828117/ for details of the algorithm used
     *
     * @Deprecated 1.18.0
     *
     * @see Statistical\Distributions\Binomial::inverse()
     *      Use the inverse() method in the Statistical\Distributions\Binomial class instead
     *
     * @param float $trials number of Bernoulli trials
     * @param float $probability probability of a success on each trial
     * @param float $alpha criterion value
     *
     * @return int|string
     */
    public static function CRITBINOM($trials, $probability, $alpha)
    {
        return Statistical\Distributions\Binomial::inverse($trials, $probability, $alpha);
    }

    /**
     * DEVSQ.
     *
     * Returns the sum of squares of deviations of data points from their sample mean.
     *
     * Excel Function:
     *        DEVSQ(value1[,value2[, ...]])
     *
     * @Deprecated 1.18.0
     *
     * @see Statistical\Deviations::sumSquares()
     *      Use the sumSquares() method in the Statistical\Deviations class instead
     *
     * @param mixed ...$args Data values
     *
     * @return float|string
     */
    public static function DEVSQ(...$args)
    {
        return Statistical\Deviations::sumSquares(...$args);
    }

    /**
     * EXPONDIST.
     *
     *    Returns the exponential distribution. Use EXPONDIST to model the time between events,
     *        such as how long an automated bank teller takes to deliver cash. For example, you can
     *        use EXPONDIST to determine the probability that the process takes at most 1 minute.
     *
     * @Deprecated 1.18.0
     *
     * @see Statistical\Distributions\Exponential::distribution()
     *      Use the distribution() method in the Statistical\Distributions\Exponential class instead
     *
     * @param float $value Value of the function
     * @param float $lambda The parameter value
     * @param bool $cumulative
     *
     * @return float|string
     */
    public static function EXPONDIST($value, $lambda, $cumulative)
    {
        return Statistical\Distributions\Exponential::distribution($value, $lambda, $cumulative);
    }

    /**
     * F.DIST.
     *
     *    Returns the F probability distribution.
     *    You can use this function to determine whether two data sets have different degrees of diversity.
     *    For example, you can examine the test scores of men and women entering high school, and determine
     *        if the variability in the females is different from that found in the males.
     *
     * @Deprecated 1.18.0
     *
     * @see Statistical\Distributions\F::distribution()
     *      Use the distribution() method in the Statistical\Distributions\Exponential class instead
     *
     * @param float $value Value of the function
     * @param int $u The numerator degrees of freedom
     * @param int $v The denominator degrees of freedom
     * @param bool $cumulative If cumulative is TRUE, F.DIST returns the cumulative distribution function;
     *                         if FALSE, it returns the probability density function.
     *
     * @return float|string
     */
    public static function FDIST2($value, $u, $v, $cumulative)
    {
        return Statistical\Distributions\F::distribution($value, $u, $v, $cumulative);
    }

    /**
     * FISHER.
     *
     * Returns the Fisher transformation at x. This transformation produces a function that
     *        is normally distributed rather than skewed. Use this function to perform hypothesis
     *        testing on the correlation coefficient.
     *
     * @Deprecated 1.18.0
     *
     * @see Statistical\Distributions\Fisher::distribution()
     *      Use the distribution() method in the Statistical\Distributions\Fisher class instead
     *
     * @param float $value
     *
     * @return float|string
     */
    public static function FISHER($value)
    {
        return Statistical\Distributions\Fisher::distribution($value);
    }

    /**
     * FISHERINV.
     *
     * Returns the inverse of the Fisher transformation. Use this transformation when
     *        analyzing correlations between ranges or arrays of data. If y = FISHER(x), then
     *        FISHERINV(y) = x.
     *
     * @Deprecated 1.18.0
     *
     * @see Statisti