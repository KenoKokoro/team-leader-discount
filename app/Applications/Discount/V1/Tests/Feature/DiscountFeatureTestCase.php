<?php


namespace Discount\V1\Tests\Feature;


use App\External\Client\ClientInterface;
use Discount\V1\Tests\DiscountTestCase;
use Laravel\Lumen\Testing\TestCase;
use Mockery\MockInterface;

class DiscountFeatureTestCase extends DiscountTestCase
{
    /**
     * @var ClientInterface|MockInterface
     */
    protected $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = $this->mockInstance(ClientInterface::class);
    }

    /**
     * Call the given URI with a JSON POST request.
     * @param  string $uri
     * @param  array  $data
     * @param  array  $headers
     * @return TestCase
     */
    protected function jsonPost($uri, array $data = [], array $headers = []): TestCase
    {
        return $this->json('POST', $uri, $data, $headers);
    }

    /**
     * Call the given URI with a JSON PUT request.
     * @param  string $uri
     * @param  array  $data
     * @param  array  $headers
     * @return TestCase
     */
    protected function jsonPut($uri, array $data = [], array $headers = []): TestCase
    {
        return $this->json('PUT', $uri, $data, $headers);
    }

    /**
     * Call the given URI with a JSON DELETE request.
     * @param  string $uri
     * @param  array  $data
     * @param  array  $headers
     * @return TestCase
     */
    protected function jsonDestroy($uri, array $data = [], array $headers = []): TestCase
    {
        return $this->json('DELETE', $uri, $data, $headers);
    }

    /**
     * Call the given URI with a JSON GET request.
     * @param  string $uri
     * @param  array  $data
     * @param  array  $headers
     * @return TestCase
     */
    protected function jsonGet($uri, array $data = [], array $headers = []): TestCase
    {
        return $this->json('GET', $uri, $data, $headers);
    }

    /**
     * Return array representation of the json content response
     * @param string $content
     * @return array
     */
    protected function jsonData(string $content): array
    {
        return json_decode($content, true);
    }

    /**
     * The stub that should be returned from the external API
     * @param string $id
     * @param string $revenue
     * @return array
     */
    protected function customerResponse(string $id = '2', string $revenue = '1505.95'): array
    {
        return [
            'id' => $id,
            'name' => 'John Doe',
            'since' => '2015-01-15',
            'revenue' => $revenue
        ];
    }

    protected function bearerHeader(string $key = null): array
    {
        if (is_null($key)) {
            $key = env('API_KEY');
        }

        return [
            'authorization' => "Bearer {$key}"
        ];
    }
}