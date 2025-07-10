<?php

namespace Tests\Traits;

trait NotSuccessfulTestTrait
{
    /**
     * Assert the response status is not in the 200 range.
     *
     * @param  \Illuminate\Testing\TestResponse  $response
     * @return void
     */
    protected function assertNotSuccessful($response)
    {
        $status = $response->getStatusCode();
        $this->assertNotBetween(200, 299, $status, "Response status code [$status] is in the 200 range.");
    }

    /**
     * Assert that the given value is not between the given range.
     *
     * @param  int  $lower
     * @param  int  $upper
     * @param  int  $value
     * @param  string  $message
     * @return void
     */
    protected function assertNotBetween($lower, $upper, $value, $message = '')
    {
        $this->assertFalse($value >= $lower && $value <= $upper, $message);
    }
}
