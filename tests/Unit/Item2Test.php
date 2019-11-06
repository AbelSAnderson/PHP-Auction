<?php

namespace Tests\Unit;

use App\Models\Item;
use PHPUnit\Framework\TestCase;

/**
 * Class Item2Test
 * @package Tests\Unit
 */
class Item2Test extends TestCase {

	/* @var $item \App\Models\Item */
	public $item;

	public function setUp() {
		$this->item = new Item(1, 1, "TestItem", 1.99, "TestItem", "2018-12-24");
	}

	public function test__construct() {
		$this->assertInstanceOf(Item::class, $this->item);
	}

	public function testGetImages() {
		$this->assertInternalType('array', $this->item->getImages());
	}

	public function testGetBids() {
		$this->assertInternalType('array', $this->item->getBids());
	}

}
