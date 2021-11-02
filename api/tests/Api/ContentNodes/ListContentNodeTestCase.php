<?php

namespace App\Tests\Api\ContentNodes;

use App\Entity\ContentNode;
use App\Tests\Api\ECampApiTestCase;

/**
 * Base LIST (get) test case to be used for various ContentNode types.
 *
 * This test class covers all tests that are the same across all content node implementations
 *
 * @internal
 */
abstract class ListContentNodeTestCase extends ECampApiTestCase {
    protected array $contentNodesCamp1 = [];

    protected array $contentNodesCampUnrelated = [];

    public function testListForAnonymousUser() {
        static::createBasicClient()->request('GET', $this->endpoint);
        $this->assertResponseStatusCodeSame(401);
        $this->assertJsonContains([
            'code' => 401,
            'message' => 'JWT Token not found',
        ]);
    }

    public function testListForInvitedCollaborator() {
        $response = $this->list(user: static::$fixtures['user6invited']);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContainsItems($response, []);
    }

    public function testListForInactiveCollaborator() {
        $response = $this->list(user: static::$fixtures['user5inactive']);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContainsItems($response, []);
    }

    public function testListForUnrelatedUser() {
        $response = $this->list(user: static::$fixtures['user4unrelated']);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContainsItems($response, $this->contentNodesCampUnrelated);
    }

    public function testListForGuest() {
        $response = $this->list(user: static::$fixtures['user3guest']);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContainsItems($response, $this->contentNodesCamp1);
    }

    public function testListForMember() {
        $response = $this->list(user: static::$fixtures['user2member']);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContainsItems($response, $this->contentNodesCamp1);
    }

    public function testListForManager() {
        $response = $this->list(user: static::$fixtures['user1manager']);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContainsItems($response, $this->contentNodesCamp1);
    }
}
