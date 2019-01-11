<?php

namespace SDM\FizzBuzz;

interface FizzBuzzInterface
{
    /**
     * FizzBuzz calculator.
     *
     * @param int $testNumber The number to test
     * @param int $fizz       A number which should be divided to say Fizz
     * @param int $buzz       A number which should be divided to say Buzz
     *
     * @return string
     *
     * @throws \RuntimeException Exception thrown if the test number is above 100
     * @throws \RuntimeException Exception thrown if the test number is below 1
     */
    public function getResults(int $testNumber, int $fizz = 3, int $buzz = 5): string;
}
