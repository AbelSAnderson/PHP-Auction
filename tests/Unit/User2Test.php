<?php

namespace Tests\Unit;

use App\Lib\Database;
use App\Models\User;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest
 * @package Tests\Unit
 */
class User2Test extends TestCase {

	public function setUp() {
		$pass = password_hash("TestPass", PASSWORD_BCRYPT, ['cost' => 10]);
		Database::getConnection()->sqlQuery("INSERT INTO `users` (`username`, `password`, `email`, `verify`, `active`) VALUES('TestUser', '{$pass}', 'test@test.com', '1234', 1);");
	}

	public function tearDown() {
		Database::getConnection()->sqlQuery("DELETE FROM `users` WHERE username = 'TestUser';");
		Database::getConnection()->sqlQuery("ALTER TABLE `users` AUTO_INCREMENT = 1;");
	}

	public function testAuth() {
		$this->assertInstanceOf(User::class, User::auth("test@test.com", "TestPass"));
	}

	public function testAuthFail() {
		$this->assertFalse(User::auth("TEST", "TestFAIL"));
	}
}