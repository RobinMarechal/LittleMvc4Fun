<?php
/**
 * Created by IntelliJ IDEA.
 * User: Utilisateur
 * Date: 19/12/2017
 * Time: 20:20
 */

namespace Test\Unit\App;

use function assertTrue;
use PHPUnit\Framework\TestCase;
use stdClass;

class TestsTest extends TestCase
{
	/**
	 * @Test
	 */
    public function testTruc()
	{
		$this->assertTrue(true, "true should be true");
		$this->assertTrue(true, "true should be true");
	}


	/**
	 * @Test
	 * @depends testTruc
	 */
	public function testTruc2()
	{
		$this->assertEquals(2, 2, "not eq");
	}


	/**
	 * @Test
	 * @depends testTruc2
	 */
	public function testTruc3()
	{
		$this->assertNull(null, "not eq");
	}
}
