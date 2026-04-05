<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Event;
use App\Entity\EventCapacity;
use App\Entity\Location;
use App\Enum\CapacityType;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @internal
 */
class EventApiTest extends ApiTestCase
{
    protected static ?bool $alwaysBootKernel = true;

    private EntityManagerInterface $entityManager;
    private ?int $availableEventId = null;
    private ?int $unavailableEventId = null;

    protected function setUp(): void
    {
        parent::bootKernel();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->clearDatabase();
        $this->seedTestData();
    }

    protected function tearDown(): void
    {
        $this->clearDatabase();
        parent::tearDown();
    }

    private function clearDatabase(): void
    {
        $connection = $this->entityManager->getConnection();
        $connection->executeStatement('DELETE FROM event_capacity');
        $connection->executeStatement('DELETE FROM event');
        $connection->executeStatement('DELETE FROM location');
    }

    private function seedTestData(): void
    {
        $location = new Location();
        $location->setName('Test Arena');
        $location->setAddress('123 Test Street');
        $location->setDescription('A test venue');

        $this->entityManager->persist($location);

        $availableEvent = new Event();
        $availableEvent->setTitle('Available Concert');
        $availableEvent->setDescription('A concert that is available');
        $availableEvent->setDateEvent(new \DateTime('+30 days'));
        $availableEvent->setDateAvailable(new \DateTime('-1 day'));
        $availableEvent->setLocation($location);

        $capacity = new EventCapacity();
        $capacity->setName('General Admission');
        $capacity->setCapacityType(CapacityType::Standing);
        $capacity->setCapacity(100);
        $capacity->setPrice('49.99');
        $capacity->setEvent($availableEvent);
        $availableEvent->getLocationCapacities()->add($capacity);

        $unavailableEvent = new Event();
        $unavailableEvent->setTitle('Future Concert');
        $unavailableEvent->setDescription('Not yet available');
        $unavailableEvent->setDateEvent(new \DateTime('+60 days'));
        $unavailableEvent->setDateAvailable(new \DateTime('+1 day'));
        $unavailableEvent->setLocation($location);

        $unavailableCapacity = new EventCapacity();
        $unavailableCapacity->setName('General Admission');
        $unavailableCapacity->setCapacityType(CapacityType::Standing);
        $unavailableCapacity->setCapacity(50);
        $unavailableCapacity->setPrice('59.99');
        $unavailableCapacity->setEvent($unavailableEvent);
        $unavailableEvent->getLocationCapacities()->add($unavailableCapacity);

        $this->entityManager->persist($availableEvent);
        $this->entityManager->persist($capacity);
        $this->entityManager->persist($unavailableEvent);
        $this->entityManager->persist($unavailableCapacity);

        $this->entityManager->flush();

        $this->availableEventId = $availableEvent->getId();
        $this->unavailableEventId = $unavailableEvent->getId();

        $this->entityManager->clear();
    }

    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', '/api/v1/events');

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@context' => '/api/v1/contexts/Event',
            '@type' => 'Collection',
        ]);

        $this->assertCount(2, $response->toArray()['member']);
    }

    public function testGetSingleEvent(): void
    {
        $response = static::createClient()->request('GET', '/api/v1/events/'.$this->availableEventId);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@type' => 'Event',
            'title' => 'Available Concert',
            'description' => 'A concert that is available',
        ]);
    }

    public function testGetUnavailableEvent(): void
    {
        $response = static::createClient()->request('GET', '/api/v1/events/'.$this->unavailableEventId);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@type' => 'Event',
            'title' => 'Future Concert',
        ]);
    }

    public function testGetNonExistentEvent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/events/99999');

        $this->assertResponseStatusCodeSame(404);
    }
}
