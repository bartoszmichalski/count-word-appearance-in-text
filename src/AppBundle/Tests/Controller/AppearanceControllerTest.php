<?php
namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Controller\AppearanceController;

class AppearanceControllerTest extends WebTestCase
{
    public function testReplaceCharacters()
    {
        $appearance = new AppearanceController;
        $result = $appearance->replaceOtherCharacters('ala.ma,) kota');
        $this->assertNotContains('.', $result);
    }
    
    public function testReplaceCharactersNull()
    {
        $appearance = new AppearanceController;
        $this->assertSame('',trim($appearance->replaceOtherCharacters(',.?!\'()";\'\\')));
    }
    
    public function testCountAppearances()
    {
        $appearance = new AppearanceController;
        $words = [ 'ala', 'ma', 'ala', 'kot'];
        $result = $appearance->countAppearances($words);
        $this->assertEquals( 2, $result['ala'] );
    }
    
    public function testCountAppearancesArray()
    {
        $appearance = new AppearanceController;
        $this->assertEquals(['ala' => 2, 'kota' => 2, 'ma' => 1], $appearance->countAppearances([ 'Ala', '', 'kOtA', 'ma', 'ala', 'kota', '']));
    }
    
    public function testCountAppearanceArrayHasKey()
    {
        $appearance = new AppearanceController;
        $this->assertArrayHasKey('lorum', $appearance->countAppearances([ 'loruM ', '', 'ipsen ', 'ma', 'ala', 'kota', '']));
    }
}
